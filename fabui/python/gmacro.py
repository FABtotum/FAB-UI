#!/usr/bin/python
#gmacro.py controlled 1by1 gcode operations
import sys
import time, datetime
import serial
import json

#read config steps/units
json_f = open("/var/www/fabui/config/config.json")
units = json.load(json_f)

#process params
try:
	preset=str(sys.argv[1])  #param for the gcode to execute
	log_trace=str(sys.argv[2]) #param for the log file
	log_response=str(sys.argv[3]) #param for the log file
except:
	print("Missing params")
	sys.exit()
	
#generic errors
probe_start_time=0 #start time
s_error=0
s_warning=0
s_skipped=0

feeder_disengage_offset=2 #mm distance to disable extruder

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
			trace(error_msg, log_trace)
		
		time.sleep(0.3) #give it some tome to start 
		while not (serial_reply==expected_reply or serial_reply[:4]==expected_reply):
			#Expected reply
			#no reply:
			if (time.time()>=macro_start_time+timeout+5):
				if serial_reply=="":
					serial_reply="<nothing>"
				#trace_msg="failed macro (timeout):"+ code+ " expected "+ expected_reply+ ", received : "+ serial_reply
				#trace(trace_msg,log_trace)
				#print trace_msg
				
				if not warning:
					s_error+=1
					trace(error_msg + ": Failed (" +serial_reply +")",log_trace)
				else:
					s_warning+=1
					trace(error_msg + ": Warning! ",log_trace)
				
				return False #leave the function
			

			serial_reply=serial.readline().rstrip()
			#add safety timeout
			time.sleep(0.2) #no hammering 
			pass
				
		
		time.sleep(delay_after) #wait the desired amount
	else:
		trace(error_msg + ": Skipped",log_trace)
		s_skipped+=1
		return False	
	
	return serial_reply

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.5)
serial.flushInput()

#preset choice

#pre_print CHECK (SAFETY)
if preset=="check_pre_print":
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door control",0.1)
	macro("M744","TRIGGERED",1,"Building plane control",0.1)
	macro("M744","TRIGGERED",1,"Spool panel control",0.1, warning=True)	
	#check head inserted?

	
#pre_SCAN CHECK (SAFETY)
elif preset=="check_pre_scan":
	trace ("Preparing the FABtotum to scan",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door control",0.1)
	#macro("M744","open",1,"Building plane control",0.1)
	macro("M744","TRIGGERED",1,"Spool panel control",1, warning=True)
	macro("G90","ok",2,"Setting absolute positioning mode",1)
	macro("G27","ok",100,"zeroing Z axis",1)
	macro("G92 Z240","ok",3,"Zeroing Z axis",1)
	#disable feeder
	macro("G91","ok",2,"Setting Relative position",1,verbose=False)
	macro("G0 Z-"+str(feeder_disengage_offset)+" F400","ok",2,"Engaging 4th Axis motion",1)
	macro("G90","ok",2,"Setting Absolute position",1,verbose=False)
	macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0,verbose=False)
	#move to collimation
	macro("G0 Z140 F1000","ok",5,"Moving to pre-scan position",3)
	
#engage feeder (require manual intervention)
elif preset=="engage_feeder":
	trace ("Engaging 3D-Printer Feeder",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door control",0.1,verbose=False)
	macro("M744","TRIGGERED",1,"Spool panel control",1, warning=True)
	macro("G27","ok",100,"zeroing Z axis",1,verbose=False)
	macro("G92 Z240","ok",3,"Setting Z position",1,verbose=False)
	macro("G91","ok",2,"Set relative movement",1,verbose=False)
	#go to fixed position, so botton can be pushed.
	macro("G0 Z-4 F1000","ok",3,"Setting Z position",1,verbose=False)
	macro("G90","ok",2,"Set absolute movement",1,verbose=False)
	macro("M92 E"+str(units['e']),"ok",1,"Setting steps/units for 3D printing",0.5,verbose=False)
	macro("M18","ok",2,"Stopping motors",0.5,verbose=False)
	
elif preset=="engage_4axis":
	#Used for Jog
	trace ("Engaging 4th Axis",log_trace)
	macro("G27","ok",100,"Zeroing Z axis",1)
	macro("G91","ok",2,"Setting Relative position",1,verbose=False)
	macro("G0 Z+"+str(feeder_disengage_offset)+" F400","ok",5,"Engaging 4th Axis motion",1)
	macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0,verbose=False)

	
#jog_setup (operations before jogging)
elif preset=="jog_setup":
	trace ("preparing to jog",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door is not closed",1)
	macro("G91","ok",1,"Setting relative positioning",0.5)
	macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0.5)
	trace(macro("M105","ok T",2,"Getting nozzle temperatures",0.5, warning=True),log_trace)

