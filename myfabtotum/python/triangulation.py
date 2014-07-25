import numpy as np
import cv2,cv
#import pprint
import sys, getopt,os
import datetime,time
from os import path, access, R_OK
import math

#verbose off: print "initialization done"

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
cs=0										#initialize counter
dx=dz=x=y=z=0							#initialize offsets and positions
reconstruct_mode="r"					#default reconstruction mode is rotative,not sweep.
z_offset=0								#default z-offset unless specified
a_offset=0								#default a-offset unless specified
cloud_data=""							
fail=0


#PARAM MANAGER
usage="python triangulate.py -i[input_folder] -o[output_file] -s[slices] -b[start] -e[end] -w[width of image] -h[height of image] -z[z scan offset] -a[a scan offset] -m[mode r or s] -l[json tracking log file] -d[debug]"

def printlog(percent,num):		
	str_log='{"post_processing":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(t0)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","img_number":'+str(cs)+',"tot_images":'+str(slices)+'}}}'
	handle=open(logfile,'w')
	print>>handle, str_log
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

def convert_cloud(points):
	global cloud_data
	cloud_file = open(output_file,"a")
	if len(points)>0:
		for row in range(0,len(points)-1):
			#point_str=str(points[row][0]), "," , str(points[row][1]) , "," , str(points[row][2])
			cloud_file.write(str(points[row][0]) + ',' + str(points[row][1]) + ',' + str(points[row][2]) + '\n')
            #cloud_file.write(str(point_str) + '\n') 
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
	opts, args = getopt.getopt(sys.argv[1:],"i:o:s:b:e:w:h:m:z:a:l:d:",["input=","output=","slices=","begin=","end=","witdh=","height=","mode=","z=","a=","log=","debug="])
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
	elif opt in ("-d", "--d"):
		debug = 1
 
#IMAGE PROCESSING SETTINGS
mmpp=10									#mm per pixel
tresh=33								#noise treshold for difference operation (usually 10-35 works)		
subrange=30								#analysis subrange.lower values are faster but less reliable

#Params for exectuing HSV thresholding
hue_min=228
hue_max=255

sat_min=30
sat_max=100

val_min=30
val_max=100

channels = {'hue': None,'saturation': None,'value': None,'laser': None,}

#RASPICAM GEOMETRIC CONFIG
beta_angle=24 #angolo raspi-horizon 
laser_angle=35 #laser-camera angle :  
fan_angle=53 # Fan angle 
half_aperture=np.tan((fan_angle/2)*(math.pi/180))

print "half aperture" + str(half_aperture)

#do not change
#positions=range(img_height)				#height of the images for column analysis
domain=np.arange(subrange*2, dtype=np.uint8)	#subdomain for analysis is initialized as blank uint8 array
cloud_data=""							#inzitialize empty ascii data
points = np.zeros(4) 					#define temporary point vector

#hole image setup
hole_image=np.zeros((img_height,slices+1,3), np.uint8)  #create new empty numpy for colored map
texture_image=np.zeros((img_height,slices+1,1), np.uint8)  #create new empty numpy for greyscale

#write the hole image
#cv2.imwrite(scan_dir + 'holes.png',hole_image)
#write the texture image
#cv2.imwrite(scan_dir + 'texture.png',texture_image)



