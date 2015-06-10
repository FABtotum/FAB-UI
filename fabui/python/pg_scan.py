import os, sys, getopt
import serial
import time
from subprocess import call

import ConfigParser
import logging
import json

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

#PARAMS

#get process pid so the GUI can kill it if needed
myPID = os.getpid()
completed=0
completed_time=0
percent=0
name="unidentified"

slices=360   #default slices num
deg=360      #default degrees (set to scan chunks only)
iso=400      #default ISO setting
width=1920   #default W
height=1080  #default H

usage= 'python om_scan.py -s<slices> -i<ISO> -d<destination> -l<json log> -b<start angle> -e<end angle> -z<z_offset> -w<width> -h<height>\n python om_scan.py -s60 -i400 -d/root/ -llog.log -b0 -e360 -w1920 -h1080'

#default scan range params, do not change.
begin=0
end=360
pos=0 # default Z position (unless begin !=0)
i = 0 #slices counter

#levels
z_pos=0
z_steps=1 #how many levels
z_range=40 #how many mm to move.
j=0   #z level counter
transition_pos=0 #for transitional movement

#PARAM MANAGER

try:
    opts, args = getopt.getopt(sys.argv[1:],"s:i:d:l:w:h:b:e:z:r:",["slices=","ISO=","dest=","log=","width=","height=","begin=","end="])
except getopt.GetoptError as e:
    #Error handling for unknown or incorrect number of options
    print "\n\nERROR!\n Correct usage:\n\n",usage , e
	
    sys.exit(2)
for opt, arg in opts:
   if opt =='--help':
      print usage 
      sys.exit()
   elif opt in ("-s", "--slices"):
      slices = int(arg)
   elif opt in ("-i", "--ISO"):
      iso = int(arg)
   elif opt in ("-w", "--width"):
      width = int(arg)
   elif opt in ("-h", "--height"):
      height = int(arg)
   elif opt in ("-p", "--post"):
      post = int(arg)
   elif opt in ("-b", "--begin"):
      begin = int(arg)
   elif opt in ("-e", "--end"):
      end = int(arg)
   elif opt in ("-d", "--dest"):
      destination = arg
   elif opt in ("-l", "--log"):
      logfile = arg
   elif opt in ("-z", "--z-steps"):
      z_steps = int(arg) #number of levels
   elif opt in ("-r", "--range"):
      z_range = float(arg) #height of each level, can be a float
	  
started=float(time.time())


logfile=config.get('task', 'monitor_file')

#compose destination
scan_dir=destination+"images/"	
	
def printlog(percent,num):		
	str_log='{"scan":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(started)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","img_number":'+str(i)+',"tot_images":'+str(slices)+'}}}'
	handle=open(logfile,'w')
	print>>handle, str_log
	return
	
printlog(0,0) #create log empty
stat_slices=slices*(z_steps+1)

print 'SFM IMAGING MODULE STARTING' 
print 'scanning from'+str(begin)+"to"+str(end); 
print 'Num of pics  : ', stat_slices
print 'ISO setting  : ', iso
print 'Resolution   : ', width ,'*', height, ' px'
print 'levels       : ', z_steps
print 'level height : ', z_range

if (200-(z_steps*z_range))<=0:
	#can't move past y<0
	print "Z/y range of movement is out of bounds"
	sys.exit()  

#ESTIMATED SCAN TIME ESTIMATION
estimated=(slices*5*(z_steps+1))/60
if(estimated<1):
    estimated*=60
    unit= "Seconds"
else:
    unit= "Minutes"

print 'Estimated Scan time =', str(estimated) + " " + str(unit) 

print "\n ---------- Initializing ---------- \n"
serial = serial.Serial('/dev/ttyAMA0', 115200)



# STARTING PARAMS //DO NOT CHANGE
deg = abs((end-begin)/slices)  #degrees to move each slice

#-----

def raspistill(string):
#shell call raspistill
	scanfile=scan_dir + string + str(i) + ".jpg"
	#NEW raspistill -o test4.png -rot 90 -hf -vf -w 1944 -h 2592
	#-cfx 128:128
	
	call (["raspistill -hf -vf -awb sun -ISO " + str(iso) + " -w "+ str(height) +" -h "+ str(width) +" -o " + scanfile + " -t 1"], shell=True)

	while (not(os.access(scanfile, os.F_OK)) or not(os.access(scanfile, os.W_OK))):
		#wait until the file has been written
		time.sleep(0.1)
		pass
	return

if(begin!=0):
	#if an offset is set, rotates to the specified A angle.
	serial.write('G0 E' + str(begin) + '\r\n')  #set zero
	pos=begin #set start position as defined offset.
	time.sleep(9)
	#serial.write('G4 S5\r\n')  #take its time to move

while (j <z_steps):
	#move to next level
	print "starting level ", j 
	
	#transition level image
	if (j>0 and j<z_steps):
		print "transitioning to level "+str(j)
		#next level 
		i=0 #reset count
		#deg=-deg #reverse direction
		#pos+=deg #update position
		z_pos+=z_range
		
		transition_pos+=(z_range/2)
		#transitional picture
		serial.write('G0 Y-' + str(transition_pos) + 'F5000\r\n')
		
		id_string=str(j-1)+"_t_"
		raspistill(id_string) 	  #snap a pic for current slice
		
		serial.write('G0 Y-' + str(z_pos) + 'F5000\r\n')
		time.sleep(4)  #take its time to move
	
	while (i < slices):
		#Turn the plate
		print str(i) + "/" + str(slices) +" (" + str(deg*i) + "/360)"
		serial.write('G0 E' + str(pos) + 'F5000\r\n')
		time.sleep(abs(deg)*0.1)  #take its time to rotate
		
		id_string=str(j)+"_"
		raspistill(id_string) 	  #snap a pic for current slice
		
		percent=100 * float(i)/float(slices)
		printlog(percent,i)
		pos+=deg #increase current pos + deg.
		i+=1
		#end of level
		
	j+=1 #level +1


	
#END of SCAN

serial.flush()
serial.close()	#close serial

print "Scan Completed."

completed=1
completed_time=float(time.time())
percent=100
printlog(percent,i)


call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" scan_pg"], shell=True)

sys.exit()  