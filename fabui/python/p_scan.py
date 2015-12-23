# -*- coding: utf-8 -*-

import os, sys, getopt
import serial
import time
import numpy as np
from subprocess import call
import math
import json

import ConfigParser

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

#PARAMS

#get process pid so the GUI can kill it if needed
myPID = os.getpid()
#flags
completed=0
completed_time=0
percent=0
name="unidentified"
i = 1
probe_num=1
tot_probes=0
slices=1
deg=0
a_offset=0

destination=""
logfile=""

#default scan range params, do not change.
begin=0
end=0

#safety
safe_z=original_safe_z=highest_z=z_hop=0

probes_to_skip=0

#adaptive control
probes_to_skip=0
skips=1

#default analysis area
x=0
y=0
x1=10
y1=10
x_pos=y_pos=0

probe_density=1 						#default probing per mm , default=1 (touches/mm)
points = np.zeros(4) 					#define temporary point vector
points_on_plane	= np.zeros(4)			#points on each plane

X_offset=np.array([float(abs(x1-x)/float(2))+x,0,0,0])	#offset array

#cloud_data=""							#data

#SYS ARG PARAMS
#plane	Aaxis	logs
#x		#a		#l
#y		#b		#d
#i		#e		#v
#j				#t
#n

usage= 'Usage: r_scan.py -x<first point x> -y<first point y> -i<second point x> -j<second point y> -n<density num> -a<axis increments> -b<starting deg> -e<ending deg> -o<safe_z offset> -l<log> -d<destination> -v=verborse -t<trace log> -k<task_id>\n'

try:
	opts, args = getopt.getopt(sys.argv[1:],"x:y:i:j:n:a:b:e:o:l:d:v:t:k:z:p:",["x=","y=","i=","j=","n=","a=","b=","e=","o=","l=","d=","v=","t=","k=", "z=", "p="])
except getopt.GetoptError:
	#Error handling for unknown or incorrect number of options
	print "\n\nERROR!\n Correct usage:\n\n",usage
	sys.exit(2)
for opt, arg in opts:
	if opt =='--help':
		print usage 
		sys.exit()
	elif opt in ("-x", "--x"):
		x = float(arg)
	elif opt in ("-y", "--y"):
		y = float(arg)
	elif opt in ("-i", "--i"):
		x1 = float(arg)
	elif opt in ("-j", "--j"):
		y1 = float(arg)
	elif opt in ("-n", "--n"):
		probe_density = float(arg) #number of probes each unit: can be a float
 	elif opt in ("-a", "--a"):
		deg = int(arg)  #must be int deg
	elif opt in ("-b", "--b"):
		begin = int(arg)
	elif opt in ("-e", "--e"):
		end = int(arg)
	elif opt in ("-o", "--o"):
		safe_z = float(arg) 	# float
		original_safe_z=safe_z
	elif opt in ("-l", "--log"):
		logfile = arg
	elif opt in ("-d", "--d"):
		destination = arg			#dest folder
	elif opt in ("-v", "--v"):
		debug=1						#verbose active?
	elif opt in ("-t", "--t"):
		log_trace = str(arg)		#trace log
	elif opt in ("-k", "--k"):
		task_id = int(arg)
	elif opt in ("-z", "--z"):
		z_hop = float(arg)			#the amount to hop (also known as safe Z)
	elif opt in ("-p", "--p"):
		probe_skip = float(arg)		#adaptive control

started=float(time.time())	#start counting

#compose destination
scan_dir=destination+"images/"  #May come handy in the future for texture storage!
output_file= destination + "cloud_"+str(task_id)+".asc"	 #output file

#SYS ARG PARAMS
#plane	Aaxis	logs
#x		#a		#l
#y		#b		#d
#i		#e		#v
#j				#t
#n

#job stats calc
if (end==0 and begin==0 and deg==0):
	a_axis=False #4th axis is not involved
	slices=1
else:
	a_axis=True #4th axis is involved
	slices= int(abs(end-begin)/deg) #number of 4 axis slices
	
tot_probes=((abs(x1-x)*probe_density)*(abs(y1-y)*probe_density))*slices


def printlog(percent,num):
	
	stats = {'percent': str(percent), 'probe': str(probe_num), 'tot_probes': str(tot_probes), 'slice_number': str(i), 'tot_slices': str(slices)}
	scan  = {'name': name, 'pid': str(myPID), 'started': str(started), 'completed' : str(completed), 'completed_time' : str(completed_time), 'stats' : stats}
	json_log = {'scan': scan}
	#str_log='{"scan":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(started)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","probe":"'+str(probe_num)+'","tot_probes":"'+str(tot_probes)+'","slice_number":'+str(i)+',"tot_slices":'+str(slices)+'}}}'
	handle=open(logfile,'w')
	print>>handle, json.dumps(json_log)
	handle.close()
	#print>>handle, str_log
	return
