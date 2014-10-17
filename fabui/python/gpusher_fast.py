#gpusher
import os,sys,time
import serial
from threading import Thread
from subprocess import call

#process params
try:
	ncfile=str(sys.argv[1])  #param for the gcode to execute
	logfile=str(sys.argv[2]) #param for the log file
	comfile=str(sys.argv[3]) #comand file
except:
	print "\nERROR missing critical params. Usage:\n python gpusher.py gcode.nc logfile.log comfile.txt\n optional: log_trace.log FABUI_task_id[INT]"
	sys.exit()

try:
	# =str(sys.argv[4]) 	 #open slot
	log_trace=str(sys.argv[5])	#trace log file
	task_id=str(sys.argv[6])	#task ID	
except:
	print "running headless..."
	
#debug	
print os.getpid()

#ncfile ='/var/www/fabui/python/gcode.nc'
str_log=""
received=0
sent=0
ext_temp=bed_temp=ext_temp_target=bed_temp_target=0

#file
EOF=False
ovr_cmd=[]

#progress
lenght=0
percent=0
sent=0
resend=0
started=last_update=time.time()
completed_time=0
resent=0
completed=0

#overrides & controls
paused=False
shutdown=False #default shutdown printer on complete = no
killed=False  

	
def printlog(percent,sent):
	str_log='{"print":{"name": "'+ncfile+'","lines": "'+str(lenght)+'","started": "'+str(started)+'","paused": "'+str(paused)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","shutdown": "'+str(shutdown)+'","stats":{"percent":"'+str(percent)+'","line_number":'+str(sent)+',"extruder":'+str(ext_temp)+',"bed":'+str(bed_temp)+',"extruder_target":'+str(ext_temp_target)+',"bed_target":'+str(bed_temp_target)+'}}}'
	#write log
	handle=open(logfile,'w+')
	print>>handle, str_log
	return	

def trace(string):
	out_file = open(log_trace,"a+")
	out_file.write(str(string) + "\n")
	out_file.close()
	#headless
	print string
	return

def checksum(gcode,num):
	cs = 0
	gcode="N"+str(num)+" " + gcode
	for char in gcode:
		#print char
		cs=cs ^ ord(char)
	cs = cs & 0xff # Defensive programming...
	return cs

#OVERRIDE GCODE DESCRIPTION
def override_description(command):
	
	command_splitted = command.split()
	
	code= command_splitted[0]
	value= command_splitted[1]
	value=value.replace("S", "");
	
	description=""
	
	if code=="M104":
		description= "<strong>Extruder temperature set to "+value+" &deg;C</strong>"
	elif code== "M140":
		description= "<strong>Bed temperature set to "+value+" &deg;C</strong>"
	elif code=="M220":
		description="<strong>Speed set to "+value+"%</strong>"
	elif code=="M3":
		description="<strong>RPM speed set to "+value+"%</strong>"
	else:
		descritpion="description none"
	return description

	
#usage: print checksum(gcode,1)

def sender():
	global received
	global ncfile
	global sent
	global resend
	global ovr_cmd
	global EOF
	
	gcode_line=0
	with open(ncfile, 'r+') as f:
		# this reads in one line at a time
		trace("Print Started, now reaching temp")
		for line in f:
			if EOF==True:
				break
			line=line.rstrip()
			gcode_line+=1
			#print str(gcode_line)+ " -"+line+"-"
			if not(line=="" or line[:1]==";"):
				line=line.split(";")[0] #remove inline comm
				#line is not empty or comment
				while received<sent and sent>0 and EOF==False:
					pass #wait!
					
				if resend>0:
					#WIP
					trace ("Checksum error.")
			
				while len(ovr_cmd)>0 and received>=sent and EOF==False:
					#execute the override comand as priority
					override=ovr_cmd.pop(0)
				
					if override[:1]=="!":
						#if comand is non-serial comand
						
						if override=="!kill": #stop print
							trace("Terminating Process")
							#kill the process
							killed=True
							EOF=True
							break			
							
						if override=="!pause":
							if not paused:
								serial.write("G0 X200 Y200\r\n") #move in the corner
								trace("Print is now paused")
								paused=True
						
						if override=="!resume":
							if paused:
								trace("Resuming print")
								paused=False
											
						if override=="!shutdown_on":
							#will shutdown the machine after the print ends
							trace("Auto-Shutdown engaged")
							shutdown=True

						if override=="!shutdown_off":
							trace("Auto-Shutdown has been revoked")
							#will not shutdown the machine.
							shutdown=False
						
						

					else:
						#gcode is executed ASAP
						serial.write(override+"\r\n")
						if override[:4]!="M105": #do not report temperature requests!
							#trace("Override sent: "+ str(override))
							trace(override_description(override))
						sent+=1
						
				#Normal Gcode
				#if received>sent: #buffer is empty, can send next line
				serial.write(line+"\r\n")
				#print str(gcode_line)+" SENT "+ str(line)
				sent+=1
				
			else:
				print "skipping "+str(gcode_line) +" , "+ str(line)
	#print "Sender closed"				
	EOF=True
	
		

