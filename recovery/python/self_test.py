#self_test.py 
import sys, os
import termios
import tty
import time, datetime
import serial
import json
from subprocess import call
import requests  #run   sudo pip install requests if needed 

#read config units from config
json_f = open("/var/www/fabui/config/config.json")
units = json.load(json_f)

#process params
log_trace=str(sys.argv[1]) #param for the log file
status_json=str(sys.argv[2])
online=int(sys.argv[3]) #for online log
try:
	calibration=int(sys.argv[4]) # manual calibration
except:
	calibration=0
try:
	task_id=int(sys.argv[5])	
except:
	task_id=0
try:
	fabui_version=str(sys.argv[6])
except:
	fabui_version='not avaiable'
	
	
	
trace_msg=""
#generic errors
s_error=0
ckey=""


def write_json(value, json):
    str_log='{"finish": ' + value +'}'
    handle=open(json,'w+')
    print>>handle, str_log
    return

write_json('0', status_json)

#track trace
def trace(string,destination_file):
	print string
	out_file = open(destination_file,"a+")
	out_file.write(str(string) + "\n")
	out_file.close()
	return

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
	
#gcode macro exec
def macro(code,expected_reply,timeout,error_msg,delay_after):
	global s_error	
	serial_reply=""
	macro_start_time = time.time()
	serial.write(code+"\r\n")
	time.sleep(0.3) #give it some toie to start  
	while not (serial_reply==expected_reply or serial_reply[:4]==expected_reply):
		#Expected reply
		#no reply:
		if (time.time()>=macro_start_time+timeout):
			#trace_msg="failed macro (timeout):"+ code+ " expected "+ expected_reply+ ", got:"+ serial_reply
			trace(trace_msg,log_trace)
			if not error_msg=="":
				trace("FAILED : "+error_msg,log_trace)
				s_error+=1
				summary()
			#print "error!"
			return False #leave the function
			
		serial_reply=serial.readline().rstrip()
		#print serial_reply
		#add safety timeout
		time.sleep(0.1) #no hammering 
		pass

	trace("OK : " + error_msg,log_trace)	
	time.sleep(delay_after) #wait the desired amount
	return serial_reply

def wait_key(msg,key):
	print msg
	while str(getKey())!=str(key):
		pass
	return
	
def getKey():
   fd = sys.stdin.fileno()
   old = termios.tcgetattr(fd)
   new = termios.tcgetattr(fd)
   new[3] = new[3] & ~termios.ICANON & ~termios.ECHO
   new[6][termios.VMIN] = 1
   new[6][termios.VTIME] = 0
   termios.tcsetattr(fd, termios.TCSANOW, new)
   key = None
   try:
      key = os.read(fd, 3)
   finally:
      termios.tcsetattr(fd, termios.TCSAFLUSH, old)
   return key

def summary():
	trace("SUMMARY:",log_trace)
	trace("Errors  : " + str(s_error),log_trace)

	if s_error==0:
		trace("Result  : No errors occurred.",log_trace)
	else:
		trace("Self Test FAILED",log_trace)


	if online==1:
		print "Saving results on remote server"
		url = 'http://plm.fabtotum.com/reports/add_report.php'
		files = {'file': open(log_trace, 'rb')}
		info={'ID':controller_serial_id, 'result':s_error}
		try:
			r = requests.post(url,info, files=files)
			trace("Self Test results saved online",log_trace)
		except:
			#print "Response: " + r.text
			trace("Could not contact remote support server, is Internet connectivity available?",log_trace)
	if s_error>0:
		#clean the buffer and leave
		#shutdown temps
		
		serial.write("M104 S0\r\n") #shutdown extruder (fast)
		serial.write("M140 S0\r\n") #shudown bed (fast)
		serial.flush()
		serial.close()
		write_json('1', status_json)
		call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" self_test"], shell=True)

		sys.exit()

 
port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.6)
serial.flushInput()

# 1 - Extruder turn on, reach temp, turns off
# 2 - Bed turns On, reach temp, turns off
# 3 - Milling motor turns on, off, RPM stress test.
# 4 - Carriage assembly control:
# 	4a - Probe extension and retraction
#   4b - Light on/off
#   4c - Laser on/off
#   4d - Laser on/off
# 5 - XYZ movement testing procedure.
#	5a - homing
#	5b - speed test
#	5c - Feeder test
# 6 - Test print of a sample file.

