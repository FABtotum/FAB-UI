#!/usr/bin/env python
import serial
import time
import os, sys

import ConfigParser
import argparse
import logging

import json
import threading
import Queue

import gcode_utils, cura_utils

from subprocess import call

from contextlib import contextmanager

from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler


############################################
############# GLOBAL VARIABLES #############
ser = None # Will be replaced by a Serial() object during initialization
recvq = []
trace_fname = ""
lock_fname  = ""

statistics = {
    "pid": os.getpid(),
    "actual_layer": 0,
    "layers": 0,
    "line": 0,
    "z_override": 0,
    "fan": 0,
    "fan_locked": False,
    "rpm": 0,
    "gcode_file": "",
    "gcode_lines": 0,
    "startedat": 0,
    "completedat": 0,
    "printdone": False,
    "started": False,
    "paused": False,
    "task_id": "0", 
    "print_type": "",
    "state": "printing",
    "engine": ""
}

temperatures = {
    "extruder": 0,
    "extruder_target": 0,
    "bed": 0,
    "bed_target": 0
}

############################################

############################################
################ EXCEPTIONS ################
class PrintInterruptedException(Exception):
    pass
class PrintPausedException(Exception):
    pass
class PrintResumedException(Exception):
    pass
class ReplyNotFoundException(Exception):
    pass
class PrintWaitTemperatures(Exception):
    pass
class PrintCompleted(Exception):
    pass
class PrintKilled(Exception):
    pass
############################################

def trace(s):
    print s
    logging.info(s)

def reset_trace():
    try:
        open(trace_fname, 'w').close()
    except IOError:
        pass

def lock_printer():
    open(lock_fname, 'w').close()

def unlock_printer():
    try:
        os.remove(lock_fname)
    except OSError:
        pass

@contextmanager
def read_buffered(skip_read=False):
    global recvq
    ##
    # __enter__()
    if not skip_read:
        recvq.append(ser.readline().strip())
        while ser.inWaiting():
            recvq.append(ser.readline().strip())
    ##
    yield recvq
    ##
    # __exit__()
    # purge recv queue from the processed replies
    recvq = [val for val in recvq if val is not None]
    ##

##################################################
#################### PARSERS #####################

def parse_comment(line):       
    def cura_comment(comment):
        cp = cura_utils.process_comment(comment)
        if cp is not None and cp[0] == 'layer':
            statistics["actual_layer"] = int(cp[1])+1

    if statistics["engine"] == "CURA":
        cura_comment(line)

    if line.startswith(";;"):
        trace(".> %s" % line[2:])

# Returns True if execution should block, False (or None) otherwise
def handle_errors(error_code, gline):  
    ERROR_CODES = {
        "100": "ERROR_KILLED",
        "101": "ERROR_STOPPED",
        "102": "ERROR_DOOR_OPEN",
        "103": "ERROR_MIN_TEMP",
        "104": "ERROR_MAX_TEMP",
        "105": "ERROR_MAX_BED_TEMP",
        "106": "ERROR_X_MAX_ENDSTOP",
        "107": "ERROR_X_MIN_ENDSTOP",
        "108": "ERROR_Y_MAX_ENDSTOP",
        "109": "ERROR_Y_MIN_ENDSTOP",
        "110": "ERROR_IDLE_SAFETY",
        "120": "ERROR_Y_BOTH_TRIGGERED",
        "121": "ERROR_Z_BOTH_TRIGGERED",
        "122": "ERROR_AMBIENT_TEMP",
        "123": "ERROR_EXTRUDE_MINTEMP",
        "124": "ERROR_LONG_EXTRUSION",
# disabled at the time of writing, but we catch it anyway
        "125": "ERROR_HEAD_ABSENT"
    }

    trace(
        "!!! Error while executing '%s' (line %d): %s" % (
            gline,
            statistics["line"],
            ERROR_CODES[error_code]
        )
    )

    return True

# Returns True if execution should block, False (or None) otherwise
def handle_echo_reply(message):
    trace("solicited echo: %s" % message)

# Returns True if execution should block, False (or None) otherwise
def parse_unattended():
    with read_buffered(skip_read=True) as q:
        for cnt, reply in enumerate(q):
            if reply.startswith("echo:"):
                message = reply.split(":", 1)[1]
                q[cnt] = None
                trace("unattended echo: %s" % message)
            else:
                q[cnt] = None
                trace("spurious message: %s" % reply)

