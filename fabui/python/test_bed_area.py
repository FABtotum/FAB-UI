#bed leveling tool
import time
import os, sys, getopt
import numpy as np
import json
import ConfigParser
import logging
import serial
import numpy

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()

x1=y1=x2=y2=skip=0
s_error=0
s_warning=0
s_skipped=0

usage = 'test_bed_area.py -x1<VALUE> -y1<VALUE> -x2<VALUE> -y2<VALUE> '

try:
    opts, args = getopt.getopt(sys.argv[1:],"x:y:j:z:s:",["x=","y=","j=","z=", "s="])
except getopt.GetoptError:
    #Error handling for unknown or incorrect number of options
    print "\n\nERROR!\n Correct usage:\n\n",usage
    sys.exit(2)
    
for opt, arg in opts:
    if opt =='--help':
        print usage 
        sys.exit()
    elif opt in ("-x"):
        x1 = int(arg)
    elif opt in ("-y"):
        y1 = int(arg)
    elif opt in ("-j"):
        x2 = int(arg)
    elif opt in ("-z"):
        y2 = int(arg)
    elif opt in ("-s"):
        skip = int(arg)

#write LOCK FILE    
open(config.get('task', 'lock_file'), 'w').close()

macro_status=config.get('macro', 'status_file')
log_trace=config.get('macro', 'trace_file')
logfile=config.get('macro', 'response_file')

logging.basicConfig(filename=log_trace,level=logging.INFO,format='%(message)s')

'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.5)

serial_reply = ''

def trace(string):
    print string
    logging.info(string)
    return

#probe routine
def probe(x,y):
    serial_reply=""
    serial.flushInput()
    serial.write("G30\r\n")
    probe_start_time = time.time()
    while not serial_reply[:22]=="echo:endstops hit:  Z:":
        serial_reply=serial.readline().rstrip()    
        #issue G30 Xnn Ynn and waits reply.
        if (time.time() - probe_start_time>90):  
            #timeout management
            trace("Could not probe this point")
            return False
            break    
        pass
    #get the z position
    z=float(serial_reply.split("Z:")[1].strip())
    
    new_point = [x,y,z,1]
    
    trace("Probed "+str(x)+ "," +str(y))
    return True

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
            trace(error_msg)
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
                    trace(error_msg + ": Failed (" +serial_reply +")")
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
        trace(error_msg + ": Skipped")
        s_skipped+=1
        return False
    return serial_reply


print 'Start to check area on this points:'
print 'X1 --> ', x1
print 'X2 --> ', x2
print 'Y1 --> ', y1
print 'Y2 --> ', y2


macro("M402","ok",2,"Retracting Probe (safety)",0.1, warning=True, verbose=False)

if(skip == 0):
    macro("G90","ok",2,"Setting absolute positioning mode",1, )
    macro("G27","ok",100,"Zeroing Z axis",1)
    macro("G28 X0 Y0","ok",15,"Zeroing Z axis",1, warning=True, verbose=False)
    macro("G0 Z135 F1000","ok",5,"Moving to pre-scan position",3, warning=True, verbose=False)
    macro("M18","ok",1,"Motor Off",1, warning=True, verbose=False) #should be moved to firmware
    macro("G0 Z40 F5000","ok",5,"Moving to start Z height",10) #mandatory!


points = [[x1, y1], [x1, y2], [x2, y2], [x2, y1]]

for (x,y) in points:
    macro("M402","ok",1,"Retracting Probe (safety)",0.1, warning=True, verbose=False)
    macro('G0 X' + str(x) + ' Y' +str(y) + ' F15000',"ok",1,"Going to (" + str(x) + ","+str(y)+")",0.1, warning=True)
    macro("M401","ok",1,"",0.1, warning=True, verbose=False)
    time.sleep(0.5)
    probe(x, y)
    macro("M402","ok",1,"Retracting Probe (safety)",0.1, warning=True, verbose=False)
    time.sleep(1)
    serial.write('G0 Z40 F5000\r\n')
    time.sleep(0.5)
        

macro("M402","ok",1,"Retracting Probe (safety)",0.1, warning=True, verbose=False)

os.remove(config.get('task', 'lock_file'))










