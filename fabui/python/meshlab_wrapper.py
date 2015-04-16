#sudo xvfb-run -a -e [LOGFILE] meshlabserver -i [INPUT] -s [FILTERSCRIPT] -o [OUTPUTSTL]
from subprocess import call,Popen, PIPE, STDOUT
import time
import fcntl
import os, sys, getopt
import logging


myPID=os.getpid()	
percent=0
completed=0
config=""
name="none"
basetime=endtime=0
log_file=""
trace_file=""
input=""
output=""
config=""
script=""

task_id=0
perc_estimate=0
eta=0
elapsed=0
estimate=0
log_time=0
mesh_pid=0


usage= 'Usage: meshlab_wrapper.py -t<trace> -l<log> -i<input ASC> -o<Output STL> -s<MLX Script> -k<FABUI task ID>\n'
#sudo pyhton meshlab_wrapper.py -t/var/www/fabui/slic3r/meshlab.trace -l/var/www/temp/meshlab.log -i/var/www/fabui/slic3r/file.asc -o/var/www/fabui/slic3r/output.stl -s/root/meshlab_script.mlx

try:
	opts, args = getopt.getopt(sys.argv[1:],"ht:l:i:o:s:k:",["help","trace=","log=","input=","output=","config=","key="])
except getopt.GetoptError:
	#Error handling for unknown or incorrect number of options
	print "\n\nERROR!\n Correct usage:\n\n",usage
	sys.exit(2)
for opt, arg in opts:
	if opt =='--help':
		print usage 
		sys.exit()
	elif opt in ("-t", "--trace"):
		trace_file = arg
	elif opt in ("-l", "--log"):
		log_file = arg
	elif opt in ("-i", "--input"):
		input = arg
	elif opt in ("-o", "--output"):
		output = arg
	elif opt in ("-s", "--script"):
		script = arg
	elif opt in ("-k", "--key"):
		task_id = int(arg)
	else:
		print usage
		sys.exit(2)


logging.basicConfig(filename=trace_file, level=logging.INFO,format='<span class="hidden-xs">[%(asctime)s] -</span> %(message)s',datefmt='%d/%m/%Y %H:%M:%S')

def trace(string):
	
	#out_file = open(trace_file,"a+")
	#out_file.write(str(string) + "\n")
	logging.info(string)
	#print string
	#out_file.close()
	return

def printlog(percent,status):		
	str_log='{"Meshing":{"id": "'+str(task_id)+'","pid": "'+str(myPID)+'","mesh_pid": "'+str(mesh_pid)+'","started": "'+str(basetime)+'","completed": "'+str(completed)+'","completed_time": "'+str(endtime)+'","stats":{"percent":"'+str(percent)+'","time_left":"'+str(eta)+'","time_elapsed":"'+str(elapsed)+'","time_total":"'+str(estimate)+'"}}}'
	handle=open(log_file,'w')
	print>>handle, str_log
	return

	
def nonBlockReadline(output):
    fd = output.fileno()
    fl = fcntl.fcntl(fd, fcntl.F_GETFL)
    fcntl.fcntl(fd, fcntl.F_SETFL, fl | os.O_NONBLOCK)
    try:
        return output.readline()
    except:
        return ''
		
printlog(0,"initializing...")
trace("Meshlab server is being initialized")

#compose the Meshlab comand
cmd = 'sudo xvfb-run -a -e /var/www/temp/meshlab.log meshlabserver -i '+input+' -s '+script+' -o '+output + '' 
#p = Popen(cmd, stdout = PIPE, stderr = STDOUT, shell = True)	

working_file = Popen(cmd, stdout=PIPE, stderr=PIPE , shell=True)

mesh_pid=working_file.pid #pid of the working file

line = nonBlockReadline(working_file.stdout)
working_file.stdout.flush()

basetime=time.time()
i=0
while working_file != "" and not completed==1:
	#print(line)
	passed=time.time()-basetime
	if line!="":	
		if line.rstrip()[:22]=="Got / Solved / Updated" in line:
			percent+=46.25
			trace("Crunching Numbers..")
			growt=percent/passed
		if line[:22]=="Locating tangent planes":
			percent+=30.99
			trace("Locating Tangent Planes")
			growt=percent/passed
		if line[:10]=="Input mesh":
			percent+=17.41
			trace("Generating Input mesh")
			growt=percent/passed
		if line[:23]=="Current Plugins Dir is:":
			percent+=1.5
			trace("Loading Plugins")
			growt=percent/passed
		if line[:14]=="Mesh  saved as":
			completed=1
			trace("Mesh Saved!")
			growt=percent/passed
		if line[:7]=="aborted":
			completed=1
			trace("Error Occurred")
			printlog(0,"Meshing was aborted due to errors")
			sys.exit()
			
		#print line
		#trace(line)
		i+=1
	log_elapsed=time.time()-log_time
	if log_elapsed>2 and percent>1:
		perc_estimate=growt*passed #current percentage 
		estimate=(passed/perc_estimate)*100 #estimated total time to completion in seconds.
		
		if(perc_estimate>100):
			perc_estimate=99 #avoid overshooting.
			
		eta=int(abs(estimate-passed))
		printlog(perc_estimate,line)
		log_time=time.time()	#reset counter.
		print str(i)+ " - " + str(perc_estimate) + " left: " + str(eta)  +" Passed: "+str(passed)
		#time.sleep(0.1)

	line = nonBlockReadline(working_file.stdout)
	working_file.stdout.flush()		
	
#####

endtime=time.time()
completed=1
eta=0
printlog(100,"Meshing Completed!")

#finalize PHP (in case no client is connected)
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+ " mesh"], shell=True)

sys.exit()