#import curses
#while True:
	#win = curses.initscr()
	#key = win.getch() 
	#print key
	
# PROCEDURE STEP 1

#pre-test-1: gather components informations
trace("SYSTEM INFORMATIONS:",log_trace)

trace("FAB UI version:" + fabui_version, log_trace);
# M760 - read FABtotum Personal Fabricator Main Controller serial ID
controller_serial_id=read_serial("M760")
trace("Controller ID     : "+ str(controller_serial_id),log_trace)

# M761 - read FABtotum Personal Fabricator Main Controller control code of serial ID
controller_serial_id_control=read_serial("M761")
	
# M763 - read FABtotum Personal Fabricator Main Controller control code of production batch number
controller_board_revision=read_serial("M762")

# M763 - read FABtotum Personal Fabricator Main Controller production batch number
controller_batch_num=read_serial("M763")
trace("Batch Num         : "+ str(controller_batch_num),log_trace)


# M765 - read FABtotum Personal Fabricator Firmware Version
fw_version=read_serial("M765")
trace("Firmware Version  : "+ str(fw_version),log_trace)

# M766 - read FABtotum Personal Fabricator Firmware build date
fw_date=read_serial("M766")
trace("Firmware build date  : "+ str(fw_date),log_trace)

# M784 - read FABtotum Personal Fabricator head ID
head_serial=read_serial("M784")
trace("Head Serial Id  : "+ str(head_serial),log_trace)


if str(fw_version)=="NONE":
	s_error+=1
	trace("-------------------------------------------------------\n",log_trace)
	trace("FAIL : FABtotum controller not responding",log_trace)
	trace("FAIL : CANNOT IDENTIFY THE UNIT",log_trace)
	trace("-------------------------------------------------------\n",log_trace)
	summary()

	
trace("-------------------------------------------------------\n",log_trace)


#pre test-2: check safety measures
macro("M741","TRIGGERED",1,"Front door switch",0.5)
macro("M744","TRIGGERED",1,"Building plane recognition",0.5)

#test m119
#Reporting endstop status
#x_min: open
#x_max: open
#y_min: open
#y_max: open
#z_min: open
#z_max: open

#trigger_reply=read_serial("M119")
#triggers=trigger_reply.count('open')
#if triggers<6:
#	trace("CRITICAL: only "+str(triggers)+"/6 endstops functioning",log_trace)
#	s_error+=1
#	summary()
#else:
#	trace("Endstop triggers",log_trace)

trace("[V] Safety Measures:                                ENGAGED",log_trace)
trace("-------------------------------------------------------\n",log_trace)



#pre-test-3: check PSU DONE values:
trace("POWER SUPPLY:",log_trace)

# M751 - read voltage monitor 24VDC input supply (ANALOG mV)
#		Allowed tolerance +/- 20%
voltage_24vdc=read_serial("M751")
try:
	voltage_24vdc=voltage_24vdc.split(":")[1]
	voltage_24vdc=float(voltage_24vdc.split(' ')[0])
except:
	trace("Error fetching value: "+ str(voltage_24vdc))
	s_error+=1
	summary()
	

if voltage_24vdc>19.200 and voltage_24vdc<28.800:
	trace("[V] PSU Voltage nominal 24V DC +/-20%: "+ str(voltage_24vdc) + " V",log_trace)
else:
	trace("CRITICAL: PSU Voltage anomaly. Expected 24V DC +/-20%, got : "+ str(voltage_24vdc) + " V",log_trace)
	s_error+=1
	summary() #self test has detected a big problem! exit!
	
# M752 - read voltage monitor 5VDC input supply (ANALOG mV) 
#		Allowed tolerance	+/- 20% .
voltage_5vdc=read_serial("M752")

try:
	voltage_5vdc=voltage_5vdc.split(":")[1]
	voltage_5vdc=float(voltage_5vdc.split(' ')[0])
except:
	trace("Error fetching value: "+ str(voltage_5vdc))
	s_error+=1
	summary()

if voltage_5vdc<6.000 and voltage_5vdc>4.000:
	trace("[V] 5V DC Power Supply is Nominal (+/-20% tolerance): "+ str(voltage_5vdc) + " V",log_trace)
