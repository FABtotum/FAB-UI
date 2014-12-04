import os, sys
import time
import serial
import json
from subprocess import call

#startup script (see crontab)
time.sleep(60) #wait 60 seconds so connections can be made.

#tell the board that the raspi has been connected.

ser = serial.Serial("/dev/ttyAMA0",115200,timeout=1)
ser.flushInput()
ser.flushOutput()

ser.write('M728\r\n') #machine alive

time.sleep(0.5) 

#LOAD USER CONFIG

#read configs
json_f = open("/var/www/fabui/config/config.json")
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
#SAFETY

try:
    safety_door = config['safety']['door']
except KeyError:
    safety_door = 1

ser.write("M732 S"+str(safety_door)+"\r\n")

#ENABLE SAFETY 
call (["sudo python /var/www/recovery/python/safety.py > /var/log/safety.log"], shell=True) #the script will run forever.

#clean the buffer and leave
serial.flush()
serial.close()

#quit
sys.exit()