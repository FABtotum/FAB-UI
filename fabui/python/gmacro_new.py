#!/usr/bin/env python
import os, time
import argparse, logging
import json
import ConfigParser
from serial_utils import SerialUtils

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

parser = argparse.ArgumentParser()

parser.add_argument("-m", "--macro",    help="macro to execute",  type=str, required=True)
parser.add_argument("-t", "--trace",    help="log travce file",        default=config.get('macro', 'trace_file'))
parser.add_argument("-r", "--response", help="log response file",      default=config.get('macro', 'response_file'))
parser.add_argument("-p1", "--param1",  help="First extra parameter",  default=None)
parser.add_argument("-p2", "--param2",  help="Second extra parameter", default=None)
args = parser.parse_args()

""" ### init  ### """
macro_name    = args.macro
trace_file    = args.trace
response_file = args.response
param1        = args.param1
param2        = args.param2

### reset files
with open(trace_file, "w"):
    pass
with open(response_file, "w"):
    pass

logging.basicConfig(filename=trace_file,level=logging.INFO,format=' %(message)s',datefmt='%d/%m/%Y %H:%M:%S')

settings_file = open(config.get('printer', 'settings_file'))
settings = json.load(settings_file)

if 'settings_type' in settings and settings['settings_type'] == 'custom':
    settings_file = open(config.get('printer', 'custom_settings_file'))
    settings = json.load(settings_file)
settings_file.close()

#write LOCK FILE    
open(config.get('task', 'lock_file'), 'w').close()

""" trace file """
def trace(string):
    global logging
    logging.info(string)
    print string

def response(string):
    global response_file
    f = open(response_file,'w')
    f.write(string) # python will convert \n to os.linesep
    f.close()

def handleExceptionEnd(message):
    trace(message)
    trace('Macro Failed')
    response('error')
""" ### custom exceptions ### """
class MacroException(Exception):
    pass
class MacroTimeOutException(Exception):
    def __init__(self, command):
        self.command = command
        self.message = 'Timeout Error : ' + command
        
""" ### macro function ### """
def macro(serial_util, command, expected_reply, timeout, message, verbose=True, warning=False):
    if(verbose):
        trace(message)
    #print "COMMAND: ", command
    #print "EXPECETD REPLY: ", expected_reply
    #print "TIMEOUT: ", timeout
    """ wait only if timeout > -1 """
    wait = False if timeout == -1 else True
    start_time = time.time() ###
    finished = False
    timeoutError = False
    serial_util.sendGCode(command)
    if('G0 ' in command):
        #print serial_util.getReply()
        time.sleep(0.5)
        """ ### send M400 to synchronize movements and get reply 'ok' ### """
        serial_util.sendGCode('M400');
        #print 'SEND M400'
    while(finished == False):
        reply = serial_util.getReply()
        if(expected_reply in reply):
            finished = True
            continue
        if(wait):
            if( time.time() - start_time > timeout):
                finished = True
                if(warning == False):
                    raise MacroTimeOutException(message)
        #print reply
""" ################################################################### """
def loadSpool(serial_util, settings, params=None):
    macro(serial_util, 'M104 S190', 'ok', -1, 'Heating nozzle...')
    macro(serial_util, 'G90', 'ok', 1, 'Set absolute position')
    macro(serial_util, 'G27', 'ok', -1, 'Zeroing Z axis')
    macro(serial_util, 'G0 X130 Y150 Z100 F10000', 'ok', -1, 'Rising bed and moving head')
    macro(serial_util, 'M302', 'ok', 1,   'Extrusion prevention disabled')
    macro(serial_util, 'G91', 'ok', 1, 'Set relative position')
    macro(serial_util, 'G92 E0', 'ok', 1, 'Reset extuder position')
    macro(serial_util, 'M92 E' + str(settings['e']), 'ok', 1, 'Setting extuder mode')
    macro(serial_util, 'M300', 'ok', 1, 'Start pushing')
    macro(serial_util, 'G0 E110 F500', 'ok', -1, 'Loading filament (slow)')
    macro(serial_util, 'G0 E660 F700', 'ok', -1, 'Loading filament (fast)')
    temperature = serial_util.getTemperature()
    if(temperature['extruder']['temperature'] < 190 ):
        macro(serial_util, 'M109 S190', 'ok', -1, 'Heating nozzle...(wait)')
    macro(serial_util, 'G0 E200 F200', 'ok', -1, 'Entering the hotend (slow)')
    macro(serial_util, 'M104 S0', 'ok', 1, 'Disabling extruder')
    macro(serial_util, 'M302 S170', 'ok', 1, 'Extrusion prevention enabled')
