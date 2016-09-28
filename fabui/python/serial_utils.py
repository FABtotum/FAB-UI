#!/usr/bin/python
import ConfigParser
import serial, re, time
import RPi.GPIO as GPIO

class SerialUtils:
    def __init__(self):
        ''' LOAD CONFIG '''
        self.config = ConfigParser.ConfigParser()
        self.config.read('/var/www/lib/config.ini')
        ''' LOAD SERIAL CONFIG '''
        self.serialconfig = ConfigParser.ConfigParser()
        self.serialconfig.read('/var/www/lib/serial.ini')
        ''' INIT SERIAL CLASS '''
        self.serial = serial.Serial(self.serialconfig.get('serial', 'port'), self.serialconfig.get('serial', 'baud'), timeout=0.5)
    def sendGCode(self, code):
        self.serial.flushInput()
        #print code.encode()
        self.serial.write("%s\r\n" % code.encode())
    def getReply(self):
        return self.serial.read(4096).strip()
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
            "probe_length" : self.getProbeLength(reply)
        }
    
    def fwVersion(self):
        self.sendGCode('M765')
        reply = self.getReply()
        fw_version = None
        match = re.search('V\s((?:(?![\n\s]).)*)', reply)
        if match != None:
            fw_version = match.group(1)
        return fw_version
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
                'extruder': {'temperature': match.group(1), 'target': match.group(2)},
                'bed'     : {'temperature': match.group(1), 'target': match.group(2)}
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
    def flush(self):
        self.serial.flushInput()
        self.serial.flushOutput()
        self.serial.flush()