##################################################
##################################################

def polled_write(s, expected="ok", cmpfunc=lambda r,e: r==e):
    global recvq

    #print "writing:'%s' expecting:'%s' recvq:'%s'" % (s, expected, recvq)
    ser.write("%s\n" % s)
    if not expected:
        return

    breakOuter = False
    replyFound = False
    exc = None
    reply = None

    while not breakOuter:
        with read_buffered() as q:
            #print "recvq: %s" % q
            for cnt, msg in enumerate(q):
                if msg.startswith("ERROR :"):
                    # Mark our reply as read. list.pop() must not be used 
                    # here because it changes the list length causing the
                    # loop to exit prematurely.
                    q[cnt] = None
                    reply = msg
                    msg = msg.split(":", 1)[1]
                    if handle_errors(msg.strip(), s):
                        exc = PrintInterruptedException
                        breakOuter = True
                        break
                elif msg.startswith("echo:"):
                    q[cnt] = None
                    reply = msg
                    msg = msg.split(":", 1)[1]
                    if handle_echo_reply(msg.strip()):
                        exc = PrintInterruptedException
                        breakOuter = True
                        break
                # checking the expected reply last, so we can catch
                # higher priority messages first
                elif cmpfunc(msg, expected):
                    q[cnt] = None
                    reply = msg
                    breakOuter = True
                    replyFound = True

    # raising the exceptions directly into the loop prevents calling
    # the __exit__ method of the context manager and the cleanup
    # of the recvq.
    if exc:
        raise exc

    if not replyFound:
        raise ReplyNotFoundException(
            ("Critical: cannot find the expected reply '%s'.\r\n" + 
             "recvq: %s") % (expected, q)
        )

    return reply

def spool_gline(line, expected="ok"):
    line = line.strip()
    if not line:
        return

    return polled_write(line, expected)

def spool_multiple(stream):
    if isinstance(stream, str):
        iterable = stream.splitlines()
    else:
        iterable = stream

    for line in iterable:
        spool_gline(line)

##################################################
##################################################

def ask_for_temps():
    ext_temp, bed_temp = 0, 0

    reply = polled_write("M105", "ok T:", cmpfunc=lambda r,e: r.startswith(e))
    tokens = reply.split(" ")
    try:
        ext, extt, bed, bedt = tokens[1:5] # T:xx.x /xx.x B:xx.x /xx.x
        ext_temp, bed_temp = float(ext[2:]), float(bed[2:])
        ext_target, bed_target = float(extt[1:]), float(bedt[1:])
        temperatures["extruder"] = ext_temp
        temperatures["bed"] = bed_temp
        #temperatures["extruder_target"] = ext_target
        #temperatures["bed_target"] = bed_target

    except ValueError:
        pass

        # M109-M190 format parser, here for future use (maybe)
        """
        try:
            ext, _, bed = tokens
            ext_temp, bed_temp = float(ext[2:]), float(bed[2:])
            temperatures["extruder"] = ext_temp
            temperatures["bed"] = bed_temp
        except ValueError:
            pass
        """

    return ext_temp, bed_temp

###
# unused at the moment

def macro(gline, expected=None, timeout=0, description="", delay=0, verbose=True):
    polled_write(gline, expected)
    if description and verbose:
        trace(description)
    time.sleep(delay)

def start_print():
    trace("Preparing the FABtotum Personal Fabricator")
    macro("G27", "ok", description="Homing axes (Z down)", delay=5)
    #macro("G27 X Y", "ok")
    macro("G90", "ok")
    macro("G0 X5 Y5 Z10 F10000", "ok", delay=10)
    macro("G28", "ok", description="Homing with probe", delay=10)
    macro("G90", "ok", 2, "Setting absolute position", 0, verbose=False)
    macro("G0 X10 Y10 Z60 F1500", "ok", 3, "Moving to oozing point", 1)
    #pre heating M104 S0
    macro("M220 S100", "ok", 1, "Reset Speed factor override", 0.1, verbose=False)
    macro("M221 S100", "ok", 1, "Reset Extruder factor override", 0.1, verbose=False)
    macro("M121", "ok", description="Enabling endstops")
    #macro("M106 S255","ok",1,"Turning Fan On",1) moved to FW
    #macro("M92 E"+str(units['e']),"ok",1,"Setting extruder mode",0.1,verbose=False)

