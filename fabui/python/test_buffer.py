#gmacro.py controlled 1by1 gcode operations
import sys
import time, datetime
import serial
import json

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.5)
serial.flushInput()

		
print "data:"

serial.flushInput()
serial.write("M503\r\n")
data=serial.read(1024)
data=float(data.split("Z Probe Length: ")[1].split("\n")[0])
print data
	
