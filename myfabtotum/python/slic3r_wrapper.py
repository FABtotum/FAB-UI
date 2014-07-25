#Slic3r comand line wrapper and FAB UI syncronization tool
#sudo /var/www/slic3r/slic3r -load /var/www/slic3r/config.ini -o /var/www/slic3r/output.gcode /var/www/slic3r/cube.stl
from subprocess import Popen, PIPE, STDOUT
import time
import os, sys, getopt
from subprocess import call

times=[]
percents=[]
names=[]
process=[23.56, 5.53, 13.30, 1.14, 0.07, 1.17, 2.33, 0.004 , 23.67, 0.09, 27.05,0]
percent=0.01
completed=0
config=""
myPID=os.getpid()
name="none"
basetime=endtime=0
log_file=""
trace_file=""
input=""
output=""
config=""
task_id=0
perc_estimate=0
eta=0
elapsed=0
estimate=0
log_time=0
log_elapsed=0
started=False

usage= 'Usage: slic3r_wrapper.py -t<trace> -l<log> -i<input STL> -o<Output Gcode> -c<config>\n'
#python /var/www/myfabtotum/python/slic3r_wrapper.py -t -l -i -o<Output Gcode> -c/var/www/slic3r/config.ini\n'

try:
	opts, args = getopt.getopt(sys.argv[1:],"ht:l:i:o:c:k:",["help","trace=","log=","input=","output=","config="])
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
	elif opt in ("-c", "--config"):
		config = arg
	elif opt in ("-k", "--key"):
		task_id = int(arg)
	else:
		print usage
		sys.exit(2)
		
def printlog(percent,status):		
	str_log='{"slicing":{"id": "'+str(task_id)+'","pid": "'+str(myPID)+'","started": "'+str(basetime)+'","completed": "'+str(completed)+'","completed_time": "'+str(endtime)+'","stats":{"percent":"'+str(percent)+'","time_left":"'+str(eta)+'","time_elapsed":"'+str(elapsed)+'","time_total":"'+str(estimate)+'"}}}'
	handle=open(log_file,'w')
	print>>handle, str_log
	return
#track trace

#track trace
def trace(string):
	out_file = open(trace_file,"a+")
	out_file.write(str(string) + "<br>")
	out_file.close()
	print string
	return

printlog(0,"initializing...")
trace("Slic3r is being initialized")
names.append("Slic3r is being initialized")
	
cmd = 'sudo /var/www/myfabtotum/slic3r/slic3r -load '+config+' -o '+ output + ' ' +input

p = Popen(cmd, stdout = PIPE, 
        stderr = STDOUT, shell = True)		

i=0
basetime=time.time()
while True:
	passed=time.time()-basetime
	line = p.stdout.readline()
	
	if line[:31]=="=> Processing triangulated mesh":
		#avoid warnings and messages
		started=True
	
	if line!="" and started:
		names.append(line)
		times.append(time.time()-basetime)
		#print str(i) +" - "+ line + " passed " + str(times[i])
		percent+=process[i]
		trace(line)
		i+=1
	if not line: break
		
	growt=percent/passed
	
	log_elapsed=time.time()-log_time
	if log_elapsed>2:
		#estimated time of completion and progress	
		perc_estimate=growt*passed #current percentage 
		estimate=(passed/perc_estimate)*100 #estimated total time to completion in seconds.
		eta=int(abs(estimate-passed))
		printlog(perc_estimate,line)
		log_time=time.time()	#reset counter.
		#print str(i)+ " - " + str(perc_estimate) + " left: " + str(eta)  +" Passed: "+str(passed)
		

#done!

#print "started at" + str(basetime)
#print times
endtime=time.time()
#print "ended" + str(endtime)
#print "total" + str(endtime-basetime)
completed=1
eta=0
printlog(100,"Slicing Completed!")

#finalize PHP (in case no client is connected)
call (['sudo php /var/www/myfabtotum/script/finalize.php '+str(task_id)+ " slice"], shell=True)

sys.exit()