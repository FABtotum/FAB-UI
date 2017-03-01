#printcore wrapper (provisional version)
import os, sys
import serial
import datetime,time
from subprocess import call

from printrun.printcore import printcore
from printrun import gcoder

#we need all the params.
if(len(sys.argv)<3):
	print "gpusher: missing params!"
	sys.exit()

#process params
ncfile=str(sys.argv[1])  #param for the gcode to execute
logfile=str(sys.argv[2]) #param for the log file
comfile=str(sys.argv[3]) #comand file
# =str(sys.argv[4]) 	 #open slot
log_trace=str(sys.argv[5])	#trace log file
task_id=str(sys.argv[6])	#task ID

#params for preheating (defaults)
t_ovr=False
mtime=os.path.getmtime(comfile) #update override file mtime.

#get process pid so the UI can kill it if needed
myPID = os.getpid()

#clear the comfile
call (['sudo echo "" > '+ comfile], shell=True)

#trace: verbose log
def trace(string):
	out_file = open(log_trace,"a+")
	out_file.write(str(string) + "\n")
	out_file.close()
	print string
	return

#load gcode	
def gcode_load(fname):
	global t_ovr
	global p
	try:
		f = open(fname,'r')
	except:
		trace("Could not open the file.")
	data = []

	for line in f.readlines():
		tmp = line.replace("\r","")
		tmp = tmp.replace("\n","")
			
		if line[:4]=="M109":
			line=line.split(";")[0]
			line=line.replace("M104","M109")
			p.send_now(line)
			trace("heating extruder")
			t_ovr=True
		if line[:4]=="M190":
			line=line.split(";")[0]
			line=line.replace("M140","M190")
			p.send_now(line)
			trace("heating bed")
			t_ovr=True
		if len(tmp) > 0 and not t_ovr:		#add line only if line lenght > 0 
			data.append(line.replace('\n',''))
		t_ovr=False
		
	return data


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
		description= "<strong>Extruder bed set to "+value+" &deg;C</strong>"
	elif code=="M220":
		description="<strong>Speed set to "+value+"%</strong>"
	elif code=="M3":
		description="<strong>RPM speed set to "+value+"%</strong>"
	else:
		descritpion="description none"
	return description
	

#code=gcode_load(ncfile)
ovr_code=[] #override code initialized

started=float(time.time())
elapsed=0
completed_time=0

completed=0
percent=0
temperatures={"extruder":0,"bed":0,"extruder_target":0,"bed_target":0}
position="0,0,0,0"

paused=False
shutdown=False #default shutdown printer on complete = no
killed=False  
	
def printlog(percent,line,totlines):		
	str_log='{"print":{"name": "'+ncfile+'","pid": "'+str(myPID)+'","lines": "'+str(totlines)+'","started": "'+str(started)+'","paused": "'+str(paused)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","shutdown": "'+str(shutdown)+'","stats":{"percent":"'+str(percent)+'","line_number":'+str(line)+',"extruder":'+str(temperatures["extruder"])+',"bed":'+str(temperatures["bed"])+',"extruder_target":'+str(temperatures["extruder_target"])+',"bed_target":'+str(temperatures["bed_target"])+',"position": "'+str(position)+'"}}}'
	handle=open(logfile,'w+')
	print>>handle, str_log
	return
	
printlog(0,0,0) #create empty log 

port="/dev/ttyAMA0"
baud = 115200
statusreport = True

#---------------------
p = printcore(port, baud)
p.loud = False  #true for verbose
time.sleep(2)

p.send_now("M105") #get the heating temps to fasten things up a little
trace("Loading file..")

gcode=gcode_load(ncfile)
#meanwhile the heater is running
trace("File loaded")
trace("Optimizing Gcode. <strong>This operation might take a while</strong>, please wait..")
gcode = gcoder.LightGCode(gcode)
trace("<strong>Gcode optimized</strong>")
p.startprint(gcode)
trace("Print started")
start =time.time()  #time of print start
progress=0
try:
	#if statusreport:
	#	p.loud = False
	#	sys.stdout.write("Progress: 00.0%\r")
	#	sys.stdout.flush()
	
	while p.printing and not killed:
		#if elapsed==0:
			#trace("Process Started!") #one-time message
			
		#trace("temp collected:" + str(p.get_temperatures()))
		time.sleep(3) #update delay
		if p.get_temperatures():
			temperatures = p.get_temperatures()
			
		if (os.path.getmtime(comfile)!=mtime): #force a new command if the comand override file has been modified recently
			while(not(os.access(comfile, os.F_OK)) or not(os.access(comfile, os.W_OK))):
				time.sleep(0.01)
				pass

			mtime=os.path.getmtime(comfile) #update file mtime.
			coms=gcode_load(comfile) #will load the last gcode.
			if len(coms)>=1:
				override=coms.pop()
				if override[:1]=="!":
					#if comand is non-serial comand
					
					if override=="!kill": #stop print
						trace("Terminating Process")
						#kill the process
						killed=True
						break			
						
					if override=="!pause":
						if not paused:
							p.send_now("G0 X200 Y200") #move in the corner
							completed="paused"						
							trace("Print is now paused")
							p.pause()
							paused=True
					
					if override=="!resume":
						if paused:
							#resume the print
							completed=""
							trace("Resuming print")
							p.resume()
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
					trace("Command added to the gcode queue: " + str(override))
					p.send_now(override)
					
				open(comfile, 'w').close() #clear the override file
				#trace("UI comand:" + override)
				trace(override_description(override))
								
		#STATUS REPORT
		if statusreport:
			progress = 100 * float(p.queueindex) / len(p.mainqueue)
			printlog(progress,p.queueindex,len(p.mainqueue))
				
		elapsed=time.time()-start
	
except Exception,err: 
	trace("An error occurred")
	print str(err)

status="performed"
#set the JSON job as completed
if not killed:
	#completed!
	#trace("Procedure Completed")
	trace("<strong>Print completed</strong>")
	completed=1
	completed_time=int(time.time())
	printlog(100,len(p.mainqueue),len(p.mainqueue))
else:
	trace("Procedure Aborted")
	completed=1
	completed_time=int(time.time())
	printlog(progress,p.queueindex,len(p.mainqueue))
	status="stopped"
	
	
#port = '/dev/ttyAMA0'
#baud = 115200
#serial = serial.Serial(port, baud, timeout=0.5)
#serial.flushInput()
#serial.flushOutput()
	
#empty the comfile //NOT NEEDED ANYMORE
#call (['sudo echo "" > '+ comfile], shell=True)

p.disconnect()

#finalize database-side operations
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" print " + status +" "], shell=True)

#shudown the printer if requested
if shutdown:
	call(['echo "M729">/dev/ttyAMA0'], shell=True)
	#p.send_now('M729') #enter sleep mode
	time.sleep(10)
	call (['sudo shutdown -h now'], shell=True)

sys.exit()
