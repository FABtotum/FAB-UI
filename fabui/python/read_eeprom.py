import serial, os
import ConfigParser
import re
import argparse
import json

parser = argparse.ArgumentParser()

parser.add_argument("o", help="output type",  default='json', nargs='?',)
args = parser.parse_args()

output = args.o

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

serialconfig = ConfigParser.ConfigParser()
serialconfig.read('/var/www/lib/serial.ini')


open(config.get('task', 'lock_file'), 'w+').close()
#os.chmod(config.get('task', 'lock_file'), 0777)

def serialize(string_source, regex_to_serach, keys):
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
        
def getServoEndstopValues(string_source):
    match = re.search('Servo\sEndstop\ssettings:\sR:\s([0-9.]+)\sE:\s([0-9.]+)', string_source, re.IGNORECASE)
    if match != None:
        object = {'r': match.group(1), 'e': match.group(2)}
        return object
    
'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = serialconfig.get('serial', 'port')
serial_baud = serialconfig.get('serial', 'baud')

ser = serial.Serial(serial_port, serial_baud, timeout=0.5)

axis = ['x', 'y', 'z', 'e', 'b']

'''  '''
ser.flushInput()
ser.write("M503\r\n")
serial_reply=ser.read(4096)
ser.close()

eeprom = {
    "steps_per_unit": serialize(serial_reply,'(M92\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
    "maximum_feedrates": serialize(serial_reply,'(M203\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
    "maximum_accelaration": serialize(serial_reply,'(M201\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['x', 'y', 'z', 'e']),
    "acceleration": serialize(serial_reply,'(M204\sS[0-9.]+\sT1[0-9.]+)', ['s', 't1']),
    "advanced_variables": serialize(serial_reply,'(M205\sS[0-9.]+\sT0[0-9.]+\sB[0-9.]+\sX[0-9.]+\sZ[0-9.]+\sE[0-9.]+)', ['s', 't', 'b', 'x', 'z', 'e']),
    "home_offset": serialize(serial_reply,'(M206\sX[0-9.]+\sY[0-9.]+\sZ[0-9.]+)', ['x', 'y', 'z']),
    "pid": serialize(serial_reply,'(M301\sP[0-9.]+\sI[0-9.]+\sD[0-9.]+)', ['p', 'i', 'd']),
    "servo_endstop": getServoEndstopValues(serial_reply)
}

if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))

if output == 'json':
    print json.dumps(eeprom)
