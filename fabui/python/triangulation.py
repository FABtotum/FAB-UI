import numpy as np
import cv2,cv
import sys, getopt,os
from subprocess import call
import datetime,time
from os import path, access, R_OK
import math
from ws4py.client.threadedclient import WebSocketClient
import ConfigParser
import json

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

'''#### WEB SOCKET CLIENT ####'''
host=config.get('socket', 'host')
port=config.get('socket', 'port')
ws = WebSocketClient('ws://'+host +':'+port+'/')
ws.connect();

#defaults:
scan_dir='/var/www/camera/scan_temp/'  	#default dir
output_file= scan_dir + "cloud.asc"		#default output file
slices=360								#default total slices
start=1                                 #starting number
end=360									#ending number
img_width=1920							#default scan size
img_height=1080							#default scan size

#progress tracking
myPID = os.getpid()						#get process pid so the GUI can kill it if needed
t0 = datetime.datetime.now()            #starting time
completed=0								#completed flag
completed_time=0						#time of completion
percent=0								#progress
debug=0									#debug disabled by default
name="scan_track"						#default scan task name
logfile=name +"log.json"				#default log name
cs=0									#initialize counter
dx=dz=x=y=z=0							#initialize offsets and positions
reconstruct_mode="r"					#default reconstruction mode is rotative,not sweep.
z_offset=0								#default z-offset unless specified
a_offset=0								#default a-offset unless specified
a_deg=0
cloud_data=""							
fail=0

#PARAM MANAGER
usage="python triangulate.py -i[input_folder] -o[output_file] -s[slices] -b[start] -e[end] -w[width of image] -h[height of image] -z[z scan offset] -a[a scan offset] -m[mode r or s] -l[json tracking log file] -t[task ID] -d[debug]"

def printlog(percent,num):		
	
	global ws
	global host
	global port
	
	stats = {'percent': str(percent), 'img_number': str(cs), 'tot_images': str(slices)}
	post_processing = {'name':  name, 'pid' : str(myPID), 'started': str(t0), 'completed': str(completed), 'completed_time': str(completed_time), 'stats': stats}
	str_log = {'post_processing': post_processing}
	#str_log='{"post_processing":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(t0)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","img_number":'+str(cs)+',"tot_images":'+str(slices)+'}}}'
	message = {'type': 'post_processing', 'data': str_log}
	
	try:
		ws.send(json.dumps(message))
	except Exception, e:
		print str(e)
		ws = WebSocketClient('ws://'+host +':'+port+'/')
		ws.connect();
	
	handle=open(logfile,'w')
	print>>handle, json.dumps(str_log)
	handle.close()
	return
	
def rotate_y_axis(point,angle):
	#Rotation matrix definition	
	rotation_matrix_y = np.zeros(shape=(4,4))
	rotation_matrix_y[0,0]=rotation_matrix_y[2,2]=np.cos(angle*math.pi/180)
	rotation_matrix_y[1,1]=1
	rotation_matrix_y[3,3]=1
	rotation_matrix_y[0,2]=np.sin(angle*math.pi/180)
	rotation_matrix_y[2,0]=-np.sin(angle*math.pi/180)

	#rotate the data set
	return np.dot(point,rotation_matrix_y)

def convert_cloud(points):
	global cloud_data
	cloud_file = open(output_file,"a")
	if len(points)>4:
		for row in range(0,len(points)-1):
			cloud_file.write(str(points[row][0]) + ',' + str(points[row][1]) + ',' + str(points[row][2]) + '\n')
	cloud_file.close()
	return 
	
def threshold_image(channel):
	if channel == "hue":
		minimum = hue_min
		maximum = hue_max
	elif channel == "saturation":
		minimum = sat_min
		maximum = sat_max
	elif channel == "value":
		minimum = val_min
		maximum = val_max

	(t, img) = cv2.threshold(channels[channel],minimum,maximum, cv2.THRESH_BINARY | cv2.THRESH_OTSU) 
	channels[channel] = img #result of the op