def listener():
	global received
	global sent
	global resend
	global EOF
	
	global ext_temp
	global bed_temp
	global ext_temp_target
	global bed_temp_target
	
	serial_in=""	
	while not EOF:
		while serial_in=="":
			serial_in=serial.readline().rstrip()
			#time.sleep(0.05)
			pass #wait!
		
		#if there is serial in:
		#parse actions:
		#print "rcv" + str(serial_in)
		
		##ok
		if serial_in=="ok":
			#print "received ok"
			received+=1
			#print "sent: "+str(sent) +" rec: " +str(received)

		##error
		if serial_in[:6]=="Resend":
			#resend line
			resend=serial_in.split(":")[1].rstrip()
			received-=1 #lost a line!
			trace("Error: Line no "+str(resend) + " has not been received correctly")
			
		##temperature report	
		if serial_in[:4]=="ok T":
			#Collected M105: Get Extruder & bed Temperature (reply)
			#EXAMPLE:
			#ok T:219.7 /220.0 B:26.3 /0.0 T0:219.7 /220.0 @:35 B@:0
			
			temps=serial_in.split(" ")
			ext_temp=float(temps[1].split(":")[1])
			ext_temp_target=float(temps[2].split("/")[1])

			bed_temp=float(temps[3].split(":")[1])
			bed_temp_target=float(temps[4].split("/")[1])
			received+=1
			
		## temp report (wait)	
		if serial_in[:2]=="T:":	
			#collected M109/M190 Snnn temp (Set temp and  wait until reached)
			#T:187.1 E:0 B:59
			temps=serial_in.split(" ")
			ext_temp=temps[0].split(":")[1]
			bed_temp=temps[2].split(":")[1]
			
			#print "BED: "+str(bed_temp) + " EXT: "+ str(ext_temp)
			#ok is sent separately.
			
		#clear everything not recognized.
		serial_in=""
	#print "listener closed"



		
def tracker():
	global sent
	global lenght
	global EOF
	
	mtime=os.path.getmtime(comfile) #update override file mtime.
	elapsed=0
	last_update=0
	
	while not EOF:
		elapsed=time.time()-last_update
		if elapsed>5:
			#trace the progress
			progress = 100 * float(sent) / float(lenght)
			printlog(progress,sent)
		
			#update the override comand queue each 5 seconds
			if (os.path.getmtime(comfile)!=mtime): #force a new command if the comand override file has been modified recently
				while(not(os.access(comfile, os.F_OK)) or not(os.access(comfile, os.W_OK))):
					time.sleep(0.5) #no hammering
					pass
				#file is readeable, can proceed
				mtime=os.path.getmtime(comfile) #update file mtime.
				
				#append new command(s)
				with open(comfile) as f:
					for line in f:
						ovr_cmd.append(line.rstrip())
						
				#clear the override file
				open(comfile, 'w').close() 
				
			
			##request temp status once
			if len(ovr_cmd)>0:
				if ovr_cmd[len(ovr_cmd)-1]!="M105":
					ovr_cmd.append("M105")
			else:
				ovr_cmd.append("M105")
				
			
			#refresh counter
			last_update=time.time()
	#print "tracker closed"

#MAIN			
	
#printlog initialization
printlog(0,0)
	
#initialize serial		
port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.5)
serial.flushInput()

#preload
with open(ncfile) as f:
	for line in f:
		lenght+=1
f.close()

#DEBUG 
trace( "File loaded.")
	
#start sender thread
sender = Thread(target=sender)
#sender.daemon=True
sender.start()

#start listener thread
listener = Thread(target=listener)
#listener.daemon=True
listener.start()

#start tracker thread
tracker = Thread(target=tracker)
#tracker.daemon=True
tracker.start()

#wait EOF
while not EOF:
	pass
	
#completed:
completed=1

status="performed"
#set the JSON job as completed
if not killed:
	#completed!
	trace("Program Completed...")
	completed_time=int(time.time())
	printlog(100,lenght)
else:
	trace("Procedure Aborted")
	completed_time=int(time.time())
	printlog(progress,sent)
	status="stopped"

trace("Now finalizing...")
#finalize database-side operations
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" print " +str(status)], shell=True)

#shudown the printer if requested
if shutdown:
	trace("Shutting down...")
	#enter sleep mode
	call(['echo "M729">/dev/ttyAMA0'], shell=True)
	time.sleep(10)
	#shutdown Raspi
	call (['sudo shutdown -h now'], shell=True)

#terminate operations
tracker.join()
#trace("tracker ok");

sender.join()
#trace("sender ok");

listener.join()
#trace("listener ok");

trace("Done!");

serial.close()
sys.exit()