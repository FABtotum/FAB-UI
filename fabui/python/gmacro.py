#!/usr/bin/python
#gmacro.py controlled 1by1 gcode operations
import sys
import time, datetime
import serial
import json
import ConfigParser
import logging


config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

macro_status = config.get('macro', 'status_file')

#read config steps/units
json_f = open(config.get('printer', 'settings_file'))
units = json.load(json_f)
#process params

if 'settings_type' in units and units['settings_type'] == 'custom':
    json_f = open(config.get('printer', 'custom_settings_file'))
    units = json.load(json_f)


try:
    safety_door = int(units['safety']['door'])
    zprobe_disabled = int(units['zprobe']['disable']) == 1
    zmax_home_pos = float(units['zprobe']['zmax'])
except KeyError:
    safety_door = 0
    zmax_home_pos = 206.0
    zprobe_disabled = False


print zmax_home_pos
print zprobe_disabled

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
feeder_disengage_offset=units['feeder']['disengage-offset'] #mm distance to disable extruder

trace_file=config.get('macro', 'trace_file')
response_file=config.get('macro', 'response_file')

logging.basicConfig(filename=log_trace,level=logging.INFO,format=' %(message)s',datefmt='%d/%m/%Y %H:%M:%S')


open(trace_file, 'w').close() #reset trace file
open(response_file, 'w').close() #reset trace file

def write_status(status):
    global macro_status
    json='{"type": "status", "status": ' + str(status).lower() +'}'
    handle=open(macro_status,'w+')
    print>>handle, json
    handle.close()
    return

#track trace
def trace(string,destination_file):
    
    global trace_file
    
    #out_file_trace = open(trace_file,"a+")
    #out_file_trace.write(str(string)+'\n')
    #out_file_trace.close()
    
    #if(destination_file != trace_file):
    #    out_file = open(destination_file,"a+")
    #    out_file.write(str(string) + "<br>")
    #    out_file.close()
    #    print string
    print string
    logging.info(string)
    return

def response(string):
    
    global response_file
    global log_response
    
    out_file_trace = open(response_file,"a+")
    out_file_trace.write(str(string))
    out_file_trace.close()
    
    if(log_response != response_file):
        out_file = open(log_response,"a+")
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


write_status(True)
'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')

serial = serial.Serial(serial_port, serial_baud, timeout=0.5)
serial.flushInput()

#pre_print CHECK (SAFETY)
if preset=="check_pre_print":
    trace("Checking safety measures",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1)
    macro("M744","TRIGGERED",1,"Building plane inserted correctly",0.1, warning=True)
    macro("M744","TRIGGERED",1,"Spool panel control",0.1, warning=True)
    
#pre_SCAN CHECK (SAFETY)
elif preset=="check_pre_scan":
    trace("Preparing the FABtotum to scan",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1)
    #macro("M744","open",1,"Building plane control",0.1)
    macro("M744","TRIGGERED",1,"Spool panel control",1, warning=True)
    macro("G90","ok",2,"Setting absolute positioning mode",1)
    macro("G27","ok",100,"Zeroing Z axis",1)
    macro("G28 X0 Y0","ok",15,"Zeroing Z axis",1)
    #disable feeder
    macro("G91","ok",2,"Setting relative position",1,verbose=False)
    macro("G0 X5 Y5 Z-"+str(feeder_disengage_offset)+" F400","ok",2,"Engaging 4th Axis Motion",1)
    macro("G90","ok",2,"Setting Absolute position",1,verbose=False)
    macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0,verbose=True)
    #move to collimation
    macro("G0 Z135 F1000","ok",5,"Moving to pre-scan position",3)
    macro("M18","ok",1,"Motor Off",1) #should be moved to firmware
        
