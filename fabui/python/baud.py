import ConfigParser
import os
from time import sleep
from serial_utils import SerialUtils

""" 
########################################
## Set the highest baudrate available ##
######################################## 
"""

def testBaud(port, baud_rate):
    #print "Test baud: ", baud_rate
    su = SerialUtils(port, baud_rate)
    su.flush()
    su.sendGCode('')
    sleep(0.5)
    
    while(su.inWaiting()):
        su.getReply()
    
    su.sendGCode('G0')
    serial_reply = su.getReply()
    #print "SERIAL REPLY: ", serial_reply
    su.close()
    return serial_reply == 'ok'


serial_port   = '/dev/ttyAMA0'
baud_list     = [250000, 115200]
accepted_baud = 0

for baud in baud_list:
    if testBaud(serial_port, baud):
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
