import os, sys, getopt
import serial
import time
from subprocess import call
import ConfigParser
import RPi.GPIO as GPIO
import logging
import json

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

#check if LOCK FILE EXISTS
if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()

#PARAMS

#get process pid so the GUI can kill it if needed
myPID = os.getpid()
completed=0
completed_time=0
percent=0
name="unidentified"

slices=360   #default slices num
deg=360      #default degrees (set to scan chunks only)
iso=400      #default ISO setting
width=1920   #default W
height=1080  #default H
power=230    #default laser PWM value.

usage= 'r_scan.py -s<slices> -i<ISO> -d<destination> -l<json log> -b<start angle> -e<end angle> -z<z_offset> -w<width> -h<height>\n\npython r_scan.py -s30 -i200 -d/var/www/fabui/python/scans -llog.json -b0 -e360 -w1920 -h1080'

#default scan range params, do not change.
begin=0
end=360
z_offset=0 #z offset.(actually Y offset position in the A axis reference)
pos=0 # default Z position (unless begin !=0)
i = 0

#PARAM MANAGER

try:
    opts, args = getopt.getopt(sys.argv[1:],"n:s:i:d:l:w:h:pb:e:z",["slices=","ISO=","dest=","log=","width=","height=","power=","begin=","end="])
except getopt.GetoptError as e:
    #Error handling for unknown or incorrect number of options
    print "\n\nERROR!\n Correct usage:\n\n",usage , e
    sys.exit(2)
for opt, arg in opts:
   if opt =='--help':
      print usage 
      sys.exit()
   elif opt in ("-n", "--name"):
      name = arg
   elif opt in ("-s", "--slices"):
      slices = int(arg)
   elif opt in ("-i", "--ISO"):
      iso = int(arg)
   elif opt in ("-w", "--width"):
      width = int(arg)
   elif opt in ("-h", "--height"):
      height = int(arg)
   elif opt in ("-p", "--power"):
      power = int(arg)
   elif opt in ("-b", "--begin"):
      begin = int(arg)
   elif opt in ("-e", "--end"):
      end = int(arg)
   elif opt in ("-z", "--z-offset"):
      z_offset = arg #can be a float
   elif opt in ("-d", "--dest"):
      destination = arg
   elif opt in ("-l", "--log"):
      logfile = arg

#write LOCK FILE    
open(config.get('task', 'lock_file'), 'w').close()

'''#### LOG ####'''
logfile=config.get('task', 'monitor_file')
log_trace=config.get('task', 'trace_file')
logging.basicConfig(filename=log_trace,level=logging.INFO,format='[%(asctime)s] - %(message)s', datefmt='%d/%m/%Y %H:%M:%S')

      
started=float(time.time())

#compose destination
scan_dir=destination+"images/"    
    
def printlog(percent,num):
    
    stats = {'percent': str(percent), 'img_number': str(i), 'tot_images' : str(slices) }
    scan = {'name': name, 'pid': str(myPID), 'started': str(started), 'completed': str(completed), 'completed_time': str(completed_time), 'stats': stats}
    json_log = {'scan': scan}        
    #str_log='{"scan":{"name": "'+name+'","pid": "'+str(myPID)+'","started": "'+str(started)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","stats":{"percent":"'+str(percent)+'","img_number":'+str(i)+',"tot_images":'+str(slices)+'}}}'
    
    #handle=open(destination+logfile,'w')
    handle=open(logfile,'w')
    print>>handle, json.dumps(json_log)
    handle.close()
    #print>>handle, str_log
    return
    
printlog(0,0) #create log vuoto
    
print 'SCAN MODULE STARTING' 
print 'scanning from'+str(begin)+"to"+str(end); 
print 'Num of scans : ', slices
print 'ISO  setting : ', iso
print 'Resolution   : ', width ,'*', height, ' px'
print 'Laser PWM.  : ', power
print 'z offset     : ', z_offset

#ESTIMATED SCAN TIME ESTIMATION
estimated=(slices*1.99)/60
if(estimated<1):
    estimated*=60
    unit= "Seconds"
else:
    unit= "Minutes"

print 'Estimated Scan time =', str(estimated) + " " + str(unit) + "  [Pessimistic]"

#Please note: laser PWM is controlled by M700 SXXX

print "\n ---------- Initializing ---------- \n"

'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.5)

if(begin!=0):
    #if an offset is set, rotates to the specified A angle.
    serial.write('G0 E' + str(begin) + '\r\n')  #set zero
    pos=begin #set start position as defined offset.
    
if(z_offset!=0):
    #if an offset for Z (Y in the rotated reference space) is set, moves to it.
    serial.write('G0 Y' + str(z_offset) + '\r\n')  #go to y offset
    
time.sleep(10)  #take its time to move

# STARTING PARAMS //DO NOT CHANGE
deg = abs((float(end)-float(begin))/float(slices))  #degrees to move each slice

#-----

def raspistill(laser_string):
#shell call raspistill
    scanfile=scan_dir + str(i) + laser_string + ".jpg"
    #NEW raspistill -o test4.png -rot 90 -hf -vf -w 1944 -h 2592
    #--exposure off
    print "saving to ",scanfile

    #raspistill -rot 270 -awb tungsten -ISO 100 -ss 45000
    
    call (["raspistill -rot 270 -awb off -awbg 1.5,1.2 -q 100 -ss 35000 -ISO " + str(iso) + " -w "+ str(height) +" -h "+ str(width) +" -o " + scanfile + " -t 1"], shell=True)

    while (not(os.access(scanfile, os.F_OK)) or not(os.access(scanfile, os.W_OK))):
        #wait until the file has been written
        time.sleep(0.1)
        pass
    return
    
serial.write('M700 S0\r\n')# Turn laser off (you never know!)


while (i < slices) :

    #Turn the plate
    print str(i) + "/" + str(slices) +" (" + str(deg*i) + "/360)"
    serial.write('G0 E' + str(pos) + 'F2500\r\n')
    time.sleep(deg*0.1)  #take its time to rotate
        
    pwm_string='M700 S'+str(power)+'\r\n' 

    serial.write(pwm_string) #turn laser ON
    raspistill("_l")       #snap a pic
    
    serial.write('M700 S0\r\n')# Turn laser off
    raspistill("")           #snap a pic without laser
    
    percent=100 * float(i)/float(slices)
    printlog(percent,i)
    pos+=deg #increase current pos + deg.
    i+=1     
#END of R_SCAN

serial.flush()
serial.close()                #close serial

print "Scan Completed."

completed=1
completed_time=float(time.time())
percent=100
printlog(percent,i)
#write_status(False)
os.remove(config.get('task', 'lock_file'))
sys.exit()  