#start print (valid for 3dprinting or milling)
elif preset=="start_print":
	trace("Preparing the FABtotum Personal Fabricator",log_trace)
	macro("G90","ok",2,"setting abs position",0)
	macro("G0 X5 Y5 Z30","ok",3,"Move to oozing point",1)
	#pre heating M104 S0
	macro("M104 S180","ok",3,"Pre Heating",20)
	macro("M140 S50","ok",3,"Pre Heating Bed",20) 
	macro("M220 S100","ok",1,"reset speed override",0.1)
	macro("M92 E"+str(units['e']),"ok",1,"Set extruder mode",0.1)	
	
elif preset=="end_print":
	serial.flush()
	trace("Terminating...",log_trace)
	#note: movement here is doen so it works with manual positioning too.
	macro("G27","ok",40,"Lowering the building platform",0.1) #normally Z=230mm
	macro("M302","ok",3,"Aborting temp rise",0.1)
	macro("M104 S0","ok",3,"Shutting down Extruder",0.1)
	macro("M140 S0","ok",3,"Shutting down Heated Bed",0.1) 
	
	macro("G28 X0 Y0","ok",10,"Going to safe pos",0.1) #normally Z=230mm
	macro("G0 X190 Y190 F1000","ok",10,"Going to safe pos",0.1,verbose=False)#move in a corner
	
	macro("M220 S100","ok",1,"reset speed override",0.1)
	macro("M5","ok",5,"Disabling milling motor",1) #should be moved to firmware
	macro("M106 S0","ok",1,"Turning Fan off",1) #should be moved to firmware
	macro("M18","ok",1,"Motor Off",1) #should be moved to firmware
	

elif preset=="raise_bed":
	#for homing procedure before calibration.
	macro("G90","ok",1,"setting relative position",1)
	macro("G0 X0 Y0 Z40","TRIGGERED",2,"Front panel door control",5)
	
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
	trace("Initializing Rotative Laser scanner",log_trace)
	
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door control",0.1)
	macro("M744","open",1,"Building plane removed",0.1)
	macro("M744","TRIGGERED",1,"Spool panel closed",0.1, warning=True)

	macro("M701 S0","ok",2,"turning off lights",0.1)
	macro("M702 S0","ok",2,"turning off lights",0.1)
	macro("M703 S0","ok",2,"turning off lights",0.1)

	macro("G90","ok",2,"setting abs position",1)
	macro("G0 X99 Y190 Z120 F10000","ok",90,"Moving to collimation position",1)
	macro("M302 S0","ok",2,"Enabling cold extrusion",0)
	#macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0)

#s_scan preset
elif preset=="s_scan":
	trace("Initializing Sweeping Laserscanner",log_trace)
	
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door is not closed",0.1)
	macro("M744","open",2,"Building plane removed!",0.1,warning=True)
	macro("M744","TRIGGERED",1,"Spool panel is not closed!",0.1, warning=True)
	
	macro("M701 S0","ok",2,"turning off lights",0.1)
	macro("M702 S0","ok",2,"turning off lights",0.1)
	macro("M703 S0","ok",2,"turning off lights",0.1)
	
	macro("M744","open",2,"Working plane absent/tilted",0.1)
	macro("G28 X0 Y0","ok",90,"homing all axis",1) 
	macro("G90","ok",2,"setting abs position",0)
	#macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0)
	macro("G0 Z145 F1000","ok",90,"lowering the plane",1) 

#p_scan preset
elif preset=="p_scan":
	trace("Initializing Probing procedure",log_trace)
	trace ("checking panel door status and bed inserted",log_trace)
	macro("M741","TRIGGERED",2,"Front panel door is not closed",0.1)
	macro("M744","open",2,"Building plane has been tilted or removed!",0.1)
	macro("M744","TRIGGERED",2,"Spool panel is not closed!",0.1, warning=True)
	macro("G90","ok",2,"Setting abs position",0)
	macro("M302 S0","ok",2,"Disabling cold extrusion prevention",0)
	macro("M92 E"+str(units['a']),"ok",2,"Setting 4th axis mode",0)
	
elif preset=="end_scan":
	trace("Initializing Probing procedure",log_trace)
	macro("M700","ok",1,"Shutting Down Laser",0.1)
	macro("G28 X0 Y0","ok",1,"Homing",2)
	macro("M402","ok",1,"Disabling probe",2)
	macro("G0 Z140 F1000","ok",5,"Moving to pre-scan position",3)
	macro("M701 S"+str(units[color][r]),"ok",2,"turning on lights",0.1)
	macro("M702 S"+str(units[color][g]),"ok",2,"turning on lights",0.1)
	macro("M703 S"+str(units[color][b]),"ok",2,"turning on lights",0.1)
	
#zero_all
elif preset=="home_all":
	trace("Now homing all axes",log_trace)
	macro("G90","ok",2,"set abs position",0)
	macro("G28","ok",100,"homing all axes",1)