#track trace

def trace(string):
	out_file = open(log_trace,"a")
	out_file.write(str(string) + "\n")
	print string
	out_file.close()
	return

def rotate_y_axis(point,angle):
	#Rotation matrix definition---------------------------
	rotation_matrix_y = np.zeros(shape=(4,4))
	rotation_matrix_y[0,0]=rotation_matrix_y[2,2]=np.cos(angle*math.pi/180)
	rotation_matrix_y[1,1]=1
	rotation_matrix_y[3,3]=1
	rotation_matrix_y[0,2]=np.sin(angle*math.pi/180)
	rotation_matrix_y[2,0]=-np.sin(angle*math.pi/180)
	#End rotation matrix definition------------------------
	return np.dot(point,rotation_matrix_y)
	
def rotate_x_axis(point,angle):
	#Rotation matrix definition---------------------------
	rotation_matrix_x = np.zeros(shape=(4,4))
	rotation_matrix_x[0,0]=1
	rotation_matrix_x[1,1]=np.cos(angle*math.pi/180)
	rotation_matrix_x[2,2]=np.cos(angle*math.pi/180)
	rotation_matrix_x[3,2]=-np.sin(angle*math.pi/180)
	rotation_matrix_x[3,3]=np.cos(angle*math.pi/180)
	#End rotation matrix definition------------------------
	return np.dot(point,rotation_matrix_x)
	
def convert_cloud(points):
	cloud_file = open(output_file,"w+")
	if len(points)>0:
		for row in range(0,len(points)-1):
			cloud_file.write(str(points[row][0]) + ',' + str(points[row][1]) + ',' + str(points[row][2]) + '\n') 
	cloud_file.close()
	return 	
	
#probe routine
def probe(x,y):
	global points_on_plane
	serial_reply=""

	serial.flushInput()
	serial.write("G30\r\n")
	
	probe_start_time = time.time()
	while not serial_reply[:22]=="echo:endstops hit:  Z:":
		serial_reply=serial.readline().rstrip()	
		#issue G30 Xnn Ynn and waits reply.
		if (time.time() - probe_start_time>90):  
			#timeout management
			trace("Could not probe this point")
			return False
			break	
		pass
		
	#get the z position
	z=float(serial_reply.split("Z:")[1].strip())
	
	new_point = [x,y,z,1]
	points_on_plane = np.vstack([points_on_plane, new_point]) #append new point to the cloud.
	
	trace("Probed "+str(x)+ "," +str(y) + " / " + str(deg) + " degrees = " + str(z))
	return True

printlog(0,0) #create log vuoto

trace("\n ---------- Initializing ---------- \n")

'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.5)

#initialize serial
#serial = serial.Serial(port, baud, timeout=0.6)
time.sleep(0.5) 						#sleep a while
serial.flushInput()						#clean buffer
	
trace('PROBE MODULE STARTING')
print 'scanning from' + str(x)+ "," +str(y)+ " to " +str(x1)+ "," +str(y1); 
print 'Total points    : ', tot_probes	
print 'Probing density : ', probe_density , " points/mm"
print 'Num of planes   : ', slices
print 'Start/End       : ', begin ,' to ', end, 'deg'

#ESTIMATED SCAN TIME ESTIMATION
estimated=(slices*tot_probes*3)/60
if(estimated<1):
    estimated*=60
    unit= "Seconds"
else:
    unit= "Minutes"

print 'Estimated Probe time =', str(estimated) + " " + str(unit) + "  [Pessimistic]"

#movement initialization
if(begin!=0 and a_axis):
	#if an offset is set, rotates to the specified angle.
	trace("rotating platform by" +str(begin)+" degrees")
	serial.write('G0 E' + str(begin) + '\r\n')  #set zero
	time.sleep(begin*0.03)  					#take its time to rotate

#move to first probing point.
serial.write('G0 X' + str(x) + ' Y' +str(y) + ' F8000\r\n')	#go to abs position
time.sleep(1)											#take its time to move

#MAIN PROBING LOOP

