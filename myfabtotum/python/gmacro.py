#gmacro.py controlled 1by1 gcode operations
import sys
import time, datetime
import serial
import json

#read config steps/units
json_f = open("/var/www/myfabtotum/config/units.json")
units = json.load(json_f)

#process params
preset=str(sys.argv[1])  #param for the gcode to execute
log_trace=str(sys.argv[2]) #param for the log file
log_response=str(sys.argv[3]) #param for the log file

#generic errors
probe_start_time=0 #start time
s_error=0
s_warning=0

#track trace
def trace(string,destination_file):
	out_file = open(destination_file,"a+")
	out_file.write(str(string) + "<br>")
	out_file.close()
	print string
	return

#get process pid so the UI can kill it if needed
#myPID = os.getpid()
#print myPID
#trace(myPID,log_trace)

#gcode macro exec
def macro(code,expected_reply,timeout,error_msg,delay_after,warning=False):
	global s_error
	global s_warning
	serial_reply=""
	macro_start_time = time.time()
	serial.write(code+"\r\n")
	trace(error_msg, log_trace)
	time.sleep(0.3) #give it some tome to start 
	while not (serial_reply==expected_reply or serial_reply[:4]==expected_reply):
		#Expected reply
		#no reply:
		if (time.time()>=macro_start_time+timeout):
			if serial_reply=="":
				serial_reply="<nothing>"
			#trace_msg="failed macro (timeout):"+ code+ " expected "+ expected_reply+ ", received : "+ serial_reply
			#trace(trace_msg,log_trace)
			#print trace_msg
			
			if not warning:
				s_error+=1
				trace(error_msg + ": Failed",log_trace)
			else:
				s_warning+=1
				trace(error_msg + ": Warning! ",log_trace)
			
			return False #leave the function
		
		
		serial_reply=serial.readline().rstrip()
		#add safety timeout
		time.sleep(0.1) #no hammering 
		pass
		
	time.sleep(delay_after) #wait the desired amount
	return serial_reply

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.5)
serial.flushInput()

#preset choice

#pre_print CHECK (SAFETY)
if preset=="check_pre_print":
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",1,"Front panel door is not closed",0.1)
	macro("M744","TRIGGERED",1,"Building plane is not properly positioned!",0.1)
	macro("M744","TRIGGERED",1,"Spool panel is not closed!",0.1,warning=True)
	#check head inserted?

	
#pre_SCAN CHECK (SAFETY)
if preset=="check_pre_scan":
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",1,"Front panel door is not closed",0.1)
	macro("M744","open",1,"Building plane is still present!",0.1)
	macro("M744","TRIGGERED",1,"Spool panel is not closed!",0.1,warning=True)
	
	
#jog_setup (operations before jogging)
if preset=="jog_setup":
	trace ("preparing to jog",log_trace)
	macro("M741","TRIGGERED",1,"Front panel door is not closed",1)
	macro("G91","ok",1,"Setting relative positioning",0.5)
	macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0.5)
	trace(macro("M105","ok T",1,"Getting nozzle temperatures",0.5),log_trace,warning=True)

	
#start print (valid for 3dprinting or milling)
elif preset=="start_print":
	trace("Preparing the FABtotum Personal Fabricator",log_trace)
	macro("G90","ok",1,"Setting abs position",0)
	macro("G0 X5 Y5 Z30","ok",3,"Move to oozing point",1)
	#pre heating M104 S0
	macro("M104 S180","ok",3,"Pre Heating",20)
	macro("M140 S50","ok",3,"Pre Heating Bed",20) 
	macro("M220 S100","ok",1,"reset speed override",0)
	macro("M92 E"+str(units['e']),"ok",1,"Set extruder mode",0)	
	