#unload spool
elif preset=="unload_spool":
	trace("Unloading Spool",log_trace)
	macro("M83","ok",5,"setting relative estrusion",0.1)
	macro("G92 E0","ok",5,"set extruder to zero",0.1)
	macro("M92 E"+str(units['e']),"ok",30,"setting extruder mode",0.1)
	#heating nozzle
	macro("M109 S190","ok",120,"Heating Nozzle...",0.1)
	macro("M728","ok",1,"Start pulling!",3)
	macro("G0 E-200 F200","ok",10,"Exiting from the heating chamber (slow!)",60)
	macro("G0 E-700 F550","ok",10,"expelling filament",60)
	macro("M82","ok",2,"setting back absolute estrusion",0)
	macro("M104 S0","ok",1,"Disabling Temperature",1)

#load spool
elif preset=="load_spool":
	trace("loading Spool",log_trace)
	macro("G27","ok",100,"zeroing Z axis",0.1)
	macro("G0 Z150 F1000","ok",3,"Zeroing Z axis",0.1)
	macro("M302 S0","ok",5,"Enabling Cold extrusion",0.1)
	macro("M83","ok",5,"setting relative estrusion mode",0.1)
	macro("G92 E0","ok",5,"setting extruder position to 0",0.1)
	macro("M92 E"+str(units['e']),"ok",5,"Setting extruder mode",0.1)
	macro("M104 S190","ok",5,"Heating Nozzle",1) #heating and waiting.
	macro("M728","ok",5,"Start pushing!",3)
	macro("G0 E110 F500","ok",1,"Loading filament",15)
	macro("G0 E660 F700","ok",1,"Loading filament (fast)",20)
	macro("M109 S190","ok",100,"waiting to get to temperature...",1, warning=True) #heating and waiting.
	
	macro("G0 E130 F200","ok",1,"Entering heating chamber (slow)",5)
	macro("M82","ok",1,"setting back absolute estrusion",0)
	macro("M104 S0","ok",1,"Disabling Heater",3)
	macro("M302 S160","ok",1,"Disabling cold extrusion",0.1)
	
#jog setup procedure	
elif preset=="jog_setup":
	trace("Engaging Jog Mode",log_trace)
	macro("M92 E"+str(units['a']),"ok",1,"Set 4th axis mode",1)
	macro("G91","ok",1,"set relative movements",1)

#flash finalization procedure	
elif preset=="start_up":
	trace("Starting up",log_trace)
	macro("M728","ok",2,"Alive!",1,verbose=False)
	macro("M701 S"+str(units[color][r]),"ok",2,"turning on lights",0.1)
	macro("M702 S"+str(units[color][g]),"ok",2,"turning on lights",0.1)
	macro("M703 S"+str(units[color][b]),"ok",2,"turning on lights",0.1)

#shutdown
elif preset=="shutdown":
	trace("shutting down",log_trace)
	macro("M729","ok",2,"Asleep!",1,verbose=False)
	
#probe calibration
elif preset=="probe_setup_prepare":
	trace("Preparing Calibration procedure",log_trace)
	macro("G90","ok",1,"Abs_mode",1)
	macro("G28","ok",90,"homing all axis",1)
	macro("G91","ok",1,"Relative mode",1)
	macro("G0 X17 Y61.5","ok",1,"Offset",1)
	macro("G90","ok",1,"Abs_mode",1)
	macro("G0 Z1 F1000","ok",2,"Moving to calibration position",1)
	#macro("M109 S200","ok",90,"Heating Nozzle ccounting for thermal expasion)",30)	
		
elif preset=="probe_setup_calibrate":
	trace("Calibrating probe",log_trace)
	macro("M104 S0","ok",90,"nozzle off",1)
	#get old probe-nozzle height difference
	serial.flushInput()
	serial.write("M503\r\n")
	data=serial.read(1024)
	z_probe_old=float(data.split("Z Probe Length: ")[1].split("\n")[0])
	trace("Old Position : "+str(z_probe_old)+" mm",log_trace)
	
	#get Z position
	serial.flushInput()
	serial.write("M114\r\n")
	data=serial.read(1024)
	z_touch=float(data.split("Z:")[1].split(" ")[0])
	trace("Current height : "+str(z_touch)+" mm",log_trace)
	
	#write config to EEPROM
	z_probe_new=abs(z_probe_old+(z_touch-0.1))
	serial.write("M710 S"+str(z_probe_new)+"\r\n")
	####
	macro("G28","ok",90,"homing all axis",1)
	trace("Probe calibrated : "+str(z_probe_new)+" mm",log_trace)
	
	
#END 

if s_error>0:
	trace("false",log_response)
	trace(str(s_error) + " Error(s) occurred",log_trace)
	trace(str(s_skipped) + " operation(s) have been skipped due to errors." ,log_trace)
	trace("<b>Try Again!</b>", log_trace )
else:
	trace("true",log_response)
	trace("All clear!",log_trace)

#clean the buffer and leave
serial.flush()
serial.close()

sys.exit()