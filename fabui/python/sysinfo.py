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

'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = serialconfig.get('serial', 'port')
serial_baud = serialconfig.get('serial', 'baud')

ser = serial.Serial(serial_port, serial_baud, timeout=0.5)

''' ### FW VERSION '''
ser.flushInput()
ser.write("M765\r\n")
serial_reply=ser.readline().rstrip()
fw_version = 'available'
match = re.search('V\s((?:(?![\n\s]).)*)', serial_reply)
if match != None:
    fw_version = match.group(1)

''' ### HW VERSION '''
ser.flushInput()
ser.write("M763\r\n")
serial_reply=ser.readline().rstrip()
hw_version = 'available'
match = re.search('((?:(?![\n\s]).)*)', serial_reply)
if match != None:
    hw_version = match.group(1)


if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))

if output == 'json':
    sysinfo = {'fw': fw_version, 'hw': hw_version}
    print json.dumps(sysinfo)
