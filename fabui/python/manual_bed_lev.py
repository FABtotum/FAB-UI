#bed leveling tool
import time
import sys, os
import serial
from subprocess import call
import numpy as np

#Args
try:
	logfile=str(sys.argv[1]) #param for the log file
	log_trace=str(sys.argv[2])	#trace log file
	fix_d=float(sys.argv[3]) #hight of the plane. (smaller=higher)
	
except:
	print "Missing Log reference"
	
#vars

#print "json: "+logfile
#print "trace: "+log_trace

cycle=True
s_warning=s_error=s_skipped=0

screw_turns=["" for x in range(4)]
screw_height=["" for x in range(4)]
screw_degrees=["" for x in range(4)]

#points to probe
probed_points=np.array([[5+17,5+61.5,0],[5+17,148.5+61.5,0],[178+17,148.5+61.5,0],[178+17,5+61.5,0]])
#first screw offset (lower left corner)
screw_offset=[8.726,10.579,0]

serial_reply=""

#num of probes each point
num_probes=4

def trace(string):
	global log_trace
	out_file = open(log_trace,"a+")
	out_file.write(str(string) + "\n")
	out_file.close()
	#headless
	print string
	return
	
def printlog():
	global logfile
	global screw_turns
	global screw_height
	global screw_degrees
	str_log='{"bed_calibration":{"t1": "'+str(screw_turns[0])+'","t2": "'+str(screw_turns[1])+'","t3": "'+str(screw_turns[2])+'","t4": "'+str(screw_turns[3])+'","s1": "'+str(screw_height[0])+'","s2": "'+str(screw_height[1])+'","s3": "'+str(screw_height[2])+'","s4": "'+str(screw_height[3])+'","d1": "'+str(screw_degrees[0])+'","d2": "'+str(screw_degrees[1])+'","d3": "'+str(screw_degrees[2])+'","d4": "'+str(screw_degrees[3])+'"}}'
	#write log
	handle=open(logfile,'w+')
	print>>handle, str_log
	return	

def fitPlaneSVD(XYZ):
	#unused
    [rows,cols] = XYZ.shape
    # Set up constraint equations of the form  AB = 0,
    # where B is a column vector of the plane coefficients
    # in the form b(1)*X + b(2)*Y +b(3)*Z + b(4) = 0.
    p = (np.ones((rows,1)))
    AB = np.hstack([XYZ,p])
    [u, d, v] = np.linalg.svd(AB,0)        
    B = v[3,:];                    # Solution is last column of v.
    nn = np.linalg.norm(B[0:3])
    B = B / nn
    return B[0:3] #a b c
	
from geometry import Point, Line, Plane
     
def fitplane(XYZ):
	[npts,rows] = XYZ.shape

	if not rows == 3:
		#print XYZ.shape
		raise ('data is not 3D')
		return None

	if npts <3:
		raise ('too few points to fit plane')
		return None

	# Set up constraint equations of the form  AB = 0,
	
	# where B is a column vector of the plane coefficients
	# in the form   b(1)*X + b(2)*Y +b(3)*Z + b(4) = 0.
	t = XYZ
	p = (np.ones((npts,1)))
	A = np.hstack([t,p])

	if npts == 3:                       # Pad A with zeros
		A = [A, np.zeros(1,4)]

	[u, d, v] = np.linalg.svd(A)        # Singular value decomposition.
	#print v[3,:]
	B = v[3,:];                         # Solution is last column of v.
	nn = np.linalg.norm(B[0:3])
	B = B / nn
	plane = Plane(Point(B[0],B[1],B[2]),D=B[3])
	return plane
	
def read_serial(gcode):
	serial.flushInput()
	serial.write(gcode + "\r\n")
	time.sleep(0.1)
	
	#return serial.readline().rstrip()
	response=serial.readline().rstrip()
	
	if response=="":
		return "NONE"
	else:
		return response
		
def macro(code,expected_reply,timeout,error_msg,delay_after,warning=False,verbose=True):
	global s_error
	global s_warning
	global s_skipped
	serial.flushInput()
	if s_error==0:
		serial_reply=""
		macro_start_time = time.time()
		serial.write(code+"\r\n")
		if verbose:
			trace(error_msg)
		time.sleep(0.3) #give it some tome to start
		while not (serial_reply==expected_reply or serial_reply[:4]==expected_reply):
			#Expected reply
			#no reply:
			if (time.time()>=macro_start_time+timeout+5):
				if serial_reply=="":
					serial_reply="<nothing>"
				if not warning:
					s_error+=1
					trace(error_msg + ": Failed (" +serial_reply +")")
				else:
					s_warning+=1
					trace(error_msg + ": Warning! ")
				return False #leave the function
			serial_reply=serial.readline().rstrip()
			#add safety timeout
			time.sleep(0.2) #no hammering
			pass
		time.sleep(delay_after) #wait the desired amount
	else:
		trace(error_msg + ": Skipped")
		s_skipped+=1
		return False
	return serial_reply

trace("Manual Bed Calibration Wizard Initiated")
port = '/dev/ttyAMA0'
baud = 115200

#initialize serial
serial = serial.Serial(port, baud, timeout=0.6)
serial.flushInput()


macro("M741","TRIGGERED",2,"Front panel door control",1, verbose=False)	
macro("M402","ok",2,"Retracting Probe (safety)",1, warning=True, verbose=False)	
macro("G27","ok",100,"Homing Z - Fast",0.1)	

