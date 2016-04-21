import serial
import ConfigParser
import os

''' Set the highest baudrate available '''

def testBaud(port, baud_rate):
    ser = serial.Serial(port, baud_rate, timeout=0.5)
    ser.flushInput()
    ser.write("G0\r\n")
    serial_reply=ser.readline().rstrip()
    ser.close()
    return serial_reply != ''

#serial_port = config.get('serial', 'port')
serial_port='/dev/ttyAMA0'

baud_list=[250000, 115200]
accepted_baud=0

for baud in baud_list:
    if(testBaud(serial_port, baud)):
        accepted_baud = baud
        break

if accepted_baud > 0:
    print "Baud Rate available is: " + str(accepted_baud)
    
else:
    accepted_baud=115200


if os.path.exists("/var/www/lib/serial.ini") == False:
    file = open('/var/www/lib/serial.ini', 'w+')
    file.write("[serial]\n")
    file.close()

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/serial.ini')

config.set('serial', 'baud', accepted_baud)
config.set('serial', 'port', serial_port)
with open('/var/www/lib/serial.ini', 'w') as configfile:
    config.write(configfile)