#engage feeder (require manual intervention)
elif preset=="engage_feeder":
    trace("Engaging 3D-Printer Feeder",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1,verbose=False)
    macro("M744","TRIGGERED",1,"Spool panel control",1, warning=True)
    macro("G27 Z240","ok",100,"zeroing Z axis",0.1,verbose=False)
    #macro("G28 X0 Y0","ok",100,"Setting Z position",0.1,verbose=False)
    macro("G91","ok",2,"Set relative movement",0.1,verbose=False)
    #go to fixed position, so feeder botton can be pushed.
    macro("G0 Z-4 F1000","ok",3,"Setting Z position",0.1,verbose=False)
    macro("G90","ok",2,"Set absolute movement",0.1,verbose=False)
    macro("M92 E"+str(units['e']),"ok",2,"Setting steps/units for 3D printing",0.5,verbose=False)
    macro("M18","ok",3,"Stopping motors",0.1,verbose=False)
    macro("M300","ok",3,"Play beep sound",1,verbose=False)
    
    
elif preset=="engage_4axis":
    #Used for Jog
    trace("Engaging 4th Axis",log_trace)
    macro("G27 Z206","ok",100,"Zeroing Z axis",0.1)
    macro("G91","ok",1,"Setting Relative position",0.1,verbose=False)
    macro("G0 Z+"+str(feeder_disengage_offset)+" F300","ok",5,"Engaging 4th Axis Motion",0.1)
    macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0,verbose=False)
    macro("G92 Z241","ok",1,"Setting position",0.1, verbose=False)
    macro("G90","ok",1,"Setting Absolute position",0.1,verbose=False)
    macro("G0 Z234","ok",1,"Check position",0.1,verbose=False)
    macro("M300","ok",3,"Play beep sound",1,verbose=False)
    
elif preset=="4th_axis_mode":
    macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0,verbose=False)
    
#jog_setup (operations before jogging)
elif preset=="jog_setup":
    #macro("G91","ok",1,"Setting relative positioning",0.5,verbose=False)
    macro("M92 E"+str(units['e']),"ok",1,"Setting 4th axis mode",0.5, verbose=False) #default 4th axis. require M302 S0 to disable cold extrusion (safety)
    #trace(macro("M105","ok T",2,"Getting nozzle temperatures",0.5, warning=True),log_trace)
    macro("M106", "ok", 5, "Fan on", 1, verbose=False) #Turn on the fan
    trace ("Ready to jog",log_trace)
    
#start print (valid for 3dprinting or milling)
elif preset=="start_print":
    trace("Preparing the FABtotum Personal Fabricator",log_trace)
    macro("G90","ok",2,"Setting absolute position",0,verbose=False)
    macro("G0 X5 Y5 Z60 F1500","ok",3,"Moving to oozing point",1)
    #macro("G0 Z60 F1500","ok",3,"Moving to oozing point",1)
    #pre heating M104 S0
    macro("M104 S180","ok",3,"Pre Heating Nozzle (fast) ",20)
    macro("M140 S50","ok",3,"Pre Heating Bed (fast) ",20)
    macro("M220 S100","ok",1,"Reset Speed factor override",0.1,verbose=False)
    macro("M221 S100","ok",1,"Reset Extruder factor override",0.1,verbose=False)
    macro("M106 S255","ok",50,"Turning Fan On",1)
    macro("M92 E"+str(units['e']),"ok",1,"Setting extruder mode",0.1,verbose=False)
    
    
elif preset=="start_subtractive_print":
    #macro("G92 X0 Y0 Z0 E0","ok",3,"Setting Origin Point",1)
    #macro("G90","ok",3,"Setting Origin Point",0.1, verbose=False)
    macro("M92 E"+str(units['a']),"ok",1,"Setting 4th Axis mode",0.1,verbose=False)
    
    
