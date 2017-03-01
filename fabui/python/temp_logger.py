import serial
import time
import sys

target=str(sys.argv[1])
log_trace=str(sys.argv[2])

start=time.time()
ext_temp=0

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.6)
serial.flushInput()
started=False
elapsed=0
timeout=120
temp_reached=False

def trace(string,destination_file):
	out_file = open(destination_file,"a+")
	out_file.write(str(string) +"\n")
	out_file.close()
	print string
	return

print "starting: aiming for " +target+ " degrees..."
while timeout>=0:
	elapsed=time.time()-start
	serial.write("M105\r\n")
	if elapsed>10 and not started:
		print "now starting heating to " + target+ " degrees"
		serial.write("M104 S"+target)
		started=True
	
	time.sleep(1)
	serial_reply=serial.readline().rstrip()
	
	#print serial_reply
	if serial_reply.startswith("ok T:"):
		ext_temp=serial_reply.split( )[1]
		ext_temp=ext_temp.split(":")[1]
		trace(ext_temp,log_trace)

	if serial_reply.startswith(" T:"):
		ext_temp=serial_reply[2:].split( )[0]
		trace(ext_temp,log_trace)
		
	if int(float(ext_temp))==int(float(target)) and not temp_reached:
		#completed cycle
		print "target reached, "+str(timeout) + " seconds to go..."
		temp_reached=True
		
	if temp_reached:
		timeout-=1
		
	serial.flushInput()

print "completed!"
sys.exit()