macro("G90","ok",5,"Setting abs mode",0.1, verbose=False)
macro("G92 Z241.2","ok",5,"Setting correct Z",0.1, verbose=False)
#M402 #DOUBLE SAFETY!
macro("M402","ok",2,"Retracting Probe (safety)",1, verbose=False)	
macro("G0 Z60 F5000","ok",5,"Moving to start Z height",10) #mandatory!

for (p,point) in enumerate(probed_points):

	#real carriage position
	x=point[0]-17
	y=point[1]-61.5
	macro("G0 X"+str(x)+" Y"+str(y)+" Z45 F10000","ok",15,"Moving to Pos",3, warning=True,verbose=False)		
	msg="Measuring point " +str(p+1)+ "/"+ str(len(probed_points)) + " (" +str(num_probes) + " times)"
	trace(msg)
	#Touches 4 times the bed in the same position
	probes=num_probes #temp
	for i in range(0,num_probes):
		
		#M401
		macro("M401","ok",2,"Lowering Probe",1, warning=True, verbose=False)	
		
		serial.flushInput()
		#G30	
		serial.write("G30\r\n")
		#time.sleep(0.5)			#give it some to to start  
		probe_start_time = time.time()
		while not serial_reply[:22]=="echo:endstops hit:  Z:":
			serial_reply=serial.readline().rstrip()	
			#issue G30 Xnn Ynn and waits reply.
			if (time.time() - probe_start_time>20):  #timeout management
				trace("Probe failed on this point")
				probes-=1 #failed, update counter
				break	
			pass
			
		#print serial_reply
		#get the z position
		if serial_reply!="":
			z=float(serial_reply.split("Z:")[1].strip())
			#trace("probe no. "+str(i+1)+" = "+str(z) )
			probed_points[p,2]+=z # store Z
			
		serial_reply=""
		serial.flushInput()
		
		#G0 Z40 F5000
		macro("G0 Z50 F5000","ok",10,"Rising Bed",1, warning=True, verbose=False)
		
	#mean of the num of measurements
	probed_points[p,0]=probed_points[p,0]
	probed_points[p,1]=probed_points[p,1]
	probed_points[p,2]=probed_points[p,2]/probes; #mean of the Z value on point "p"
	
	#trace("Mean ="+ str(probed_points[p,2]))
	
	msg="Point " +str(p+1)+ "/"+ str(len(probed_points)) + " , Z= " +str(probed_points[p,2])
	trace(msg)
	
	macro("M402","ok",2,"Raising Probe",1, warning=True, verbose=False)	
	
	#G0 Z40 F5000
	macro("G0 Z50 F5000","ok",2,"Rising Bed",0.5, warning=True, verbose=False)
	
#now we have all the 4 points.
macro("G0 X5 Y5 Z50 F10000","ok",2,"Idle Position",0.5, warning=True, verbose=False)

macro("M18","ok",2,"Motors off",0.5, warning=True, verbose=False)

#offset from the first calibration screw (lower left)
probed_points=np.add(probed_points,screw_offset)

#DEBUG 
#print probed_points
#print "-----"

#Working too
#Fit= fitPlaneSVD(probed_points)

Fit = str(fitplane(probed_points))

#Plane(Point(0.003868641782933245, 0.003058519080316435, -0.999987839461956), 37.786092179315283)
#Messy!
coeff = Fit.split("(")[2]
d = float(coeff.split(" ")[3][:9])
coeff = coeff.split(")")[0].replace(" ","")
#print coeff
coeff = coeff.split(",")

#convert to float
for (i,c) in enumerate(coeff):
	coeff[i]=float(c)
	#print coeff[i]

#we retrieve the height of the probe.
serial.flushInput()
serial.write("M503\r\n")
data=serial.read(1024)
z_probe=float(data.split("Z Probe Length: ")[1].split("\n")[0])

d_ovr=d

msg= "d_ovr="+str(d_ovr)
#trace(msg) 
#eq of a plane= ax +by +cz = d

msg= "Equation of the plane: \n "+ str(coeff[0]) +"x +"+ str(coeff[1]) +"y + "+ str(coeff[2]) +"z =" + str(d)
trace(msg)
#Calibration Points of the screws
cal_point=np.array([[0-8.726,0-10.579,0],[0-8.726,257.5-10.579,0],[223-8.726,257.5-10.579,0],[223-8.726,0-10.579,0]])

idx=0
for (p,point) in enumerate(cal_point):
	#cal_point[p][0][1]  => point[1]  #Y coordinate of point 0

	z=(-coeff[0]*point[0] - coeff[1]*point[1] +d)/coeff[2]
		
	#difference from titled plane to straight plane
	#distance=P2-P1
	diff=abs(-d_ovr)-abs(z)
	
	#msg= "d :"+str(d)+", P :"+str(p)+" , Z:" +str(z) +" Diff: "+str(diff) +" d_ovr: "+str(d_ovr) 
	msg= str(d_ovr)+ "-"+str(abs(z))+" = " + str(diff)
	
	trace(msg)
	
	#number of screw turns, pitch 0.5mm
	turns=round(diff/0.5, 2) #
	degrees= turns*360
	degrees=int(5 * round(float(degrees)/5))  #lets round to upper 5
	
	screw_turns[idx]=turns
	screw_height[idx]=diff
	screw_degrees[idx]=degrees
	
	idx+=1
	#print "Calculated=" + str(z) + " Difference " + str(diff) +" Turns: "+ str(turns) + " deg: " + str(degrees)

#save everything
printlog()

#end
trace("Done!")
sys.exit()