""" ################################################################### """
def unloadSpool(serial_util, settings, params=None):
    macro(serial_util, 'M104 S190', 'ok', -1, 'Heating nozzle...')
    macro(serial_util, 'G90',  'ok', 1,   'Set absoulte position' )
    macro(serial_util, 'M302', 'ok', 1,   'Extrusion prevention disabled')
    macro(serial_util, 'G27',  'ok', -1, 'Zeroing Z axis')
    macro(serial_util, 'G0 Z100 F1000', 'ok', -1, 'Rising bed')
    macro(serial_util, 'G91', 'ok', 1, 'Set relative position')
    macro(serial_util, 'G92 E0', 'ok', 1, 'Reset extuder position')
    macro(serial_util, 'M92 E' + str(settings['e']), 'ok', 1, 'Setting extuder mode')
    macro(serial_util, 'M300', 'ok', 1, 'Start Pulling')
    macro(serial_util, 'G0 E-800 F550', 'ok', -1, 'Expelling filament')
    macro(serial_util, 'G0 E-200 F550', 'ok', -1, 'Expelling filament', verbose=False)
    macro(serial_util, 'M104 S0', 'ok', 1, 'Disabling extruder')
    macro(serial_util, 'M302 S170', 'ok', 1, 'Extrusion prevention enabled')
""" ################################################################### """       
def preUnloadSpool(serial_util, settings, params=None):
    """ pre heat nozzle  """
    temperature = serial_util.getTemperature()
    if(temperature['extruder']['temperature'] < 160 ):
        macro(serial_util, 'M109 S160', 'ok', -1, 'Heating nozzle... reaching temperature 160&deg;C (please wait)') ### set target and wait to reach it
    macro(serial_util, 'M104 S190', 'ok', -1, 'Heating nozzle...', verbose=False) ### set target
""" ################################################################### """
def checkPrePrint(serial_util, settings, params=None):
    """ preparing printer to print """
    trace("Checking safety measures")
    if(settings['safety']['door'] == 1):
        macro(serial_util, 'M741', 'TRIGGERED', 1, 'Front panel door control')
    macro(serial_util, 'M744', 'TRIGGERED', 1, 'Building plane inserted correctly')
    macro(serial_util, 'M744', 'TRIGGERED', 1, 'Spool panel control')
""" ################################################################### """
def endPrintAdditive(serial_util, settings, params=None):
    macro(serial_util, 'G90', 'ok', 1, 'Set Absolute movement')
    macro(serial_util, 'G27 Z0', 'ok', -1, 'Lowering the plane')
    macro(serial_util, 'M104 S0', 'ok', 1, 'Shutting down extruder')
    macro(serial_util, 'M140 S0', 'ok', 1, 'Shutting down heated Bed')
    macro(serial_util, 'M220 S100', 'ok', 1, 'Reset Speed factor override')
    macro(serial_util, 'M221 S100', 'ok', 1, 'Reset Extruder factor override')
    macro(serial_util, 'M107 S100', 'ok', 1, 'Turning Fan off')
    macro(serial_util, 'M18', 'ok', 1, 'Motor off')
    macro(serial_util, 'M300', 'ok', 1, 'Done!')
""" ################################################################### """
def checkPreScan(serial_util, settings, params=None):
    trace("Preparing the FABtotum to scan")
    if(settings['safety']['door'] == 1):
        macro(serial_util, 'M741', 'TRIGGERED', 1, 'Front panel door control')
    macro(serial_util, 'M744', 'TRIGGERED', 1, 'Spool panel control', verbose=False, warning=True)
    macro(serial_util, 'G90', 'ok', 1, 'Set Absolute movement', verbose=False)
    macro(serial_util, 'G27', 'ok', -1, 'Zeroing axis')
    macro(serial_util, 'G28 X0 Y0', 'ok', -1, 'Zeroing axis', verbose=False)
    macro(serial_util, 'G91', 'ok', 1, 'Setting relative position', verbose=False)
    macro(serial_util, 'G0 X5 Y5 Z-' + str(settings['feeder']['disengage-offset']) + ' F400', 'ok', -1, 'Engaging 4th Axis Motion')
    macro(serial_util, 'G90', 'ok', 1, 'Set Absolute movement', verbose=False)
    macro(serial_util, 'M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode')
    macro(serial_util, 'G0 Z135 F1000', 'ok', -1, 'Moving to pre-scan position')    
