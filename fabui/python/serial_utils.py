#!/usr/bin/python
import ConfigParser
import serial, re, time, logging, sys
import RPi.GPIO as GPIO

class MacroException(Exception):
    def __ini__(self, command):
        self.command = command
        self.message = 'Macro Exception : ' + command
class MacroTimeOutException(Exception):
    def __init__(self, command):
        self.command = command
        self.message = 'Timeout Error : ' + command

class SerialUtils:
    def __init__(self, port=None, baud=None, trace_file=None, debug=False):
        self.debug = debug
        ''' LOAD CONFIG '''
        self.config = ConfigParser.ConfigParser()
        self.config.read('/var/www/lib/config.ini') 
        ''' LOAD SERIAL CONFIG '''
        self.serialconfig = ConfigParser.ConfigParser()
        self.serialconfig.read('/var/www/lib/serial.ini')
        self.port  = port  or self.serialconfig.get('serial', 'port')
        self.baud  = baud  or self.serialconfig.get('serial', 'baud')
        ''' LOAD LOGGING '''
        self.trace_file = trace_file or self.config.get('macro', 'trace_file')
        logging.basicConfig(filename=self.trace_file,level=logging.INFO,format=' %(message)s',datefmt='%d/%m/%Y %H:%M:%S')
        self.logger = logging.getLogger('serial_utils')
        self.resetTrace()
        ''' INIT SERIAL CLASS '''
        self.serial = serial.Serial(self.port, self.baud, timeout=1)
        if self.debug:
            print "serial_util >> serial connect to port '%s'" % self.port
            print "serial_util >> serial connect speed '%s'" % self.baud
            print self.serial.isOpen()
    def sendGCode(self, code):
        self.serial.reset_input_buffer()
        #print code.encode()
        self.serial.write("%s\r\n" % code.encode())
        if self.debug:
            print "serial_util >> sent '%s'" % code.encode()
    def getReply(self, bytes=4096):
        try:
            reply = self.serial.read(bytes).strip()
            if self.debug:
                print "serial_util >> reply '%s'" % reply
            return reply
        except:
            print "Unexpected error:", sys.exc_info()
            return ''
    def close(self):
        self.serial.close()
    def serialize(self,string_source, regex_to_serach, keys):
        match = re.search(regex_to_serach, string_source, re.IGNORECASE)
        if match != None:
            string = match.group(1)
            object = {}
            object.update({'string':string})
            for index in keys:
                match_temp = re.search(index+'([0-9.]+)', string, re.IGNORECASE)
                if match_temp != None:
                    val = match_temp.group(1)
                    object.update({index:val})
            return object
    def getServoEndstopValues(self, string_source):
        match = re.search('Servo\sEndstop\ssettings:\sR:\s([0-9.]+)\sE:\s([0-9.]+)', string_source, re.IGNORECASE)
        if match != None:
            object = {'r': match.group(1), 'e': match.group(2)}
            return object
    def getProbeLength(self, string_source):
        match = re.search('Z\sProbe\sLength:\s([-|+][0-9.]+)', string_source, re.IGNORECASE)
        if match != None:
            value = match.group(1)
            return value
    def getBaudrate(self, string_source):
        match = re.search('Baudrate:\s([0-9.]+)', string_source, re.IGNORECASE)
        if match != None:
            value = match.group(1)
            return value
    def getPosition(self):
        self.sendGCode('M114')
        reply = self.getReply()
        position = None
        match = re.search('X:([-|+0-9.]+)\sY:([-|+0-9.]+)\sZ:([-|+0-9.]+)\sE:([-|+0-9.]+)\sCount\sX:\s([-|+0-9.]+)\sY:([-|+0-9.]+)\sZ:([-|+0-9.]+)', reply, re.IGNORECASE)
        if match != None:
           position = {
            "x" : match.group(1),
            "y" : match.group(2),
            "z" : match.group(3),
            "e" : match.group(4),
            "count": {
                "x" : match.group(5),
                "y" : match.group(6),
                "z" : match.group(7),
            }
           }
        return position
        
    def eeprom(self):
        self.sendGCode('M503')
        reply = self.getReply()
        return {
            "steps_per_unit": self.serialize(reply,'(M92\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
            "maximum_feedrates": self.serialize(reply,'(M203\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
            "maximum_accelaration": self.serialize(reply,'(M201\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
            "acceleration": self.serialize(reply,'(M204\sS[0-9.]+\sT1[0-9.]+)', ['s', 't1']),
            "advanced_variables": self.serialize(reply,'(M205\sS[0-9.]+\sT0[0-9.]+\sB[0-9.]+\sX[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['s', 't', 'b', 'x', 'z', 'e']),
            "home_offset": self.serialize(reply,'(M206\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+)', ['x', 'y', 'z']),
            "pid": self.serialize(reply,'(M301\sP[0-9.]+\sI[0-9.]+\sD[0-9.]+)', ['p', 'i', 'd']),
            "servo_endstop": self.getServoEndstopValues(reply),
            "probe_length" : self.getProbeLength(reply),
            "baudrate": self.getBaudrate(reply)
        }
    
    def fwVersion(self):
        self.sendGCode('M765')
        reply = self.getReply()
        fw_version = None
        match = re.search('V\s((?:(?![\n\s]).)*)', reply)
        if match != None:
            fw_version = match.group(1)
        return fw_version
    def fwBuildDate(self):
        self.sendGCode('M766')
        reply = self.getReply()
        return reply.replace('\n', '').replace('ok', '')
    def fwAuthor(self):
        self.sendGCode('M767')
        reply = self.getReply()
        return reply.replace('\n', '').replace('ok', '')
    def hwVersion(self):
        self.sendGCode('M763')
        reply = self.getReply()
        hw_version = None
        match = re.search('((?:(?![\n\s]).)*)', reply)
        if match != None:
            hw_version = match.group(1)
        return hw_version
    def getTemperature(self):
        self.sendGCode('M105')
        reply = self.getReply()
        temperature = None
        match = re.search('ok\sT:([0-9.]+)\s\/([0-9.]+)\sB:([0-9.]+)\s\/([0-9.]+)', reply, re.IGNORECASE)
        if match != None:
            temperature = {
                'extruder': {'temperature': float(match.group(1)), 'target': float(match.group(2))},
                'bed'     : {'temperature': float(match.group(1)), 'target': float(match.group(2))}
            }
        return temperature
    def reset(self):
        GPIO.setmode(GPIO.BCM)
        GPIO.setwarnings(False)
        pin = 17
        GPIO.setup(pin, GPIO.OUT)
        GPIO.output(pin, GPIO.HIGH)
        time.sleep(0.5)
        GPIO.output(pin, GPIO.LOW)
        time.sleep(0.5)
        GPIO.output(pin, GPIO.HIGH)
        GPIO.cleanup()
        time.sleep(1)
        return True
    def g30(self):
        reply = self.doMacro('G30', 'echo:', -1, None, verbose=False)
        reply = reply.split('\n')
        z = float( reply[-1].split("Z:")[1].strip() )
        z = round(z, 3)  # round to 3 decimanl points
        
        #match = re.search('echo:endstops\shit:\s\sZ:([0-9.]+)', reply, re.IGNORECASE)
        #if match != None:
        #    return match.group(1)
        return z
    def flush(self):
        self.serial.reset_input_buffer()
        self.serial.reset_output_buffer()
    def inWaiting(self):
        return self.serial.in_waiting
    def trace(self, string):
        self.logger.info(string)
        if(self.debug):
            print string
    def resetTrace(self):
        with open(self.trace_file, "w"):
            pass
    def doMacro(self, command, expected_reply, timeout, message, verbose=True, warning=False):
        if(verbose):
            self.trace(message)
        """ wait only if timeout > -1 """
        wait = False if timeout == -1 else True
        start_time = time.time() ###
        finished = False
        timeoutError = False
        self.sendGCode(command)
        if('G0 ' in command):
            time.sleep(0.5)
            """ ### send M400 to synchronize movements and get reply 'ok' ### """
            self.sendGCode('M400');
        while(finished == False):
            reply = self.getReply()
            if(expected_reply in reply):
                finished = True
                return reply
                continue
            if(wait):
                if( time.time() - start_time > timeout):
                    finished = True
                    if(warning == False):
                        raise MacroTimeOutException(message)