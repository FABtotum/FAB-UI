#sudo xvfb-run -a -e [LOGFILE] meshlabserver -i [INPUT] -s [FILTERSCRIPT] -o [OUTPUTSTL]
from subprocess import Popen, PIPE, STDOUT
import time
import os, sys, getopt
from subprocess import call
	
started=False
times=[]
percents=[]
names=[]
last=0
progress=0

def trace(string):
	out_file = open("/var/www/myfabtotum/slic3r/meshlab.trace","a+")
	out_file.write(str(string) + "\n")
	print string
	out_file.close()
	return


cmd = 'sudo xvfb-run -a -e /var/www/temp/meshlab.log meshlabserver -i /var/www/myfabtotum/slic3r/file.asc -s /root/meshlab_script.mlx -o /var/www/myfabtotum/slic3r/output.stl'
p = Popen(cmd, stdout = PIPE, stderr = STDOUT, shell = True)	
#for line in p.stdout:
#    trace(line)

i=0
basetime=time.time()
while True:
	line = p.stdout.readline()
	if last==0:
		last=basetime
	
	if line!="":
		last=time.time()-last
		names.append(line)
		times.append(last)
		percent=float(last/152.477)*100
		percents.append(percent)
		#trace(line)
		progress+=percent
		last=time.time()
		i+=1
		print str(progress)+"%"
	if not line: break
	
	
	#time.sleep(3)
#estimated time of completion and progress
passed=time.time()-basetime
print "total time:"+str(passed)

#times, names = zip(*sorted(zip(times, names))
list = zip(times,names)
list.sort(reverse=True)

for i,item in enumerate(list[:20]):
	print str(i)+item[1].rstrip() + " took " + str(item[0])
		
sys.exit()