##################################################
##################################################

def end_print(fast_end=False):
    procedures = {}

    procedures["mill"] = (
    """
        M121
    """
    )

    if fast_end:
        procedures["print"] = (
        """
            M121
            M220 S100
            G91
            G0 Z+2
            G90
        """
        )
    else:
        procedures["print"] = (
        """
            M121
            M220 S100
            G91
            G0 Z+1 E-5 F1000
            G90
            G0 X200 Y200 F2500
        """
        ) 

    print_type = statistics["print_type"]
    try:
        proc = procedures[print_type]
        spool_multiple(proc)
    except KeyError:
        pass

def finalize(status, task_id):
    trace("Moving to safe zone")
    call(
        ["sudo php /var/www/fabui/script/finalize.php %s %s %s" % (
            task_id, statistics["print_type"], status)],
        shell=True
    )


######################################################
######################################################

''' WRITE STATS ON MONITOR FILE '''
class JSONWriter(threading.Thread):

    def __init__(self, overrides_queue, group=None, target=None, name=None,
                 verbose=None, *args, **kwargs):
        super(JSONWriter, self).__init__(
            group=group, target=target, name=name, verbose=verbose
        )
        self._statistics = kwargs["statistics"]
        self._temperatures = kwargs["temperatures"]
        self._monitor_file = kwargs["monitor_file"]
        self._every = kwargs["every"]
        self._die = False
        self._ovr_queue = overrides_queue

    def update_json(self):
        statistics = self._statistics
        temperatures = self._temperatures

        _layers = {
            'total': [statistics["layers"]],
            'actual': statistics["actual_layer"]
        }
        _stats = {
            "percent": str(100.0 * statistics["line"] / statistics["gcode_lines"]),
            "line_number": str(statistics["line"]),
            "extruder": str(temperatures["extruder"]),
            "bed": str(temperatures["bed"]),
            "extruder_target": str(temperatures["extruder_target"]),
            "bed_target": str(temperatures["bed_target"]),
            "z_override": str(statistics["z_override"]),
            "layers": _layers,
            "fan": str(statistics["fan"]),
            "rpm": str(statistics["rpm"])
        }
        _tip = {
            "show": str(""),
            "message": str("")
        }
        _print = {
            "name": statistics["gcode_file"],
            "lines": str(statistics["gcode_lines"]),
            "print_started": str(statistics["started"]),
            "started": statistics["startedat"],
            "status": str(statistics["state"]),
            "completed": str(statistics["printdone"]),
            "completed_time": statistics["completedat"],
            "shutdown": "",
            "tip": _tip,
            "stats": _stats
        }
        stats = {
            "type": "print",
            "print": _print,
            "engine": statistics["engine"],
            "pid": statistics["pid"]
        }
        
        stats_file = open(self._monitor_file,'w+')
        stats_file.write(json.dumps(stats))
        stats_file.close()

    def run(self):
        while not self._die:
            if statistics["state"] == "printing":
                self._ovr_queue.put("!temps")
            self.update_json()
            time.sleep(self._every)

    def die(self):
        self._die = True

##################################################
##################################################

class OverrideCommandsHandler(PatternMatchingEventHandler):
    def __init__(self, overrides_queue, *args, **kwargs):
        super(OverrideCommandsHandler, self).__init__(*args, **kwargs)
        self._ovr_queue = overrides_queue
        self._command_file = kwargs["patterns"][0]

    def catch_all(self, event, op):
        if event.is_directory:
            return
        if event.src_path == self._command_file:
            with open(event.src_path) as f:
                for line in f:
                    c = line.rstrip()
                    if c:
                        self._ovr_queue.put(c)
            open(event.src_path, 'w').close()

    def on_modified(self, event):
        self.catch_all(event, 'MOD')

##################################################
################# OVERRIDES ######################

def do_ovr_kill(params, stats):
    print "do_ovr_kill() called: '%s'" % params
    raise PrintKilled

def do_ovr_pause(params, stats):
    print "do_ovr_pause() called: '%s'" % params
    raise PrintPausedException

def do_ovr_resume(params, stats):
    print "do_ovr_resume() called: '%s'" % params
    raise PrintResumedException