elif preset=="end_print_subtractive":
    serial.flush()
    trace("Terminating...",log_trace)
    macro("G27","ok",100,"Lowering the building platform",1,verbose=False) #normally Z=240mm
    #note: movement here is done so it works with manual positioning (subtractive mode).
    macro("M5","ok",100,"Shutting Down Milling Motor",1,verbose=False) #should be moved to firmware    
    macro("M220 S100","ok",50,"Reset Speed factor override",0.1,verbose=False)
    macro("M221 S100","ok",1,"Reset Extruder factor override",0.1,verbose=False)
    macro("M107","ok",50,"Turning Fan off",1,verbose=False) #should be moved to firmware
    macro("M18","ok",10,"Motor Off",1,verbose=False) #should be moved to firmware
    macro("M728","ok",10,"Completed!",0.1) #should be moved to firmware
    trace("Completed",log_trace)
    
elif preset=="end_print_additive":
    serial.flush()
    #note: movement here is done so it works with AUTO positioning (additive mode).
    trace("Terminating...",log_trace)
    #macro("G90","ok",100,"Set Absolute movement",0.1,verbose=False)
    #macro("G90","ok",2,"Set Absolute movement",1)
    #macro("G0 X210 Y210 Z200 F10000","ok",100,"Moving to safe zone",0.1,verbose=False) #right top, normally Z=240mm
    macro("M104 S0","ok",50,"Shutting down Extruder",1)
    macro("M140 S0","ok",50,"Shutting down Heated Bed",1)
    macro("M220 S100","ok",20,"Reset Speed factor override",0.1)
    macro("M221 S100","ok",20,"Reset Extruder factor override",0.1,verbose=False)
    macro("M107","ok",50,"Turning Fan off",1) #should be moved to firmware
    macro("M18","ok",10,"Motor Off",1) #should be moved to firmware
    macro("M300","ok",1,"Done!",1,verbose=False) #end print signal
   
elif preset=="end_print_additive_safe_zone":
    serial.flush()
    macro("G90","ok", 2, "Setting Absolute position", 0)
    macro("G0 X210 Y210 Z200 F10000", "ok", 100, "Moving to safe zone", 1)
    
elif preset=="raise_bed":
    #for homing procedure before probe calibration and print without homing.
    macro("M402","ok",4,"Raising probe",0.1)
    macro("G90","ok",2,"Setting absolute position",1)
    
    #macro("G27 Z206","ok",100,"Homing all axes",0.1)
    #macro("G0 Z10 F10000","ok",15,"raising",0.1)
    #macro("G28","ok",100,"homing all axes",0.1)
    if zprobe_disabled:
        macro("G27 X0 Y0 Z" + str(zmax_home_pos),"ok",100,"Homing all axes",0.1)
        macro("G0 Z50 F10000","ok",15,"raising",0.1)
    else:
        macro("G27 Z206","ok",100,"Homing all axes",0.1)
        macro("G0 Z10 F10000","ok",15,"raising",0.1)
        macro("G28","ok",100,"homing all axes",0.1)
    
    
elif preset=="raise_bed_no_g27":
    #for homing procedure before probe calibration.
    macro("M402","ok",4,"Raising probe",0.1)
    macro("G90","ok",2,"Setting absolute position",1,verbose=False)
    
    #macro("G0 Z20 F10000","ok",15,"Raising bed",0.1,verbose=False)
    #macro("G28","ok",100,"Homing all axes",0.1)
    
    if zprobe_disabled:
        macro("G27 X0 Y0 Z" + str(zmax_home_pos),"ok",100,"Homing all axes",0.1)
        macro("G0 Z50 F10000","ok",15,"raising",0.1)
    else:
         macro("G0 Z20 F10000","ok",15,"Raising bed",0.1,verbose=False)
         macro("G28","ok",100,"Homing all axes",0.1)
        
    
        
#Auto bed leveling
elif preset=="auto_bed_leveling":
    trace("Auto Bed leveling Initialized",log_trace)
    macro("G91","ok",2,"Setting relative position",1,verbose=False)
    #macro("G0 X17 Y61.5","ok",1,"Offset",1)
    macro("G0 Z25","ok",2,"Moving away from the plane",1,verbose=False)
    macro("G90","ok",2,"Setting abs position",1,verbose=False)
    macro("G28","ok",90,"Homing all axis",1)
    macro("G29","ok",140,"Auto bed leveling procedure",1)
    macro("G0 X5 Y5 Z60 F2000","ok",100,"Getting to idle position",1)
    