else:
	trace("CRITICAL: 5V DC Power Supply anomaly. Expected 5V DC +/-20%, got : "+ str(voltage_5vdc) + " V",log_trace)
	s_error+=1
	summary() #self test has detected a big problem! exit!

# M753 - read current monitor input supply (ANALOG mA)	
#	    Must be < 500ma when nothing is running.
current_supply=read_serial("M753")
try:
	current_supply=current_supply.split(":")[1]
	current_supply=float(current_supply.split(' ')[0])
except:
	trace("Error fetching value: "+ str(current_supply))
	s_error+=1
	summary()

if current_supply<0.5:
	trace("[V] Power consumption is Nominal : "+ str(current_supply) + "< 500 mA",log_trace)
else:
	trace("CRITICAL: Power consumption Anomaly : "+ str(current_supply) + " A , expected <500 mA",log_trace)
	s_error+=1
	summary() #self test has detected a big problem! exit!
	
trace("Power supply status:                            DONE",log_trace)
trace("-------------------------------------------------------\n",log_trace)



#0 - probe calibration routine
if calibration==1:
	print "----------------------------"
	print "--Manual Probe Calibration--"
	print "----------------------------"
	
	probe_engaged_pos=127
	probe_idle_pos=26
	p_set=False
	serial.write("M401\r\n")
	print "Set lowered position, Press N,M to move Y to save"
	while ckey!=str("y"):
		if ckey==str("n") or ckey==str("N"):
			probe_engaged_pos-=1
			p_set=True
		if ckey==str("m") or ckey==str("M"):
			probe_engaged_pos+=1
			p_set=True
		if p_set:
			print "Moving to "+str(probe_engaged_pos)
			serial.write("M711 S"+str(probe_engaged_pos)+"\r\n")
			serial.write("M401\r\n")
			p_set=False
			
		ckey=str(getKey())
		time.sleep(0.1)
		pass
	trace("Engaged position saved at ["+str(probe_engaged_pos)+"]",log_trace)
	p_set=False
	
	time.sleep(1)
	
	ckey=""
	serial.write("M402\r\n")
	print "Set idle position, Press N,M to move Y to save"
	while ckey!=str("y"):
		if ckey==str("n") or ckey==str("N"):
			probe_idle_pos-=1
			p_set=True
		if ckey==str("m") or ckey==str("M"):
			probe_idle_pos+=1
			p_set=True
		if p_set:
			print "Moving to "+str(probe_idle_pos)
			serial.write("M712 S"+str(probe_idle_pos)+"\r\n")
			serial.write("M402\r\n")
			p_set=False
			
		ckey=str(getKey())
		time.sleep(0.1)
		pass
	trace("Engaged position saved at ["+str(probe_idle_pos)+"]",log_trace)
	p_set=False
	
	#---

#0/A - probe calibration routine, probe-nozzle height, laser etc.

if calibration==1:
	print "\n\n--------------------------------------------------------"
	print "--Automatic Probe-Nozzle Height difference measurement--"
	print "--------------------------------------------------------\n"
	print "Connect the nozzle and the probe accordingly, then press Y."
	wait_key("Press Y to continue ","y")
	
	serial.write("M713\r\n")
	print("now executing...")
	time.sleep(10)
	trace("COMPLETED : Nozzle height calibration",log_trace)
		
	wait_key("Disconnect everything, then press Y to continue.","y")

	print "\n\n--------------------------------------------------------"
	print "---------------------Laser Alignment--------------------"
	print "--------------------------------------------------------\n"
	print "ALIGN LASER, press Y when done. (M/N: move X axis  , A,Z move Z axis"

	
	ckey=""
	z_set=False
	l_set=False
	
	serial.write('G28 X0 Y0\r\n')# HOME XY
	time.sleep(3)
	serial.write('G0 X10 Y120\r\n')# Y middle pos
	time.sleep(1)
	serial.write('G91\r\n')# Y middle pos
	time.sleep(5)
	print "Ready, enter your comands:"
	serial.write('M700 S255\r\n')# Turn laser on
	while ckey!=str("y"):
		if ckey==str("n") or ckey==str("N"):
			xpos=-1
			l_set=True
		if ckey==str("m") or ckey==str("M"):
			xpos=1
			l_set=True
			
		if ckey==str("a") or ckey==str("A"):
			zpos=-10
			z_set=True
		if ckey==str("z") or ckey==str("Z"):
			zpos=+10
			z_set=True
		
		if z_set:
			print "Moving Z: "+str(zpos)
			serial.write("G0 Z"+str(zpos)+"\r\n")
			z_set=False
			
		if l_set:
			print "Moving X: "+str(xpos)
			serial.write("G0 X"+str(xpos)+"\r\n")
			l_set=False
			
		ckey=str(getKey())
		time.sleep(0.1)
		pass

	serial.write('M700 S0\r\n')# Turn laser off
	trace("[V] Laser line manually aligned",log_trace)
	p_set=False
			
	time.sleep(2)

	