while (i <= slices) :
	
	#do each planar scan
	serial.write('G0 Z140\r\n') #center Z (collimation pstn)
	time.sleep(2) 				#take some time to move
	serial.flushInput()		   	#clean buffer
	
	trace("Starting planar scan")
	serial.write('M401\r\n') #lower probe	
	time.sleep(0.5)

	xs=np.arange(x,x1,1/probe_density)
	ys=np.arange(y,y1,1/probe_density)
	
	d_zeta=0
	
	for y_pos in ys:
	
		for x_pos in xs:
			
			#Adaptive controls (min 2 points collected, first is Null)
			#print "----------"
			if len(points_on_plane)>=3:
				
				if probes_to_skip>0:
					probes_to_skip-=1
					#print "SKIPPED "+str(probes_to_skip) + " REMAINING"
					probe_num+=1
					continue
		
			trace("probing point "+str(probe_num) +" of "+str(tot_probes))

			#take its time to move to probe position			
			serial.write('G0 X' + str(x_pos) + ' Y' +str(y_pos) + ' F15000\r\n')	#go to probing pos
			time.sleep(0.5)
			
			#probe point in x_pos,y_pos
			if probe(x_pos,y_pos):
			
				safe_z=original_safe_z+points_on_plane[-1][2]
				#of successful, try adaptive density - Experimental
				if len(points_on_plane)>=3:	
					#density control					
					d_zeta=(points_on_plane[-2][2]-points_on_plane[-1][2])
					if abs(d_zeta)<probe_skip:
						#skip probe if object has been very flat recently.
						if skips<=6:
							skips+=1	#max 6 skips				
					else:		
						if skips>=0:
							skips-=2    #min 0 skip
					
					probes_to_skip=skips
					#print "probes_to_skip: " + str(probes_to_skip)
					
					slope=d_zeta/(1/probe_density)
					
					#print "Slope= " +str(slope) + "last_Z= " +str(points_on_plane[-1][2]) + " SafeZ: " + str(safe_z)
					#print "DENSITY" +str(probe_density)
					
					
				#safe_z adaptive control		
				if abs(d_zeta/(1/probe_density))>1 or points_on_plane[-1][2]>(safe_z/3):
					#if slope>45° or 1/3rd of Safe_z has been reached, trigger adaptive behaviour
					if skips>0:
						step=skips
					else:
						step=1
					#points_on_plane[-1][2] // LAST Z is contained here
					safe_z=original_safe_z+points_on_plane[-1][2] 
					#safe_z=-float(d_zeta*(1/probe_density)*step)
					#print "CHANGING SAFE Z: " + str(safe_z)
			if safe_z <36:
				safe_z=original_safe_z+36 #probe lenght + safe z
				
			if safe_z>highest_z:
				highest_z=safe_z #save for later
				
			#print "SAFE Z:" + str(safe_z)
			serial.write('G0 Z'+str(safe_z)+' F1500\r\n') #move to safe Z ADAPTIVE
			
			serial.flushInput()						#clean buffer
			time.sleep(0.5)
			
			#update counters
			probe_num+=1
			percent=100 * float(probe_num)/float(tot_probes)
			printlog(percent,probe_num) #log the status
			
			serial.write('M401\r\n')	#renew probe position in case it got moved.
	
	
	#points_on_plane=np.delete(points_on_plane, 0, 0)
	#rotate the acquired cloud points.
	if a_axis and i>1:
		a_offset=-float((i-1)*deg)
		trace("Rotating acquired points to " +str(a_offset)+" deg (" + str(i)+" / "+str(slices)+")")
		
		print "original:" 
		print points_on_plane
		
		#move all the points of this plane to origin
		points_on_plane=np.subtract(points_on_plane,X_offset)	

		print "removed x offet:" 
		print points_on_plane
	
		#rotate to desired angle
		points_on_plane=rotate_y_axis(points_on_plane,a_offset)  #this may take a while. note: +=CCW

		print "rotated "+ str(a_offset)+ "degres"
		print points_on_plane
		
		#move all th epoints to rotation axis
		points_on_plane=np.add(points_on_plane,X_offset)

		print "added X offset"
		print points_on_plane
		
		print "offset:"
		print X_offset

	#append points	
	points = np.vstack([points,points_on_plane])
	points_on_plane= np.zeros(4) #reset points for new slice

	if a_axis:
		#Turn the plate
		serial.write('M402\r\n') #raise probe
		time.sleep(2)
		trace("Rotating plane to " + str(i*deg) + " degrees")
		serial.write('G0 E' + str(deg*i) + ' F10000\r\n')
		time.sleep(deg*0.05)  #take some time to rotate	
	
	serial.write('G0 Z'+str(highest_z)+' F1500\r\n') #move to highest Z
	highest_z=original_safe_z #reset highest z point for new scan line.
	serial.write('M402\r\n') #SAFETY RAISE probe
	i+=1 #slices ++		  

#rotate whole model to up	
#if flat scan rotate x axis
#a_offset=-90
#points=rotate_x_axis(points,a_offset)
	
trace("Physical Probing completed")

#END of P_SCAN
serial.write('G0 '+str(safe_z)+'\r\n')				#safety for the probe
time.sleep(3)
serial.write('M402\r\n')				#raise probe
time.sleep(2)
serial.write('M728\r\n')				#completed
time.sleep(1)
serial.flush()
serial.close()							#close serial

#compile cloud data
trace("Saving cloud data...")
convert_cloud(points[1:]) #save the ascii cloud data ignoring first

#save the task as done.
trace("Probing Procedure complete!")
completed=1
completed_time=float(time.time())
percent=100
printlog(percent,i)

#finalize database-side operations
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" scan_p"], shell=True)

#goodbye!
sys.exit()