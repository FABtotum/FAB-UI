import os, sys, getopt
import serial
import time
from subprocess import call


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
post=1      # DISABLED  mode 1=default:laser switch 2=greyscale only 3:laser only.

usage= 'r_scan.py -s<slices> -i<ISO> -d<destination> -l<json log> -b<start angle> -e<end angle> -z<z_offset> -w<width> -h<height>\n'

#default scan range params, do not change.
begin=0
end=360
z_offset=0 #z offset.(actually Y offset position in the A axis reference)
pos=0 # default Z position (unless begin !=0)
i = 0

#PARAM MANAGER

try:
    opts, args = getopt.getopt(sys.argv[1:],"n:s:i:d:l:w:h:pb:e:z",["slices=","ISO=","dest=","log=","width=","height=","post=","begin=","end="])
except getopt.GetoptError:
    #Error handling for unknown or incorrect number of options
    print "\n\nERROR!\n Correct usage:\n\n",usage
    sys.exit(2)
for opt, arg in opts:
   if opt =='--help':
      print usage 
      sys.exit()
   elif opt in ("-n", "--name"):
      name = arg
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
   elif opt in ("-z", "--z-offset"):
      z_offset = arg #can be a float
   elif opt in ("-d", "--dest"):
      destination = arg
   elif opt in ("-l", "--log"):
      logfile = arg
	  
started=float(time.time())

#compose destination
scan_dir=destination+"images/"	
	
	
def printlog(percent,num):		
	str_log='{"scan":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(started)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","img_number":'+str(i)+',"tot_images":'+str(slices)+'}}}'
	handle=open(destination+logfile,'w')
	print>>handle, str_log
	return
	
printlog(0,0) #create log vuoto
	
print 'SCAN MODULE STARTING' 
print 'scanning from'+str(begin)+"to"+str(end); 
print 'Num of scans : ', slices
print 'ISO  setting : ', iso
print 'Resolution   : ', width ,'*', height, ' px'
print 'Postproces.  : ', post
print 'z offset     : ', z_offset

#ESTIMATED SCAN TIME ESTIMATION
estimated=(slices*1.99)/60
if(estimated<1):
    estimated*=60
    unit= "Seconds"
else:
    unit= "Minutes"

print 'Estimated Scan time =', str(estimated) + " " + str(unit) + "  [Pessimistic]"

#Please note: laser PWM is controlled by M700 SXXX

print "\n ---------- Initializing ---------- \n"
serial = serial.Serial('/dev/ttyAMA0', 115200)

if(begin!=0):
	#if an offset is set, rotates to the specified A angle.
	serial.write('G0 E' + str(begin) + '\r\n')  #set zero
	pos=begin #set start position as defined offset.
	
if(z_offset!=0):
	#if an offset for Z (Y in the rotated reference space) is set, moves to it.
	serial.write('G0 Y' + str(z_offset) + '\r\n')  #go to y offset
	
time.sleep(10)  #take its time to move

# STARTING PARAMS //DO NOT CHANGE
deg = abs((end-begin)/slices)  #degrees to move each slice


#-----

def raspistill(laser_string):
#shell call raspistill
	scanfile=scan_dir + str(i) + laser_string + ".png"
	#NEW raspistill -o test4.png -rot 90 -hf -vf -w 1944 -h 2592
	#-cfx 128:128
	
	call (["raspistill -hf -vf -rot 90 --exposure off -awb sun -ISO " + str(iso) + " -w "+ str(height) +" -h "+ str(width) +" -o " + scanfile + " -t 0"], shell=True)

	#OLD call (["raspistill -vf -hf --exposure off -awb sun -ISO " + str(iso) + " -w "+ str(width) +" -h "+ str(height) +" -o " + scanfile + " -t 0"], shell=True)

	while (not(os.access(scanfile, os.F_OK)) or not(os.access(scanfile, os.W_OK))):
		#wait until the file has been written
		time.sleep(0.1)
		pass
	return

while (i <= slices) :
	#Turn the plate
	print str(i) + "/" + str(slices) +" (" + str(deg*i) + "/360)"
	serial.write('G0 E' + str(pos) + 'F2500\r\n')
	time.sleep(deg*0.1)  #take its time to rotate
	
	serial.write('M700 S180\r\n') #turn laser ON
	raspistill("_l") 	  #snap a pic
	
	serial.write('M700 S0\r\n')# Turn laser off
	raspistill("") 		  #snap a pic without laser
	
	percent=100 * float(i)/float(slices)
	printlog(percent,i)
	pos+=deg #increase current pos + deg.
	i+=1
	 
#END of R_SCAN

serial.flush()
serial.close()				#close serial

print "Scan Completed."

completed=1
completed_time=float(time.time())
percent=100
printlog(percent,i)

sys.exit()  