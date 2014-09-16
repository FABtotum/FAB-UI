import serial
import time
import sys

# open serial port
port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=2)
serial.flush()

serial.write("G28\r\n")
serial.write("G29\r\n")
coms=2

serial.open()
while True and coms>0:
	line=serial.readline()
	if line!="":		
		if line.rstrip()=="ok":
			coms-=1
		else:
			if line[0:3]=="Bed":
				line=line.split(" ")
				print(str(line[2].rstrip())+","+ str(line[4].rstrip())+","+str(line[6].rstrip()))
		line=""	
	pass

serial.close()
#print ("completed!")
sys.exit()