""" ################################################################### """
def endScan(serial_util, settings, params=None):
    trace("Terminating digitalization procedure")
    macro(serial_util, 'M402', 'ok', -1, 'Retracting Probe')
    macro(serial_util, 'M700 S0', 'ok', 1, 'Shutting down laser')
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    macro(serial_util, 'G0 Z140 E0 F5000', 'ok', -1, 'Rasing Probe', verbose=False)
    macro(serial_util, 'M92 E' + str(settings['e']), 'ok', 1, 'Setting extruder mode', verbose=False)
    macro(serial_util, 'M701 S' + str(settings['color']['r']), 'ok', 1, 'Turning on lights')
    macro(serial_util, 'M702 S' + str(settings['color']['g']), 'ok', 1, 'Turning on lights', verbose=False)
    macro(serial_util, 'M703 S' + str(settings['color']['b']), 'ok', 1, 'Turning on lights', verbose=False)
    macro(serial_util, 'M402', 'ok', -1, 'Retracting Probe', verbose=False)
    macro(serial_util, 'M300', 'ok', 1, 'Scan completed', verbose=False)
""" ################################################################### """
def sweepScan(serial_util, settings, params=None):
    trace("Initializing Sweeping Laserscanner")
    trace("checking panel door status and bed inserted")
    if(settings['safety']['door'] == 1):
        macro(serial_util, 'M741', 'TRIGGERED', 1, 'Front panel door control')
    macro(serial_util, 'M744', 'open', 1, 'Building plane removed!', warning=True)
    macro(serial_util, 'M744', 'TRIGGERED', 1, 'Spool panel is not closed!')
    macro(serial_util, 'M701 S0', 'ok', 1, 'Turning off lights')
    macro(serial_util, 'M702 S0', 'ok', 1, 'Turning off lights', verbose=False)
    macro(serial_util, 'M703 S0', 'ok', 1, 'Turning off lights', verbose=False)
    macro(serial_util, 'G28 X0 Y0', 'ok', -1, 'Homing all axis')
    macro(serial_util, 'G90', 'ok', 1, 'Setting Absolute position')
    macro(serial_util, 'G0 Z145 F1000', 'ok', -1, 'Lowering the plane')
""" ################################################################### """
def probingScan(serial_util, settings, params=None):
    trace("Initializing Probing procedure")
    if(settings['safety']['door'] == 1):
        macro(serial_util, 'M741', 'TRIGGERED', 1, 'Front panel door control')
    macro(serial_util, 'M402', 'ok', -1, 'Retracting Probe')
    macro(serial_util, 'M744', 'open', 1, 'Building plane is absent!', warning=True)
    macro(serial_util, 'G90', 'ok', 1, 'Setting Absolute position', verbose=False)
    macro(serial_util, 'M302 S0', 'ok', 1, 'Disabling cold extrusion prevention', verbose=False)
    macro(serial_util, 'M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode', verbose=False)
""" ################################################################### """
def rotatingScan(serial_util, settings, params=None):
    trace("Initializing Rotative Laser scanner")
    trace("Checking panel door status and bed inserted")
    if(settings['safety']['door'] == 1):
        macro(serial_util, 'M741', 'TRIGGERED', 1, 'Front panel door control')
    macro(serial_util, 'M744', 'open', 1, 'Building plane (must be removed)')
    macro(serial_util, 'M744', 'TRIGGERED', 1, 'Spool panel closed', verbose=False, warning=True)
    macro(serial_util, 'M701 S0', 'ok', 1, 'Turning off lights')
    macro(serial_util, 'M702 S0', 'ok', 1, 'Turning off lights', verbose=False)
    macro(serial_util, 'M703 S0', 'ok', 1, 'Turning off lights', verbose=False)
    macro(serial_util, 'G90', 'ok', -1, 'Setting Absolute position', verbose=False)
    macro(serial_util, 'G0 X96 Y175 Z135 E0 F10000', 'ok', -1, 'Moving to collimation position', verbose=False)
    macro(serial_util, 'M302 S0', 'ok', 1, 'Disabling cold extrusion prevention', verbose=False)
""" ################################################################### """
def photogrammetryScan(serial_util, settings, params=None):
    print