def do_ovr_zplus(params, stats):
    print "do_ovr_zplus() called: '%s'" % params
    try:
        z_increment = float(params[0])
        trace("Z height increased by %.2f mm" % z_increment)
    except IndexError:
        trace("zplus: No parameter given")
        return
    except ValueError:
        trace("zplus: Invalid parameter given: '%s'" % params[0])
        return

    stats["z_override"] += z_increment
    return """
        G91
        G0 Z+%f
        G90
    """ % z_increment

def do_ovr_zminus(params, stats):
    print "do_ovr_zminus() called: '%s'" % params
    try:
        z_decrement = float(params[0])
        trace("Z height decreased by %.2f mm" % z_decrement)
    except IndexError:
        trace("zminus: No parameter given")
        return
    except ValueError:
        trace("zminus: Invalid parameter given: '%s'" % params[0])
        return

    stats["z_override"] -= z_decrement
    return """
        G91
        G0 Z-%f
        G90
    """ % z_decrement

def do_ovr_gettemps(params, stats):
    ask_for_temps()

def do_ovr_rpm_cw(params, stats):
    try:
        stats["rpm"] = int(params[0][1:], 10)
    except IndexError:
        trace("ovr_rpm_cw: No parameter given")
        return
    except ValueError:
        trace("ovr_rpm_cw: Invalid parameter given: '%s'" % params[0])
        return

    return "M3 %s" % params[0]

def do_ovr_rpm_ccw(params, stats):
    try:
        stats["rpm"] = int(params[0][1:], 10)
    except IndexError:
        trace("ovr_rpm_ccw: No parameter given")
        return
    except ValueError:
        trace("ovr_rpm_ccw: Invalid parameter given: '%s'" % params[0])
        return

    return "M4 %s" % params[0]


def do_ovr_extt(params, stats):
    print "do_ovr_extt() called: '%s'" % params
    try:
        temperatures["extruder_target"] = float(params[0][1:])
        if temperatures["extruder_target"] > 0:
            trace("Extruder temperature set to %.f &deg;C" % temperatures["extruder_target"])
    except IndexError:
        trace("ovr_extt: No parameter given")
        return
    except ValueError:
        trace("ovr_extt: Invalid parameter given: '%s'" % params[0])
        return
    
    return "M104 %s" % params[0]

def do_ovr_fan(params, stats):
    print "do_ovr_fan() called: '%s'" % params
    try:
        stats["fan"] = float(params[0][1:])
    except IndexError:
        trace("ovr_fan: No parameter given")
        return
    except ValueError:
        trace("ovr_fan: Invalid parameter given: '%s'" % params[0])
        return

    return "M106 %s" % params[0]

def do_ovr_bedt(params, stats):
    print "do_ovr_bedt() called: '%s'" % params
    try:
        temperatures["bed_target"] = float(params[0][1:])
        if temperatures["bed_target"] > 0:
            trace("Bed temperature set to %.f &deg;C" % temperatures["bed_target"])
    except IndexError:
        trace("ovr_bedt: No parameter given")
        return
    except ValueError:
        trace("ovr_bedt: Invalid parameter given: '%s'" % params[0])
        return

    return "M140 %s" % params[0]

def do_ovr_speed(params, stats):
    print "do_ovr_speed() called: '%s'" % params
    try:
        trace("Speed factor ovveride set to %d%%" % int(params[0][1:], 10))
        return "M220 S%d" % int(params[0][1:], 10)
    except IndexError:
        trace("ovr_speed: No parameter given")
    except ValueError:
        trace("ovr_speed: Invalid parameter given: '%s'" % params[0])

def do_ovr_extrusion(params, stats):
    print "do_ovr_extrusion() called: '%s'" % params
    try:
        trace("Extruder factor override set %d%%" % int(params[0][1:], 10))
        return "M221 S%d" % int(params[0][1:], 10)
    except IndexError:
        trace("ovr_extrusion: No parameter given")
    except ValueError:
        trace("ovr_extrusion: Invalid parameter given: '%s'" % params[0])

def do_ovr_lockfan(params, stats):
    print "do_ovr_lockfan() called: '%s'" % params
    try:
        stats["fan_locked"] = True if params[0] == 'true' else False
        trace("M106 command %slocked" % ("" if stats["fan_locked"] else "un"))
    except IndexError:
        trace("ovr_lockfan: No parameter given")

##################################################
##################################################

##################################################
################### HOOKS ########################

def do_m0(params, stats, temps):
    raise PrintPausedException

