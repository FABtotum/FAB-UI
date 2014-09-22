#!/usr/bin/python

gcode="G0 X1"	

def checksum(gcode,line):
	cs = 0
	gcode="N"+str(line)+" " + gcode
	for char in gcode:
		#print char
		cs=cs ^ ord(char)
	cs = cs & 0xff # Defensive programming...
	return cs
	
print checksum(gcode,1)
print checksum(gcode,2)
print checksum(gcode,3)