#LOGGING	
printlog(0,0);	#initialize Json LOG

try:
	opts, args = getopt.getopt(sys.argv[1:],"i:o:s:b:e:w:h:m:z:a:l:t:d:",["input=","output=","slices=","begin=","end=","witdh=","height=","mode=","z=","a=","log=","task_id=","debug="])
except getopt.GetoptError as err:
	#Unknown or incorrect number of options
	print "\n\nERROR!\n Correct usage:\n\n",usage
	print err
	sys.exit(2)
	
for opt, arg in opts:
	if opt =='--help':
		print usage 
		sys.exit()		
	elif opt in ("-i", "--input"):
		scan_dir = arg
	elif opt in ("-o", "--output"):
		output_file = arg
	elif opt in ("-s", "--slices"):
		slices = int(arg)
	elif opt in ("-b", "--begin"):
		start = int(arg)
	elif opt in ("-e", "--end"):
		end = int(arg)
	elif opt in ("-w", "--width"):
		img_width = int(arg)
	elif opt in ("-h", "--height"):
		img_height = int(arg)
	elif opt in ("-m", "--mode"):
		reconstruct_mode = arg
		if reconstruct_mode=="r":
			print "Reconstruct mode: Rotating platform"
		if reconstruct_mode=="s":
			print "Reconstruct mode: Sweeping Laserscanner"
	elif opt in ("-z", "--z-off"):
		z_offset = float(arg)  	#note: this can be a float!
	elif opt in ("-a", "--a-deg"):
		a_offset = float(arg)  	#note: this can be a float!
	elif opt in ("-l", "--log"):
		logfile = arg
	elif opt in ("-t", "--t"):
		task_id = int(arg)
	elif opt in ("-d", "--d"):
		debug = 1
 
#force debug
debug=1

#IMAGE PROCESSING SETTINGS
mmpph=6.0							#mm per pixel height
mmppl=6.0							#mm per pixel lenght 

tresh=40							#noise treshold for difference operation (usually 10-45 works) lowest than tresh gets deleted.	
subrange=15							#analysis subrange.lower values are faster but less reliable, usually 10-15 is fine with 200/255 PWM laser

channels = {'hue': None,'saturation': None,'value': None,'laser': None,}

#RASPICAM GEOMETRIC CONFIG
beta_angle=24  #angle raspicam-horizon 
laser_angle=35 #laser-camera angle :  
fan_angle=53   #fan angle 
half_aperture=np.tan((fan_angle/2)*(math.pi/180))

if debug:
	print "half aperture" + str(half_aperture)

#do not change
#positions=range(img_height)					#height of the images for column analysis
domain=np.arange(subrange*2, dtype=np.uint8)	#subdomain for analysis is initialized as blank uint8 array
cloud_data=""									#inzitialize empty ascii data
points = np.zeros(4, dtype=np.float) 			#define temporary points vector

#hole & texture image setup
hole_image=np.zeros((img_height,slices+1,3), np.uint8)  #create new empty numpy for colored map
texture_image=np.zeros((img_height,slices+1,1), np.uint8)  #create new empty numpy for greyscale

if debug:
	print "Initialization done"

	