# 1 - Extruder turn on, reach temp, turns off

#pretest
trace("Temperature Sensors Pre-test:",log_trace)
serial.flushInput()
serial.write("M105\r\n")
time.sleep(1)
serial_reply=serial.readline().rstrip()
if len(serial_reply)>5:
	ext_temp=serial_reply.split( )[1]
	ext_temp=ext_temp.split(":")[1]
	bed_temp=serial_reply.split( )[3]
	bed_temp=bed_temp.split(":")[1]
else:
	ext_temp=-1
	bed_temp=-1
	trace("FAILURE : Extruder Sensor unresponsive",log_trace)
	s_error+=1
	summary() #self test has detected a big problem! exit!

if float(ext_temp)>0:
	trace("OK : Extruder sensor (" + ext_temp +" ) responding correctly",log_trace)
else:
	trace("FAILED : Extruder sensor disconnected (" + ext_temp + ") ",log_trace)
	summary()
	
if float(bed_temp)>0: 
	trace("OK : Bed sensor("+ str(bed_temp) +") reponding correctly",log_trace)
else:
	trace("FAILED : Bed not positioned or sensor not responding " + str(bed_temp)+")",log_trace)
	s_error+=1
	summary()	
	
#end pre-test
ext_temp=-1
bed_temp=-1

temperature_timeout=60
trace("HEATING TEST (temperature in "+str(temperature_timeout)+" seconds): " ,log_trace)
serial.write("M104 S200\r\n") #set ext temp
serial.write("M140 S100\r\n") #set bed temp
time.sleep(temperature_timeout)
serial.flushInput()
serial.write("M105\r\n")
time.sleep(1)
serial_reply=serial.readline().rstrip()
#Collected M105: Get Extruder Temperature (reply)
#EXAMPLE:
#ok T:219.7 /220.0 B:26.3 /0.0 T0:219.7 /220.0 @:35 B@:0

if len(serial_reply)>5:
	ext_temp=serial_reply.split( )[1]
	ext_temp=ext_temp.split(":")[1]

	bed_temp=serial_reply.split( )[3]
	bed_temp=bed_temp.split(":")[1]
else:
	ext_temp=-1
	bed_temp=-1
	trace("FAILURE : Extruder Sensor not responding after "+str(temperature_timeout)+" seconds )",log_trace)
	s_error+=1
	summary() #self test has detected a big problem! exit!

if ext_temp>=190:
	trace("OK : Extruder heating(" + ext_temp +" in "+str(temperature_timeout)+" seconds )",log_trace)
else:
	trace("FAILED : Extruder heating (" + serial_reply + ") took more than "+str(temperature_timeout)+" seconds",log_trace)
	summary()
	
# 2 - Bed turns On, reach temp, turns off

if float(bed_temp)>=50: #reach this at least in 60 seconds
	trace("OK : Bed heating("+ str(bed_temp) +") in "+str(temperature_timeout)+" seconds" + serial_reply,log_trace)
else:
	trace("FAILED : Bed heating took more than "+str(temperature_timeout)+" seconds ( currently : " + str(bed_temp)+")",log_trace)
	s_error+=1
	summary()
	
#shutdown temps
serial.write("M104 S0\r\n") #shutdown extruder (fast)
serial.write("M140 S0\r\n") #shudown bed (fast)

trace("HEATING SYSTEMS:                                DONE",log_trace)
trace("-------------------------------------------------------\n",log_trace)
	
# 3 - Milling motor turns on, off, RPM stress test.