def do_m3(params, stats, temps):
    try:
        stats["rpm"] = float(params[0][1:])
    except (IndexError, ValueError):
        trace("M3: invalid parameter given.")
        return False

def do_m4(params, stats, temps):
    try:
        stats["rpm"] = float(params[0][1:])
    except (IndexError, ValueError):
        trace("M4: invalid parameter given.")
        return False

def do_m5(params, stats, temps):
    stats["rpm"] = 0

def do_m106(params, stats, temps):
    if stats["fan_locked"]:
        return False
    try:
        stats["fan"] = float(params[0][1:])

    except IndexError:
        trace("M106: no parameter given")
        return False
    
    except ValueError:
        trace("M106 %s: invalid parameter, defaulting to S255" % params[0])
        stats["fan"] = 255
        params[0] = "S255"

    return "M106", params, "ok"

def do_m107(params, stats, temps):
    if stats["fan_locked"]:
        return False
    stats["fan"] = 0
    
def do_m109(params, stats, temps):
    try:
        ext_target = float(params[0][1:]) # Get rid of the S
        trace("Extruder temperature set to %.f &deg;C" % ext_target)
    except IndexError:
        trace("M109: no parameter given, defaulting to S0")
        params.append("S0")
        ext_target = 0
    except ValueError:
        trace("M109 %s: invalid parameter, defaulting to S0" % params[0])
        params[0] = "S0"
        ext_target = 0

    ext_curtemp, bed_curtemp = ask_for_temps()
    # this is necessary because the command has not been executed yet, causing
    # the value into the temperatures dict to be out of sync
    temps["extruder_target"] = ext_target
    print "do_m109() Extruder:%f Bed:%f" % (ext_curtemp, bed_curtemp)
    trace("Wait for Extruder temperature to reach %.f &deg;C" % ext_target)
    return "M104", params, "ok"


def do_m190(params, stats, temps):
    try:
        bed_target = float(params[0][1:]) # Get rid of the S
        trace("Bed temperature set to %.f &deg;C" % bed_target)
    except IndexError:
        trace("M190: no parameter given, defaulting to S0")
        params.append("S0")
        bed_target = 0
    except ValueError:
        trace("M190 %s: invalid parameter, defaulting to S0" % params[0])
        params[0] = "S0"
        bed_target = 0

    ext_curtemp, bed_curtemp = ask_for_temps()
    # this is necessary because the command has not been executed yet, causing
    # the value into the temperatures dict to be out of sync
    temps["bed_target"] = bed_target
    print "do_m190() Extruder:%f Bed:%f" % (ext_curtemp, bed_curtemp)
    trace("Wait for Bed temperature to reach %.f &deg;C" % bed_target)
    return "M140", params, "ok"


def do_g0(params, stats, temps):
    if stats["started"] is False:
        print "do_g0() extt %.f bedt %.f" % (temps["extruder_target"], temps["bed_target"])
        if temps["bed_target"] > 0 or temps["extruder_target"] > 0:
            raise PrintWaitTemperatures
        else:
            stats["started"] = True

    for cnt, param in enumerate(params):
        if param.startswith("Z"):
            z_value = float(param[1:])
            new_z_c = z_value + stats["z_override"]
            params[cnt] = "Z%f" % new_z_c
            break

    return "G0", params, "ok"


##################################################
##################################################

### Unused for the moment
def calculate_checksum(string):
    checksum = 0

    for char in map(ord, string):
        checksum ^= char
        checksum &= 0xFF

    return checksum

