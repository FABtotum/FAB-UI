import serial
import time
import sys

# open serial port
port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=2)

serial.write("G28\r\n")
serial.write("G29\r\n")
coms=2

serial.open()
while True and coms!=0:
	line=serial.readline()
	if line!="":
		print(line.rstrip())
		line=""
		coms-=1
	pass

serial.close()
print ("completed!")