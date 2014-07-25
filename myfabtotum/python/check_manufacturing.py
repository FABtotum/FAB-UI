#check manufacturing
import os, sys
import itertools

#check_manufacturing.py

gcode=str(sys.argv[1])  #gcode to check
sub=False

#print "gcode: ", gcode
f = open(gcode, 'r')
lines_to_read = 100 

for line in itertools.islice(f, lines_to_read):

	#line = line.replace("\r","")
	#line= line.replace("\n","")
	#DEGUB print line
	
	if line[:2]=="M3" or line[:2]=="M4" or line[:3]=="M03":
		sub=True
		break
	pass 

if sub:
	print "SUBTRACTIVE"
else:
	print "ADDITIVE"
	
sys.exit()