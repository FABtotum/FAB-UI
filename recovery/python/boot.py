import os, sys
import time
import serial
import json
import ConfigParser
from subprocess import call

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini') 



#startup script (see crontab)
#print "Boot script"
#time.sleep(60) #wait 60 seconds so connections can be made.
#print "Start"

#tell the board that the raspi has been connected.

#settting serial communication
serail_port = config.get('serial', 'port')
serail_baud = config.get('serial', 'baud')

ser = serial.Serial(serail_port,serail_baud,timeout=1)
ser.flushInput()
ser.flushOutput()

ser.write('M728\r\n') #machine alive

time.sleep(0.5) 

#LOAD USER CONFIG

#read configs
json_f = open(config.get('printer', 'settings_file'))
config = json.load(json_f)

##UNITS
#load custom units
#ser.write("M92 X"+str(config[x])+"\r\n")
#ser.write("M92 Y"+str(config[y])+"\r\n")
#ser.write("M92 Z"+str(config[z])+"\r\n")
#ser.write("M92 E"+str(config[e])+"\r\n")

##COLORS
ser.write("M701 S"+str(config['color']['r'])+"\r\n")
ser.write("M702 S"+str(config['color']['g'])+"\r\n")
ser.write("M703 S"+str(config['color']['b'])+"\r\n")

print "Ambient color setted"

#SAFETY

try:
    safety_door = config['safety']['door']
except KeyError:
    safety_door = 0

ser.write("M732 S"+str(safety_door)+"\r\n")

#print "Safety door setted"

try:
    switch = config['switch']
except KeyError:
    switch = 0

ser.write("M714 S"+str(switch)+"\r\n")

#print "Homing direction setted"
#clean the buffer and leave
serial.flush()
serial.close()

print "Boot completed"
#quit
sys.exit()