#END print (valid for 3dprinting or milling)
elif preset=="end_print":
	trace("Terminating...",log_trace)
	macro("G90","ok",1,"Setting abs position",0)
	macro("G0 X205 Y190 Z130 F5000","ok",40,"Lowering the building platform",0) #normally Z=230mm
	macro("M220 S100","ok",1,"reset speed override",0)
	macro("M302","ok",3,"Aborting temp rise",0)
	macro("M104 S0","ok",3,"Shutting down Extruder",0)
	macro("M140 S0","ok",3,"Shutting down Heated Bed",0) 
	#macro("M1","ok",1,"Disabling heaters and motors",1)
	macro("M5","ok",1,"Disabling milling motor",1) #should be moved to firmware

#Auto bed leveling	
elif preset=="auto_bed_leveling":
	trace("Auto Bed leveling Initialized",log_trace)
	macro("G91","ok",1,"setting relative position",1)
	macro("G0 Z25","ok",3,"moving away from the plane",1) 	
	macro("G90","ok",2,"setting abs position",1)
	macro("G28","ok",90,"homing all axis",1) 
	macro("G29","ok",140,"Auto bed leveling procedure",1)
	macro("G0 X5 Y5 Z60 F2000","ok",100,"Getting to idle position",1)

#r_scan rotative scan preset
elif preset=="r_scan":
	trace("Initializing Rotative Laserscanner",log_trace)
	macro("G90","ok",1,"setting abs position",1)
	macro("G27","ok",100,"zeroing  Z axis",1)
	macro("G0 X120 Y120 Z120 F10000","ok",90,"Moving to collimation position",1)
	macro("M302 S0","ok",1,"Enabling cold extrusion",0)
	macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0)

#s_scan preset
elif preset=="s_scan":
	trace("Initializing Sweeping Laserscanner",log_trace)
	macro("G90","ok",1,"setting abs position",0)

#p_scan preset
elif preset=="p_scan":
	trace("Initializing Probing procedure",log_trace)
	macro("G90","ok",1,"setting abs position",0)
	macro("M92 E"+str(units['a']),"ok",1,"Set 4th axis mode",0)

#zero_all
elif preset=="home_all":
	trace("Now homing all axes",log_trace)
	macro("G90","ok",1,"set abs position",0)
	macro("G28","ok",100,"homing all axes",1)

#unload spool
elif preset=="unload_spool":
	trace("Unloading Spool",log_trace)
	macro("M83","ok",1,"setting relative estrusion",0)
	macro("G92 E0","ok",1,"set extruder to zero",0)
	macro("M92 E"+str(units['e']),"ok",30,"setting extruder mode",0)
	#heating nozzle
	macro("M109 S210","ok",60,"Heating Nozzle",60)
	macro("G0 E-700 F500","ok",10,"expelling filament",60)
	macro("M82","ok",2,"setting back absolute estrusion",0)
	macro("M104 S0","ok",1,"Disabling Temperature",40)

#load spool
elif preset=="load_spool":
	trace("loading Spool",log_trace)
	macro("M83","ok",1,"setting relative estrusion",0)
	macro("G92 E0","ok",1,"setting extruder position to 0",0)
	macro("M92 E"+str(units['e']),"ok",1,"Setting extruder mode",0)
	macro("M109 S210","ok",60,"Heating Nozzle",60) #heating and waiting.
	macro("M728","ok",1,"beep signal",1)
	macro("G0 E100 F500","ok",2,"Loading filament",15)
	macro("G0 E650 F700","ok",5,"Loading filament (fast)",20)
	macro("G0 E50 F200","ok",2,"Entering fusion chamber",0)
	macro("M82","ok",1,"setting back absolute estrusion",0)
	macro("M104 S0","ok",1,"Disabling Temperature",3)
	
#jog setup procedure	
elif preset=="jog_setup":
	trace("Engaging Jog Mode",log_trace)
	macro("M92 E"+str(units['a']),"ok",1,"Set 4th axis mode",1)
	macro("G91","ok",1,"set relative movements",1)
	
#END 

if s_error>0:
	trace("false",log_response)
	trace(str(s_error) + " error(s) occurred",log_trace)
else:
	trace("true",log_response)
	trace("All clear!",log_trace)

#clean the buffer and leave
serial.flush()
serial.close()

sys.exit()