#r_scan rotative scan preset
elif preset=="r_scan":
    trace("Initializing Rotative Laser scanner",log_trace)
    trace("Checking panel door status and bed inserted",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1,verbose=False)
    macro("M744","open",1,"Building plane (must be removed)",0.1)
    macro("M744","TRIGGERED",1,"Spool panel closed",0.1, warning=True)
    macro("M701 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("M702 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("M703 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("G90","ok",2,"Setting Absloute position",1,verbose=False)
    macro("G0 X96 Y175 Z135 E0 F10000","ok",90,"Moving to collimation position",1)
    macro("M302 S0","ok",2,"Enabling cold extrusion",0,verbose=False)
    #macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0)
    
elif preset=="pg_scan":
    trace("Initializing Rotative Laser scanner",log_trace)
    trace("Checking panel door status and bed inserted",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1,verbose=False)
    macro("M744","open",1,"Building plane (must be removed)",0.1)
    macro("M744","TRIGGERED",1,"Spool panel closed",0.1, warning=True)
    macro("M701 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("M702 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("M703 S255","ok",2,"Turning off lights",0.1,verbose=False)
    macro("G90","ok",2,"Setting Absloute position",1,verbose=False)
    macro("G0 X96 Y175 Z135 E0 F10000","ok",90,"Moving to collimation position",1)
    macro("M302 S0","ok",2,"Enabling cold extrusion",0,verbose=False)
    
#s_scan preset
elif preset=="s_scan":
    trace("Initializing Sweeping Laserscanner",log_trace)
    trace ("checking panel door status and bed inserted",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1)
    macro("M744","open",2,"Building plane removed!",0.1,warning=True)
    macro("M744","TRIGGERED",1,"Spool panel is not closed!",0.1, warning=True)
    macro("M701 S0","ok",2,"Turning off lights",0.1)
    macro("M702 S0","ok",2,"Turning off lights",0.1)
    macro("M703 S0","ok",2,"Turning off lights",0.1)
    #macro("M744","open",2,"Working plane absent/tilted",0.1)
    macro("G28 X0 Y0","ok",90,"Homing all axis",1)
    macro("G90","ok",2,"Setting Absolute position",0)
    #macro("M92 E"+str(units['a']),"ok",1,"Setting 4th axis mode",0)
    macro("G0 Z145 F1000","ok",90,"Lowering the plane",1)
#p_scan preset
elif preset=="p_scan":
    trace("Initializing Probing procedure",log_trace)
    if(safety_door == 1):
        macro("M741","TRIGGERED",2,"Front panel door control",0.1,warning=True)
    macro("M402","ok",2,"Raising Probe",0)
    macro("M744","open",2,"Building plane is absent",0.1, warning=True)
    macro("M744","TRIGGERED",2,"Spool panel",0.1, warning=True)
    macro("G90","ok",2,"Setting Absolute position",0, verbose=False)
    macro("M302 S0","ok",2,"Disabling cold extrusion prevention",0,verbose=False)
    macro("M92 E"+str(units['a']),"ok",2,"Setting 4th axis mode",0,verbose=False)
    
elif preset=="end_scan":
    trace("Terminating digitalization procedure",log_trace)
    macro("G90","ok",100,"Setting Absolute position",0) #long waiting time
    macro("G0 Z140 E0 F5000","ok",35,"Rasing",0.1)
    macro("M402","ok",100,"Disabling probe",0)
    macro("M700 S0","ok",3,"Shutting Down Laser",0)
    macro("M18","ok",3,"Motor Off",1) #should be moved to firmware
    #go back to user-defined colors
    macro("M701 S"+str(units['color']['r']),"ok",2,"Turning on lights",0.1,verbose=False)
    macro("M702 S"+str(units['color']['g']),"ok",2,"Turning on lights",0.1,verbose=False)
    macro("M703 S"+str(units['color']['b']),"ok",2,"Turning on lights",0.1,verbose=False)
    macro("M300","ok",1,"Scan completed",1,verbose=False)
    
#zero_all
elif preset=="home_all":
    trace("Now homing all axes",log_trace)
    macro("G90","ok",2,"set abs position",0,verbose=False)
    
    #macro("G28","ok",100,"homing all axes",1,verbose=False)
    if zprobe_disabled:
        print "Z probe disabled"
        macro("G27 X0 Y0 Z" + str(zmax_home_pos),"ok",100,"Homing all axes",0.1)
        macro("G0 Z50 F10000","ok",15,"raising",0.1)
    else:
        macro("G28","ok",100,"homing all axes",1,verbose=False)

#unload spool    
elif preset=="unload_spool":
    
    trace("Unloading Spool : Procedure Started.",log_trace)
    macro("G90","ok",10,"set abs position",0,verbose=False)
    macro("G27 Z206","ok",100,"zeroing Z axis",1,verbose=False)
    macro("G0 Z150 F10000","ok",10,"Moving to safe zone",0.1,verbose=False) #right top corner Z=150mm
    macro("M83","ok",5,"setting relative estrusion",0.1,verbose=False)
    macro("G92 E0","ok",5,"set extruder to zero",0.1,verbose=False)
    macro("M92 E"+str(units['e']),"ok",30,"setting extruder mode",0.1,verbose=False)
    #heating nozzle
    #macro("M109 S220","ok",150,"Heating Nozzle... Get ready to pull the filament gently...",1)
    macro("M300","ok",2,"Start Pulling!",3)
    #macro("G0 E-50 F350","ok",10,"Leaving the hotend (slow!)",1, verbose=False)
    macro("G0 E-800 F550","ok",10,"Expelling filament",1)
    macro("G0 E-200 F550","ok",10,"Expelling filament",1, verbose=False)
    time.sleep(90)
    macro("M82","ok",2,"Restoring absolute estrusion mode",0,verbose=False)
    macro("M104 S0","ok",1,"Disabling Extruder",1)
    trace("Done!",log_trace)
    
#load spool from reel
elif preset=="load_spool":
    trace("Loading Spool : Procedure Started.",log_trace)
    macro("G90","ok",2,"set abs position",0)
    macro("G27 Z206","ok",100,"zeroing Z axis",1)
    macro("G0 Z150 F10000","ok",10,"Moving to Safe Zone",0.1)
    macro("M302 S0","ok",5,"Enabling Cold extrusion",0.1,verbose=False)
    macro("M83","ok",5,"setting relative estrusion mode",0.1,verbose=False)
    macro("G92 E0","ok",5,"setting extruder position to 0",0.1,verbose=False)
    macro("M92 E"+str(units['e']),"ok",5,"Setting extruder mode",0.1,verbose=False)
    macro("M104 S190","ok",5,"Heating Nozzle. Get ready to push...",5) #heating and waiting.
    macro("M300","ok",5,"Start pushing!",3)
    
    macro("G0 E110 F500","ok",1,"Loading filament (slow)",15)
    macro("G0 E660 F700","ok",1,"Loading filament (fast)",20)
    macro("M109 S210","ok",100,"waiting to get to temperature...",1) #heating and waiting.
    
    macro("G0 E100 F200","ok",1,"Entering the hotend (slow)",1)
    
    macro("M82","ok",1,"setting back absolute estrusion",0,verbose=False)
    time.sleep(25)
    macro("M104 S0","ok",1,"Disabling Extruder",0.1)
    macro("M302 S170","ok",1,"Disabling Cold Extrusion Prevention",0.1,verbose=False)
    
#jog setup procedure
elif preset=="jog_setup":
    trace("Engaging Jog Mode",log_trace)
    macro("M92 E"+str(units['a']),"ok",1,"Set 4th axis mode",1)
    macro("G91","ok",1,"set relative movements",1)
    
#flash finalization procedure
elif preset=="start_up":
    trace("Starting up",log_trace)
    macro("M728","ok",2,"Alive!",1,verbose=False)
    macro("M701 S"+str(units['color']['r']),"ok",2,"turning on lights",0.1,verbose=False)
    macro("M702 S"+str(units['color']['g']),"ok",2,"turning on lights",0.1,verbose=False)
    macro("M703 S"+str(units['color']['b']),"ok",2,"turning on lights",0.1,verbose=False)
    try:
        safety_door = units['safety']['door']
    except KeyError:
        safety_door = 0
    macro("M732 S"+str(safety_door),"ok",2,"Safety Settings",0.1,verbose=False)
    
    try:
        switch = units['switch']
    except KeyError:
        switch = 0
    macro("M714 S"+str(switch),"ok",2,"Homing direction",0.1,verbose=False)
    
    
    
#shutdown
elif preset=="shutdown":
    macro("M300","ok",5,"play alert sound!",1, verbose=False)
    trace("shutting down",log_trace)
    macro("M729","ok",2,"Asleep!",0,verbose=False)
    macro("G4 S10","ok",10,"Shutting down!",1, verbose=False)
    macro("M300","ok",5,"play alert sound!",1, verbose=False)
    
#probe calibration
elif preset=="probe_setup_prepare":
    trace("Preparing Calibration procedure",log_trace)
    trace("This may take a wile",log_trace)
    macro("M104 S200","ok",90,"Heating extruder", 0.1, verbose=False)
    macro("M140 S45", "ok",90,"Heating Bed - fast ", 0.1,verbose=False)
    macro("G91","ok",2,"Relative mode",1, verbose=False)
    macro("G0 X17 Y61.5 F6000","ok",2,"Offset",1, verbose=False)
    macro("G90","ok",2,"Abs_mode",1, verbose=False)
    macro("G0 Z2 F1000","ok",2,"Moving to calibration position",1)
    
elif preset=="probe_setup_calibrate":
    trace("Calibrating probe",log_trace)
    macro("M104 S0","ok",90,"nozzle off",1,verbose=False)
    macro("M140 S0", "ok",90,"bed off ",1,verbose=False)
    
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
    macro("G90","ok",2,"Abs_mode",1, verbose=False)
    macro("G0 Z50 F1000","ok",3,"Moving the plane ",1,verbose=False )
    macro("G28","ok",90,"homing all axis",verbose=False)
    trace("Probe calibrated : "+str(z_probe_new)+" mm",log_trace)
    macro("M300","ok",5,"Done!")
    
elif preset=="laser":
    trace("Checking Laser",log_trace)
    macro("M700 S255","ok",3,"Turning On Laser",0)
    time.sleep(3)
    macro("M700 S0","ok",3,"Shuttind Down Laser",0)
    
elif preset=="mill":
    trace("Brushless motor test", log_trace)
    time.sleep(1);
    macro("M3 S5000", "ok", 5, "RPM 5000", 0)
    time.sleep(1);
    macro("M3 S7000", "ok", 5, "RPM 7000", 0)
    time.sleep(1);
    macro("M3 S9000", "ok", 5, "RPM 9000", 0)
    time.sleep(1);
    macro("M3 S12000", "ok", 5, "RPM 12000", 0)
    time.sleep(1);
    macro("M3 S14000", "ok", 5, "RPM 14000", 0)
    time.sleep(1);
    macro("M5", "ok", 5, "Motor Off", 0)
    
elif preset=="blower":
    trace("Start blower test", log_trace);
    time.sleep(1);
    macro("M106 S255","ok",50,"Turning Blower On",1)
    time.sleep(5);
    macro("M106 S0","ok",50,"Turning Blower Off",1)
    
elif preset=="head_light":
    trace("Start head light test", log_trace);
    time.sleep(1);
    macro("M706 S255","ok",50,"Turning Head Light On",1)
    time.sleep(5);
    macro("M706 S0","ok",50,"Turning Head Light Off",1)

elif preset=="end_stop":
    trace("Start End Stop Test", log_trace)
    time.sleep(1)
    serial.flushInput()
    serial.write("M119\r\n")
    serial_reply=serial.read(1024)
    trace(serial_reply, log_trace)
    #print serial_reply.split('\n');
    time.sleep(2)

elif preset=="g28":
    trace("Start home Test", log_trace)
    macro("G28","ok",100,"homing all axes",1,verbose=False)
    
elif preset=="probe_down":
    macro("M401","ok",1,"Probe Down",0)
elif preset=="probe_up":
    macro("M402","ok",1,"Probe Up",0)
     
elif preset == "temperature":
    trace("Temperature Test", log_trace)
    time.sleep(1)
    serial.flushInput()
    serial.write("M105\r\n")
    time.sleep(1)
    serial_reply=serial.readline().rstrip()
    if len(serial_reply)>5:
        
        ext_temp=serial_reply.split( )[1]
        ext_temp=ext_temp.split(":")[1]
        bed_temp=serial_reply.split( )[3]
        bed_temp=bed_temp.split(":")[1]
        
        #ext_temp = int(ext_temp)
        #bed_temp = int(bed_temp)
        
        trace("Nozzle temperature " + str(ext_temp), log_trace)
        #trace("Bed temperature " + str(bed_temp), log_trace)
        
        new_ext_temp = float(ext_temp) + 20
        new_bed_temp = float(bed_temp) + 20
        
        macro("M104 S" + str(new_ext_temp), "ok", 5, "Nozzle temperature set to " + str(new_ext_temp), 0)
        #macro("M140 S" + str(new_bed_temp), "ok", 5, "Bed temperature set to " + str(new_bed_temp), 0)
        
        trace("Waiting temperatures..", log_trace)
        time.sleep(10)
        
        serial.flushInput()
        serial.write("M105\r\n")
        time.sleep(1)
        serial_reply=serial.readline().rstrip()
        
        ext_temp=serial_reply.split( )[1]
        ext_temp=ext_temp.split(":")[1]
        bed_temp=serial_reply.split( )[3]
        bed_temp=bed_temp.split(":")[1]
        
        trace("Nozzle temperature " + str(ext_temp), log_trace)
        #trace("Bed temperature " + str(bed_temp), log_trace)
    
        macro("M104 S60","ok",5,"",1, verbose=False)
        #macro("M140 S30","ok",5,"",1, verbose=False)
        
    else:
        ext_temp=-1
        bed_temp=-1
        trace("FAILURE : Sensors unresponsive",log_trace)
        s_error+=1

elif preset=="pre_unload_spool":
    
    serial.flushInput()
    serial.write("M105\r\n")
    time.sleep(1)
    serial_reply=serial.readline().rstrip()
    ext_temp=serial_reply.split( )[1]
    ext_temp=ext_temp.split(":")[1]
    
    if(float(ext_temp) < 100):
        time_to_wait = 60
    elif(float(ext_temp) > 100 and float(ext_temp) < 150 ):
        time_to_wait = 30
    elif(float(ext_temp) > 150 and float(ext_temp) < 190 ):
        time_to_wait = 10
    elif(float(ext_temp) > 190):
        time_to_wait = 0
        
    macro("M104 S190","ok", 5,"Heating Nozzle...", time_to_wait, verbose=True) #heating and waiting.    
        
if s_error>0:
    response("false")
    trace(str(s_error) + " Error(s) occurred",log_trace)
    trace(str(s_skipped) + " operation(s) have been skipped due to errors." ,log_trace)
    trace("<b>Try Again!</b>", log_trace )
else:
    response("true")
    trace("All clear!",log_trace)
    
#clean the buffer and leave
serial.flush()
serial.close()
write_status(False)
#open(trace_file, 'w').close() #reset trace file
sys.exit()