class GSender(threading.Thread):

    overrides_dict = {
        "!kill": do_ovr_kill,
        "!pause": do_ovr_pause,
        "!resume": do_ovr_resume,
        "!z_plus": do_ovr_zplus,
        "!z_minus": do_ovr_zminus,
        "!temps": do_ovr_gettemps,
        "!lock_fan": do_ovr_lockfan,
        "M3": do_ovr_rpm_cw,
        "M4": do_ovr_rpm_ccw,
        "M104": do_ovr_extt,
        "M106": do_ovr_fan,
        "M140": do_ovr_bedt,
        "M220": do_ovr_speed,
        "M221": do_ovr_extrusion,
    }

    hooks = {
        "M0": do_m0,
        "M3": do_m3,
        "M4": do_m4,
        "M5": do_m5,
        "M109": do_m109,
        "M190": do_m190,
        "M104": do_m109,
        "M140": do_m190,
        "M106": do_m106,
        "M107": do_m107,
# FABlin makes no difference between G0 and G1, so we don't care about this.
        "G0": do_g0,
        "G1": do_g0,
    }


    def __setpaused(self, x):
        self._statistics["paused"] = x

    _paused = property(lambda self: self._statistics["paused"], __setpaused)
    _state = property(lambda self: self._statistics["state"])

    def __init__(self, group=None, target=None, name=None, verbose=None,
                 *args, **kwargs):
        super(GSender, self).__init__(
            group=group, target=target, name=name, verbose=verbose
        )
        self._overrides_queue = kwargs["overrides_queue"]
        self._statistics = kwargs["statistics"]
        self._temperatures = kwargs["temperatures"]
        self._gcode_file = kwargs["gcode_file"]
        self._die = False

    def _manage_overrides(self):
        while not self._overrides_queue.empty():
            override = self._overrides_queue.get(block=False)

            if ":" in override:
                L = override.split(":")
                cmd, params = L[0], L[1:]
            elif " " in override:
                L = override.split(" ")
                cmd, params = L[0], L[1:]
            else:
                cmd, params = override, ""

            try:
                func = self.overrides_dict[cmd]
            except KeyError:
                trace("Invalid override given: '%s'" % override)
            else:
                ret = func(
                    params,
                    self._statistics
                )
                if ret:
                    spool_multiple(ret)

    def _run_hooks(self, line):
        expected = "ok"

        try:
            gcmd, params = line.split(" ", 1)
        except ValueError:
            gcmd, params = line, ""

        try:
            func = self.hooks[gcmd]
        except KeyError:
            pass
        else:
            ret = func(
                params.split(" "),
                self._statistics,
                self._temperatures
            )
            if ret is False:
                return False, ""
            if ret is not None:
                gcmd, params, expected = ret
                line = " ".join([gcmd] + params)

        return line, expected

    def _wait_temperatures(self):
        self._manage_overrides()
        ext, bed = ask_for_temps()

        print "Extruder: %f/%f Bed: %f/%f" % (
            ext, self._temperatures["extruder_target"],
            bed, self._temperatures["bed_target"]
        )

        if (ext >= self._temperatures["extruder_target"] and
            bed >= self._temperatures["bed_target"]):
            return False

        return True

    def run(self):
        statistics = self._statistics
        temperatures = self._temperatures
        exc = None
        __fast_end = True

        # clean up FABlin's buffer
        ser.write("\r\n")
        time.sleep(1)
        while ser.inWaiting():
            ser.readline()
        ##

        statistics["startedat"] = time.time()
        #start_print()

        with open(self._gcode_file) as f:
            iterator = enumerate(f, 1)
            while not self._die:
                try:
                    if self._paused:
                        self._manage_overrides()
                        time.sleep(0.1)
                    else:
                        try:
                            cnt, line = iterator.next()
                        except StopIteration:
                            raise PrintCompleted

                        statistics["line"] = cnt
                        line = line.strip()

                        if not line:
                            continue
                        elif line.startswith(";"):
                            parse_comment(line)
                        else:
                            try:
                                line, comment = line.split(";", 1) # remove inline comment
                                line = line.strip()
                            except ValueError:
                                pass

                            self._manage_overrides()
                            try:
                                line, expected = self._run_hooks(line)
                            except PrintWaitTemperatures:
                                trace("Now reaching temperatures..")
                                statistics["state"] = "waiting_temps"
                                while self._wait_temperatures():
                                    time.sleep(1)
                                statistics["state"] = "printing"
                                self._statistics["started"] = True
                                trace("Temperatures reached!")
                                trace("Now starting print")

                            if line is not False:
                                spool_gline(line, expected)
                            if parse_unattended():
                                raise PrintInterruptedException

                except PrintPausedException:
                    self._paused = True
                except PrintResumedException:
                    self._paused = False
                except PrintCompleted:
                    break
                except PrintKilled:
                    __fast_end = False
                    self.die()
                except PrintInterruptedException:
                    self.die()
                except Exception, e:
                    statistics["state"] = "dumped"
                    exc = e
                    break

        if exc:
            end_print(fast_end=True)
            raise exc

        if self._die:
            statistics["state"] = "stopped"
            end_print(fast_end=__fast_end)
        else:
            # reset trace only if print completed successfully!
            reset_trace()
            statistics["printdone"] = True
            statistics["completedat"] = time.time()
            statistics["state"] = "performed"
            end_print()

    def die(self):
        self._die = True

