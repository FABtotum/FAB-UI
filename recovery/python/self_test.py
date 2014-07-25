#self_test.py 
import sys
import time, datetime
import serial
import json
from subprocess import call

#read config units from config
json_f = open("/var/www/myfabtotum/config/units.json")
units = json.load(json_f)

#process params
log_trace=str(sys.argv[1]) #param for the log file

#generic errors
s_error=0

#track trace
def trace(string,destination_file):
	print string
	out_file = open(destination_file,"a+")
	out_file.write(str(string) + "\n")
	out_file.close()
	return

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
				trace("Failed : "+error_msg,log_trace)
				s_error+=1
			#print "error!"
			return False #leave the function
			
		serial_reply=serial.readline().rstrip()
		#print serial_reply
		#add safety timeout
		time.sleep(0.1) #no hammering 
		pass

	trace(error_msg + ": Done!",log_trace)	
	time.sleep(delay_after) #wait the desired amount
	return serial_reply

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
# 7 - the carriage and the Z axis are moved to a safe shipping position.

trace("Starting self test",log_trace)
macro("M741","TRIGGERED",1,"Front door switch test",0.5)
macro("M744","TRIGGERED",1,"Building plane recognition test",0.5)

# PROCEDURE STEP 1
# 1 - Extruder turn on, reach temp, turns off
trace("Heating test starting...",log_trace)
serial.write("M104 S200\r\n")
serial.write("M140 S60\r\n")
time.sleep(60)
serial.flushInput()
serial.write("M105\r\n")
time.sleep(1)
serial_reply=serial.readline().rstrip()
#Collected M105: Get Extruder Temperature (reply)
#EXAMPLE:
#ok T:219.7 /220.0 B:26.3 /0.0 T0:219.7 /220.0 @:35 B@:0
ext_temp=serial_reply.split( )[1]
ext_temp=ext_temp.split(":")[1]

bed_temp=serial_reply.split( )[3]
bed_temp=bed_temp.split(":")[1]

if ext_temp>=190:
	trace("OK : Extruder heating(" + ext_temp +")",log_trace)
else:
	trace("Failed : Extruder heating (" + serial_reply + ")",log_trace)
	
# 2 - Bed turns On, reach temp, turns off

if bed_temp>=70:
	trace("OK : Bed heating(" + bed_temp +")",log_trace)
else:
	trace("Failed : Bed heating (" + serial_reply +")",log_trace)
	s_error+=1

#shutdown temps
serial.write("M104 S0\r\n") #shutdown extruder (fast)
serial.write("M140 S0\r\n") #shudown bed (fast)
	
# 3 - Milling motor turns on, off, RPM stress test.

trace("Brushless motor test...",log_trace)
serial.write("M3 S4000\r\n")
time.sleep(7)
for rpm in range(5,14):
	rpm*=1000
	trace("RPM: "+ str(rpm),log_trace)
	serial.write("M3 S"+str(rpm)+"\r\n")
	time.sleep(2)

serial.write("M5\r\n")		
trace("OK : Milling motor stress test",log_trace)
# 4 - Carriage assembly control:

# 	4a - Probe extension and retraction
trace("(visual confirmation) : Probe down",log_trace)
serial.write("M401\r\n") #probe down
time.sleep(5)
serial.write("M402\r\n") #probe up
time.sleep(1)

#   4b - Light on/off
trace("(visual confirm) : Light test",log_trace)
serial.write("M706 S255\r\n") #light on
time.sleep(5)
serial.write("M706 S0\r\n") #light off
time.sleep(5)
trace("OK : Head Light test",log_trace)

#   4c - Laser on/off
trace("(visual confirm) : Laser test",log_trace)
serial.write('M700 S255\r\n')# Turn laser off
time.sleep(5)
serial.write('M700 S0\r\n')# Turn laser off
trace("OK : Laser test",log_trace)

#   4d - Ambient Lightning
# M701 S[0-255] - Ambient Light, Set Red
# M702 S[0-255] - Ambient Light, Set Green
# M703 S[0-255] - Ambient Light, Set Blue

trace("(visual confirm) Now testing ambient light...",log_trace)
time.sleep(4);
serial.write('M701 S255\r\n')# Turn Red on
time.sleep(3)
serial.write('M701 S0\r\n')# Turn Red off
serial.write('M702 S255\r\n')# Turn green on
time.sleep(3)
serial.write('M702 S0\r\n')# Turn green off
serial.write('M702 S255\r\n')# Turn green on
time.sleep(3)
serial.write('M702 S0\r\n')# Turn green off
serial.write('M703 S255\r\n')# Turn blue on
time.sleep(3)
serial.write('M703 S0\r\n')# Turn blue off

serial.write('M701 S255\r\n')# Turn blue on
serial.write('M702 S255\r\n')# Turn blue on
serial.write('M703 S255\r\n')# Turn blue on
trace("OK : Ambient light test",log_trace)
time.sleep(3)
serial.write('M701 S0\r\n')# Turn red off
serial.write('M702 S0\r\n')# Turn green off
serial.write('M703 S0\r\n')# Turn blue off

# 5 - XYZ movement testing procedure.
#	5a - homing
macro("G28","ok",1,"Homing procedure",10)
#	5b - speed test
#EXEC A GCODE

#	5c - Feeder test
#serial.write('G0 E+50\r\n')
#serial.write('G0 E-50\r\n')

# 6 - Test print of a sample file.

# 7 - the carriage and the Z axis are moved to a safe shipping position.
trace("------------------------------",log_trace)
trace("-----------COMPLETED----------",log_trace)
trace("------------------------------",log_trace)

trace("Errors:" + str(s_error),log_trace)
	
if s_error==0:
	trace("true",log_trace)
else:
	trace("false",log_trace)

#clean the buffer and leave
serial.flush()
serial.close()

sys.exit()