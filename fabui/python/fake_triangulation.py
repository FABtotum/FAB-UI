
import sys, getopt,os
from subprocess import call
import datetime,time
import math


slices=720								#default total slices
start=1                                 #starting number
end=360									#ending number


completed=0								#completed flag
completed_time=0						#time of completion
percent=0								#progress
debug=0									#debug disabled by default

cs=1									#initialize counter
dx=dz=x=y=z=0							#initialize offsets and positions
reconstruct_mode="r"					#default reconstruction mode is rotative,not sweep.
z_offset=0								#default z-offset unless specified
a_offset=0								#default a-offset unless specified
a_deg=0
cloud_data=""							
fail=0

#force debug
debug=1

print "Initialization done"

	
while (cs <= slices) :

		#ROTATIVE laser_scan reconstruction
		a_deg=-((float(cs*(end-start))/slices)*math.pi/180)				#+=CCW,degrees/shot in radiants

		#finished this slice, update task log
		percent=(float(cs)/slices)*100				
		

		print str(cs) + " - Degrees : " + str(a_deg) + " - "+str(percent) + "%"

			
		#next slice
		cs+=1
		#END SLICES CYCLE (end of this slice scan)
		
		#time.sleep(0.1)
	
print "Done"
sys.exit()