""" ################################################################### """
def raiseBed(serial_util, settings, params=None):
    macro(serial_util, 'M402', 'ok', -1, 'Retracting Probe')
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    if(settings['zprobe']['disbale'] == 1):
        macro(serial_util, 'G27 X0 Y0 Z' + str(settings['zprobe']['zmax']), 'ok', -1, 'Homing all axes')
        macro(serial_util, 'G0 Z50 F10000', 'ok', -1, 'Rising')
    else:
        macro(serial_util, 'G27', 'ok', -1, 'Homing all axes')
        macro(serial_util, 'G0 Z10 F10000', 'ok', -1, 'Raising')
        macro(serial_util, 'G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def raiseBedNo27(serial_util, settings, params=None):
    macro(serial_util, 'M402', 'ok', -1, 'Retracting Probe')
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    if(settings['zprobe']['disbale'] == 1):
        macro(serial_util, 'G27 X0 Y0 Z' + str(settings['zprobe']['zmax']), 'ok', -1, 'Homing all axes')
        macro(serial_util, 'G0 Z50 F10000', 'ok', -1, 'Rising')
    else:
        macro(serial_util, 'G0 Z20 F10000', 'ok', -1, 'Raising bed', verbose=False)
        macro(serial_util, 'G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def fourthAxisMode(serial_util, settings, params=None):
    macro(serial_util, 'M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode', verbose=False)
""" ################################################################### """
def homeAll(serial_util, settings, params=None):
    trace("Now homing all axes")
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    if(settings['zprobe']['disbale'] == 1):
        macro(serial_util, 'G27 X0 Y0 Z' + str(settings['zprobe']['zmax']), 'ok', -1, 'Homing all axes', verbose=False)
        macro(serial_util, 'G0 Z50 F10000', 'ok', -1, 'Rising', verbose=False)
    else:
        macro(serial_util, 'G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def autoBedLeveling(serial_util, settings, params=None):
    trace("Auto Bed leveling Initialized")
    macro(serial_util, 'G91', 'ok', 1, 'Setting relative position', verbose=False)
    macro(serial_util, 'G0 Z25 F1000', 'ok', -1, 'Moving away from the plane', verbose=False)
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    macro(serial_util, 'G28', 'ok', -1, 'Homing all axes')
    macro(serial_util, 'G29', 'ok', -1, 'Auto bed leveling procedure')
    macro(serial_util, 'G0 X5 Y5 Z60 F2000', 'ok', -1, 'Getting to idle position')
""" ################################################################### """
def startPrint(serial_util, settings, params=None):
    trace("Preparing the FABtotum Personal Fabricator")
    macro(serial_util, 'G90', 'ok', 1, 'Setting absolute position', verbose=False)
    macro(serial_util, 'G0 X5 Y5 Z60 F1500', 'ok', -1, 'Moving to oozing point')
    trace('Pre heating nozzle (' + + '&deg;) (fast)')
    serial_util.sendGCode('M104 S' + str(params['param1']))
    trace('Pre heating bed (' + + '&deg;) (fast)')
    serial_util.sendGCode('M140 S' + str(params['param2']))
    macro(serial_util, 'M220 S100', 'ok', -1, 'Reset Speed factor override', verbose=False)
    macro(serial_util, 'M221 S100', 'ok', -1, 'Reset Extruder factor override', verbose=False)
    macro(serial_util, 'M92 E' + str(settings['e']), 'ok', 1, 'Setting extruder mode', verbose=False)
    
MACROS_CMDS = {
 'load_spool'         : loadSpool,
 'unload_spool'       : unloadSpool,
 'pre_unload_spool'   : preUnloadSpool,
 'check_pre_print'    : checkPrePrint,
 'end_print_additive' : endPrintAdditive,
 's_scan'             : sweepScan,
 'p_scan'             : probingScan,
 'r_scan'             : rotatingScan,
 'pg_scan'            : photogrammetryScan,
 'end_scan'           : endScan,
 'check_pre_scan'     : checkPreScan,
 'raise_bed'          : raiseBed,
 'raise_bed_no_g27'   : raiseBedNo27,
 '4th_axis_mode'      : fourthAxisMode,
 'home_all'           : homeAll,
 'auto_bed_leveling'  : autoBedLeveling,
 'start_print'        : startPrint
}

su = SerialUtils()

if macro_name in MACROS_CMDS:
    try:
        params = {
            'param1' : param1,
            'param2' : param2
        }
        MACROS_CMDS[macro_name](su, settings, params)
        response('true')
    except MacroException as e:
        handleExceptionEnd(e)
    except MacroTimeOutException as e:
        handleExceptionEnd(e.message)
else:
    #print "Macro not found"
    response('false')

if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))