while (cs < slices) :
		filename=scan_dir +str(cs) + '.jpg'
		filename_l=scan_dir +str(cs) + '_l.jpg'

		#wait for the file to exist and be writeable + written (also, for real time processing!)
		img_l=None
		img=None
	
		while img is None:
			# Load the image
			img = cv2.imread(filename)
			time.sleep(0.5)
			if debug:
				print "waiting img" , filename
			pass
		
		while img_l is None:
			# Load the laser image
			img_l = cv2.imread(filename_l)
			time.sleep(0.5)
			if debug:
				print "waiting img_l"
			pass
			
		
		if debug:
			print "File       : " + filename + ":"
			#print "F_OK" + str(os.access(filename, os.F_OK))
			#print "R_OK" + str(os.access(filename, os.R_OK))
			#print "W_OK" + str(os.access(filename, os.W_OK))
			#print " "
			print "Laser File : " + filename_l + ":"
			#print "F_OK" + str(os.access(filename_l, os.F_OK))
			#print "R_OK" + str(os.access(filename_l, os.R_OK))
			#print "W_OK" + str(os.access(filename_l, os.W_OK))

		#COMPUTE DIFFERENCE Between laser and no laser images
		or_difference = cv2.absdiff(img_l, img)			
	
		if cs==0:
			#first time only (calculate dynamic threshold)
			tresh_difference = cv2.cvtColor(or_difference, cv.CV_BGR2GRAY)
			maxval=tresh_difference.max()
			#print maxval
			tresh= int(maxval*0.4)		 #use 40% of max value as a treshold
			if debug:
				print "Dynamic Treshold :" , tresh			
			
		
		#remove differences that are smaller that [tresh] (threshold) and are just sensor noise.
		ret,difference = cv2.threshold(or_difference,tresh,255,cv2.THRESH_TOZERO)
				
		#Create enhanced view of the Laser line
		difference = cv2.cvtColor(difference, cv.CV_BGR2GRAY)

		#max values for each column
		ind=difference.argmax(axis=0)
			
		#declare empty position array for post process.
		line_pos=np.zeros(img_height,dtype=np.float)
		
		for col,value in enumerate(ind):
			if(value>0): #if column has a point to process. otherwise skip to next
				
				#resize analysis domain if outside image size values
				if(value-subrange<=0):
					y1=0
				else:
					y1=value-subrange
					
				if(value+subrange>=img_height):
					y2=img_height
				else:
					y2=value+subrange
					
				luminance_col=difference[y1:y2,col:col+1]
				luminance_col=np.swapaxes(luminance_col,1,0)
				
				if(domain.shape==luminance_col[0].shape):
					#Use np.average: average(a, axis=None, weights=None, returned=False):
					w_position=np.average(domain,0,luminance_col[0])    #find index in the search domain with weighted position
					w_position=value+(w_position-subrange)				#correction of the original position
					#if debug and cs==slices-1:
						#print col , "-", w_position
				else:
					fail+=1
					#if debug:
					#	print "Exiting subdomain in col :" + str(col) +" of slice " + str(cs) + " value:" + str(value)
					#	print "Domain:" + str(domain.shape) +" , Luminance col:" +str(luminance_col[0].shape)
					#	print "Domain resized."
					w_position=value		#keep the max luminance found since the subdomain has violated the image borders
					
				#add the position in the empty array
				line_pos[col] = w_position
					
				if debug:
					#print str(x)+ "," + str(y) + "," + str(z) + "\n"
					or_difference[w_position,col,1]=255  #set green pixel in CV debug image  (BGR)
					or_difference[y1,col,0]=255  #set blue pixel in CV debug image  (BGR)
					or_difference[y2,col,0]=255  #set blue pixel in CV debug image  (BGR)
				
			#holes map
			if value==0:	
				#the holemap maps where there is data.
				hole_image[col,cs,2]=255 #place a red pixel (BGR)
		
		#END COL CYCLE: IMAGE ANALYZED.
		
		if debug:
			print "Failed subdomains :" + str(fail)
		fail=0
		
		# post process pixel postions with local STD (to uniform line)
		# all positions are in array line_pos
		# line pos = array [0,0,0,530,532,534,0,0,0,0...0]
		
		# Measure STD once.
		#if cs==0:
		#	global_std = np.std(line_pos)
		#	if debug:
		#		print "Global STD calculated as ", global_std
		
		# Local  STD	
		
		'''for col,value in enumerate(line_pos):
			if value==0:
				continue #skip if empty
			
			subset_range=3
			subset_start=col 				#analyze only ahead. 
			subset_end=col+subset_range
			
			if subset_start<0:
				subset_start=0
			if subset_end>len(line_pos):
				subset_end=len(line_pos)
			
			#extract STD from smaller subset
			local_line_pos=line_pos[subset_start:subset_end]
			local_std = np.std(local_line_pos)
			local_mean = np.mean(local_line_pos)
			
			#corrects pixel position with STD
			if abs(local_mean-value)<local_std:
				#sample is within STD, set it to mean.
				line_pos[col]=local_mean
				
			else:
				#move the position closer to local_mean by local_std
				pass
		'''
		
		# Convert to XYZ points
		tri_side=float(2*np.tan(laser_angle*math.pi/180))
		for col,value in enumerate(line_pos):
			if value==0:
				continue
				
			#------------------RECONSTRUCTION----------------------------
			#-------Calculate XYZ coordinates for cloud points-----------						
			
			if reconstruct_mode=="r":
				#ROTATIVE laser_scan reconstruction
				a_deg=-((float(cs*(end-start))/slices)*math.pi/180)				#+=CCW,degrees/shot in radiants
				app_distance=float(abs((img_width/2)-line_pos[col])/mmppl)		#apparent distance in pixels *mm per pixels
				ro=float(app_distance*tri_side)
				x=float(ro*(np.cos(a_deg)))               						#switching to cartesian
				y=float(ro*(np.sin(a_deg)))						
				z=col/float(mmpph)
				
				if col==100:
					print "a deg", a_deg , " app_dist:", app_distance , " ro :", ro 

			#-----------------------------------
			if reconstruct_mode=="s":
				#SWEEPING laser scan reconstruction
				#area params:
				#			start  : x0 (mm, x position)
				#			end	   : x1 (mm, x position)
				#			slices : linear slices

				x=((end-start)/slices*cs)+int(start)															#distance from the camera
				y=float((230-z_offset)-((float(img_height)/float(2))-col)/float(mmpph))		   					#Y columns
				#z=float(((img_width/2)-w_positionline_pos[col])/(img_width/2))*np.tan(30*math.pi/180)*x		#
				z=x*(np.tan((beta_angle*math.pi/180)-np.arctan(((img_width/2-line_pos[col])/(img_width/2))*half_aperture)))

			#data collected, now add to cloud
			new_point = [x,y,z,1]
			points = np.vstack([points, new_point])
		
		
		#finished this slice, update task log
		percent=(float(cs)/slices)*100				
		printlog(percent,cs) 
		
		if debug:
			if reconstruct_mode=="r":
				print str(cs) + " :" + filename_l + " - Degrees : " + str(a_deg*180/3.14) + " - "+str(percent) + "%"
			if reconstruct_mode=="s":
				print str(cs) + " :" + filename_l + " - Sweep no.  " + str(cs) + "/"+ str(slices) + " - " +str(percent) + "%"
			#computer vision jpg preview is written.	
			cv2.imwrite(scan_dir + str(cs) +'_vision.png',or_difference)
			
		#next slice
		cs+=1
		os.remove(filename)
		os.remove(filename_l)
		#END SLICES CYCLE (end of this slice scan)
		
#Overall point cloud transformations, if needed
if not a_offset==0:
	if debug:
		print "rotating points"
		#trace("Now rotating the points by "+str(a_offset)+ " degrees")
	points=rotate_y_axis(points,a_offset)  #this may take a while. note: +=CCW

#Write cloud data to ASC file
if debug: 
	print "---------------------------------------------"
if points.shape != "4,":
	convert_cloud(points[1:]) #save the ascii cloud data ignoring the first point

#write the hole image
cv2.imwrite(scan_dir + 'holes.png',hole_image)

#write the texture image
cv2.imwrite(scan_dir + 'texture.png',texture_image)

#elapsed time
delta_t = datetime.datetime.now() - t0

#SET JOB AS COMPLETED
completed=1
completed_time=float(time.time())
percent=100
printlog(percent,cs)
	
#finalize database-side operations
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" scan_"+reconstruct_mode], shell=True)

sys.exit()