trace("BRUSHLESS MOTOR TEST:",log_trace)
serial.write("M3 S5000\r\n")
time.sleep(7)
result="RPM: "
for rpm in range(6,14):
	rpm*=1000
	print str(rpm) + ": OK..."
	serial.write("M3 S"+str(rpm)+"\r\n")
	time.sleep(2)

serial.write("M5\r\n")		#turn motor off
trace("MILLING MOTOR:                                  DONE",log_trace)
trace("-------------------------------------------------------\n",log_trace)

# 4 - Carriage assembly control:

trace("CARRIAGE FUNCTIONALITY TEST:",log_trace)
# 	4a - Probe extension and retraction
serial.write("G91\r\n") #relative mode
serial.write("G0 Z+40\r\n") #min distance
serial.write("G90\r\n") #abs mode

	
trace("[V] : Probe down (visual confirmation)",log_trace)
serial.write("M401\r\n") #probe down

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(5)
	
trace("[V] : Probe up (visual confirmation)",log_trace)
serial.write("M402\r\n") #probe up

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)

#   4b - Light on/off
trace("[V] : Head light (visual confirmation)",log_trace)
serial.write("M706 S255\r\n") #light on

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(5)

trace("[V] : Head light (visual confirmation)",log_trace)
serial.write("M706 S0\r\n") #light off

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(5)

#   4c - Laser on/off
trace("[V] : Laser Line Generator ON (visual confirmation)",log_trace)
serial.write('M700 S255\r\n')# Turn laser on

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(5)
	
trace("[V] : Laser Line Generator OFF (visual confirmation)",log_trace)
serial.write('M700 S0\r\n')# Turn laser off

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)

#   4d - Ambient Lightning
# M701 S[0-255] - Ambient Light, Set Red
# M702 S[0-255] - Ambient Light, Set Green
# M703 S[0-255] - Ambient Light, Set Blue

serial.write('M701 S0\r\n')# Turn red off
serial.write('M702 S0\r\n')# Turn green off
serial.write('M703 S0\r\n')# Turn blue off

time.sleep(1);

serial.write('M701 S0\r\n')# Turn Red off
serial.write('M702 S0\r\n')# Turn Green off
serial.write('M703 S0\r\n')# Turn Blue off

trace("[V] : RGB ambient light test (visual confirmation)",log_trace)
time.sleep(1);
serial.write('M701 S255\r\n')# Turn Red on

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)
	
serial.write('M701 S0\r\n')# Turn Red off
serial.write('M702 S255\r\n')# Turn green on

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)
	
serial.write('M702 S0\r\n')# Turn green off
serial.write('M703 S255\r\n')# Turn blue on

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)
	
serial.write('M703 S0\r\n')# Turn blue off

serial.write('M701 S255\r\n')# Turn all on
serial.write('M702 S255\r\n')# Turn all on
serial.write('M703 S255\r\n')# Turn all on

trace("[V] : RGB ambient light OK (visual confirmation)",log_trace)


trace("CARRIAGE SYSTEMS:                               DONE",log_trace)
trace("-------------------------------------------------------\n",log_trace)


trace("MOVEMENT TEST:",log_trace)

if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(0)
	
# 5 - XYZ movement testing procedure.
#	5a - homing
macro("G28 X0 Y0","ok",1,"Homing procedure",10)
#	5b - speed test

#	5c - Feeder test

trace("[V] : FEEDER TEST",log_trace)
if calibration:
	wait_key("Press Y to continue","y")
	serial.write('G91\r\n') #rel positioning
	serial.write('M302 S0\r\n') #disable cold extrusion safety
	serial.write('G0 E+200\r\n')
	serial.write('G0 E-200\r\n')
	time.sleep(5)
else:
	time.sleep(1)

# 6 - Test print // calibration print
#done manually


trace("\n",log_trace)
trace("MOVEMENT:                                     DONE",log_trace)
trace("-------------------------------------------------------\n",log_trace)


trace("Buzzer test // Alive comand )",log_trace)
if calibration:
	wait_key("Press Y to continue","y")
else:
	time.sleep(1)
serial.write('M728\r\n')# Alive!
	

trace("-------------------------------------------------------\n",log_trace)


#####COMPLETE: CALL SUMMARY#########
summary()

#close all and leave.
write_json('1', status_json)
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" self_test"], shell=True)
sys.exit()