while (cs <= slices) :
		filename=scan_dir +str(cs) + '.png'
		filename_l=scan_dir +str(cs) + '_l.png'

		#wait for the file to exist and be writeable + written (also, for real time processing!)
		img_l=None
		img=None
	
		#Not Needed anymore.
		#while img is None:
		#	# Load the image in grayscale 
		#	img = cv2.imread(filename)
		#	time.sleep(0.5)
		#	if debug:
		#		print "waiting img" , filename
		#	pass
		
		while img_l is None:
			# Load the color in grayscale 
			img_l = cv2.imread(filename_l)
			time.sleep(0.5)
			if debug:
				print "waiting img_l"
			pass
		
		if debug:
			print "File: " + filename + ":"
			print "F_OK" + str(os.access(filename, os.F_OK))
			print "R_OK" + str(os.access(filename, os.R_OK))
			print "W_OK" + str(os.access(filename, os.W_OK))
			print " "
			print "Laser File: " + filename_l + ":"
			print "F_OK" + str(os.access(filename_l, os.F_OK))
			print "R_OK" + str(os.access(filename_l, os.R_OK))
			print "W_OK" + str(os.access(filename_l, os.W_OK))


		#Create enhanced view of the Laser line
		hsv_img = cv2.cvtColor(img_l, cv.CV_BGR2HSV)

		# split into color channels
		h, s, v = cv2.split(hsv_img)

		channels['hue'] = h
		channels['saturation'] = s
		channels['value'] = v

		# Threshold the HSV img
		#threshold_image("hue") #not needed right now
		threshold_image("saturation")
		threshold_image("value")

		#Getting the laser line
		channels['laser'] = cv2.bitwise_and(channels['saturation'],channels['value'])

		#increasing precision by merging the Laser with Saturation (gives thinner line and bigger smooth subdomain)
		difference = cv2.bitwise_and(channels['laser'],s)

		#max values for each column
		ind=difference.argmax(axis=0)

		if debug:
			#create difference image for debug purposes
			#print len(ind)
			cv2.imwrite(scan_dir + str(cs) +'_clear.png',difference)
			newimage=np.zeros((img_width,img_height,3),np.uint8)  #create new empty (black) grayscale numpy img
		
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
					
				#print "from " + str(y1) + "to " + str(y2)	
				luminance_col=difference[y1:y2,col:col+1]
				luminance_col=np.swapaxes(luminance_col,1,0)
				
				if(domain.shape==luminance_col[0].shape):
				
					#average(a, axis=None, weights=None, returned=False):
					w_position=np.average(domain,0,luminance_col[0])    #weighted position  
					w_position=value+(w_position-subrange)				#correction of the position
					#print value , "-", w_position
				else:
					fail+=1
					if debug:
						print "Exiting subdomain in col :" + str(col) +" of slice " + str(cs) + " value:" + str(value)
						print "Domain:" + str(domain.shape) +" , Luminance col:" +str(luminance_col[0].shape)
						print "domain resized"
					w_position=value									#keep the max luminance found since the subdomain has violated the image borders
				
				
				#todo: noise filter
				
				#------------------RECONSTRUCTION----------------------------
				#-------Calculate XYZ coordinates for cloud points-----------						
				#-------for reference: wiki.fabtotum.cc
				
				if reconstruct_mode=="r":
					#ROTATIVE laser_scan reconstruction
					a_deg=cs*((float(end)-float(start))/float(slices))*math.pi/180			#degrees for each shot in radiants
					app_distance=abs(((img_height/2)-w_position)*mmpp)						#apparent distance in pixels *mm per pixels
					ro=float(app_distance*(2*np.tan(laser_angle*math.pi/180)))
					x=(ro*(np.cos(a_deg)))               										#switching to cartesian
					y=(ro*(np.sin(a_deg)))						
					z=(col*mmpp)+z_offset*mmpp

				#-----------------------------------
				if reconstruct_mode=="s":
					#SWEEPING laser scan reconstruction
					#area params:
					#			start  : x0 (mm, x position)
					#			end	   : x1 (mm, x position)
					#			slices : linear slices
					

					x=float((((end-start)/slices*cs)+int(start)))									#distance from the camera
					y=float(((float(img_height)/float(2))-col)/float(mmpp))+z_offset		   			#Y columns
					#z=float(((img_width/2)-w_position)/(img_width/2))*np.tan(30*math.pi/180)*x		#
					z=x*(np.tan((beta_angle*math.pi/180)-np.arctan(((img_width/2-w_position)/(img_width/2))*half_aperture)))

				#data collected, now add to cloud
				new_point = [x,y,z,1]
				points = np.vstack([points, new_point])
				#print "added point: " , new_point

				#cloud_data+=str(x)+ "," + str(y) + "," + str(z) + "\n"		
				
				if debug:
					print str(x)+ "," + str(y) + "," + str(z) + "\n"
					newimage.itemset((w_position,col,2),255) #Artificial vision of the laser line, debug only.
				
				#create texture image			
				#this is WIP.
				#crop_texture = img[200:400, 100:300] 
				#Crop from x, y, w, h -> 100, 200, 300, 400			
				#print "writing texture " , cs, " position: ", int(w_position) , "col: ", col 
				#place original value in the texture
				#original_value=img[int(w_position),col]    #get 0-255 greyscale in the original image, point of contact
				#print "brightness: ", original_value
				#texture_image[col,cs,0]=original_value
				#.itemset((cs,w_position),original_value)  #set 0-255 value

					
			#holes map
			if value==0:	
				#there is a hole! possible shadow.
				#print "there is a hole in slice" , cs , "col :", col
				hole_image[col,cs,2]=255 #place a red pixel.
				#hole_image.itemset((col,cs,2),255) #place a red pixel 
		print "failed subdomains :" + str(fail)
		fail=0
		
		#END COL CYCLE	
		percent=(float(cs)/slices)*100
				
		#update task log
		printlog(percent,cs) 
		
		if debug:
			if reconstruct_mode=="r":
				print str(cs) + " :" + filename_l + " - Segree: " + str(a_deg*180/3.14) + " - "+str(percent) + "%"
			if reconstruct_mode=="s":
				print str(cs) + " :" + filename_l + " - Sweep: " + str(cs) + "/"+ str(slices) + " - " +str(percent) + "%"
			
		if debug:
			#computer vision png preview is written.
			cv2.imwrite(scan_dir + str(cs) +'_vision.png',newimage)
			
		#print "slice ", cs, " start:",start," end:", end," - Delta x: " ,dx," - Delta z: ",dz , "wpos: ", w_position , " app dist= ",app_distance , " ro=", ro
				
		#next slice
		cs+=1
		
		#END SLICES CYCLE

		
#point postprocessing
if not a_offset==0:
	if debug:
		print "rotating points"
		#trace("Now rotating the points by "+str(a_offset)+ " degrees")
	points=rotate_y_axis(points,a_offset)  #this may take a while. note: +=CCW

#compile cloud data
convert_cloud(points) #save the ascii cloud data

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

if debug:
	print "done, Elapsed time: " + str(delta_t) 
	print "done!"

sys.exit()  