##################################################
##################################################

def app_init():
    global trace_fname, lock_fname, ser

    def file_len(fname):
        with open(fname) as f:
            i = 0
            for i, l in enumerate(f, 1):
                pass
        return i
    
    config = ConfigParser.ConfigParser()
    config.read('/var/www/lib/config.ini')
    lock_fname = config.get('task', 'lock_file')
    
    ''' LOCK FILE (if exists it means printer is already busy, else create it and take over) '''
    if os.path.isfile(lock_fname):
        print "printer busy"
        raise SystemExit

    serialconfig = ConfigParser.ConfigParser()
    serialconfig.read('/var/www/lib/serial.ini')
    serial_port = serialconfig.get('serial', 'port')
    serial_baud = serialconfig.get('serial', 'baud')
    ser = serial.Serial(serial_port, serial_baud, timeout=10)

    ''' SETTING EXPECTED ARGUMENTS  '''
    parser = argparse.ArgumentParser()
    parser.add_argument("file", help="gcode file to execute")
    parser.add_argument("command_file", help="overrides file")
    parser.add_argument("task_id", help="id of the task")
    parser.add_argument("monitor", help="json informative file",  default=config.get('task', 'monitor_file'), nargs='?')
    parser.add_argument("trace", help="trace file",  default=config.get('task', 'trace_file'), nargs='?')
    parser.add_argument("--ext_temp", help="extruder temperature (for UI feedback only)",  default=180, nargs='?')
    parser.add_argument("--bed_temp", help="bed temperature (for UI feedback only)",  default=50,  nargs='?')

    ''' GET ARGUMENTS '''
    args = parser.parse_args()
    logging.basicConfig(filename=args.trace, level=logging.INFO, format='%(message)s')
    trace_fname = args.trace
    print trace_fname
    
    trace("Loading file...")

    ''' INITIALIZE SOME STATISTICS '''
    statistics["gcode_lines"] = file_len(args.file)
    statistics["engine"] = gcode_utils.who_generate_file(args.file)
    if statistics["engine"] == "CURA":
        try:
            statistics["layers"] = int(cura_utils.get_layers_count(args.file)[0])
        except (IndexError, ValueError):
            pass

    trace("Load complete!")

    statistics["task_id"] = args.task_id

    return args

def main(print_type):
    global statistics

    def kill_threads():
        for thr in threads:
            try:
                thr.die()
            except AttributeError:
                pass


    threads = []
    args = None
    statistics["print_type"] = print_type

    try:
        args = app_init()
        q = Queue.Queue()

        jsonwriter = JSONWriter(overrides_queue=q,
                                statistics=statistics,
                                temperatures=temperatures,
                                monitor_file=args.monitor,
                                every=3, 
                                name="JSONWriterThread")

        observer = Observer()
        observer.schedule(
            OverrideCommandsHandler(overrides_queue=q, patterns=[args.command_file]),
            "/var/www/tasks/",
            #"/root/",
            recursive=True
        )

        senderthread = GSender(
            overrides_queue=q,
            gcode_file=args.file,
            print_type=print_type,
            statistics=statistics,
            temperatures=temperatures,
            name="GSenderThread"
        )

        lock_printer()

        jsonwriter.start()
        observer.start()
        senderthread.start()

        print "all threads on"
        threads.append(jsonwriter)
        threads.append(observer)
        threads.append(senderthread)

        while True:
            alives = []
            for thr in threads:
                alives.append(thr.isAlive())
                thr.join(0.05)
                time.sleep(0.2)
            if not all(alives):
                break

    except KeyboardInterrupt:
        print "Interrupted by Keyboard"
        statistics["state"] = "stopped"
        end_print()
    except Exception:
        statistics["state"] = "dumped"
        raise
    finally:
        try:
            unlock_printer()

            if statistics["started"]:
                jsonwriter.update_json()
                time.sleep(2) # give some time to monitor.py to catch up

            if args:
                finalize(statistics["state"], args.task_id)

        except Exception:
            statistics["state"] = "dumped"
            raise
        finally:
            kill_threads()
            if ser and ser.isOpen():
                ser.close()

if __name__ == "__main__":
    main("print")

