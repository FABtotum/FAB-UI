import os,sys,time
import serial
from serial import SerialException
from threading import Thread
from subprocess import call
import re
import json
import ConfigParser
import logging
import argparse
from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler

parser = argparse.ArgumentParser()
parser.add_argument("file", help="nc file to execute")
parser.add_argument("command_file", help="command file")
parser.add_argument("task_id", help="id_task")
parser.add_argument("time", help="time monitor interval",  default=5, nargs='?')
args = parser.parse_args()

ncfile = args.file
comfile = args.command_file
task_id = args.task_id
time_interval = args.time

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

serialconfig = ConfigParser.ConfigParser()
serialconfig.read('/var/www/lib/serial.ini')

''' IF LOCK FILE EXISTS PRINTER IS ALREADY BUSY, ELSE CREATE IT '''
if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()
else:
    open(config.get('task', 'lock_file'), 'w').close()
    
    
logfile=config.get('task', 'monitor_file')
log_trace=config.get('task', 'trace_file')

logging.basicConfig(filename=log_trace,level=logging.INFO,format='%(message)s')

EOF=False
ovr_cmd=[]
str_log=""
received=0
sent=0
progress=0
lenght=0
percent=0
sent=0
resend=0
started=last_update=time.time()
completed_time=0
resent=0
completed=False
z_override=0
print_started=False
#total_layers=0
#actual_layer=0
rpm=0
paused=False
shutdown=False #default shutdown printer on complete = no
killed=False

def writeMonitor(percent,sent):
    
    
    global print_started
    global rpm
    global z_override
    global started

    _stats={"percent": str(percent), "line_number": str(sent), "z_override": str(z_override), 'rpm':str(rpm) }
    _print = {"name": ncfile, "lines": str(lenght),  "print_started": str(print_started), "started": str(started), "paused": str(paused), "completed": str(completed), "completed_time": str(completed_time), "shutdown": str(shutdown), "stats": _stats}
    str_log = {"type": "print", "print": _print}
    
    handle=open(logfile,'w+')
    print>>handle, json.dumps(str_log)
    handle.close()
    return

def trace(string):
    logging.info(string) 
    return

''' RESET LOG TRACE to avoid annoing verbose '''
def resetTrace():
    with open(log_trace, 'w'):
        pass
    
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
    
    
    global fan
    global rpm
    
    try:
        command_splitted = command.split()
        
        code= command_splitted[0]
        value= command_splitted[1]
        value=value.replace("S", "");
    except:
        code=""
        value=""
        
    description="<span class='override-command'>"
    
    if code=="M220":
        description+="Speed factor override set to "+value+"%"
    elif code=="M3":
        rpm=value
        description+="RPM speed set to "+value+""
    elif code=="!z_plus":
        description+="Z height incresed by 0.1 mm"
    elif code=="!z_minus":
        description+="Z height decreased by 0.1 mm"
    else:
        description+="description none"
    
    description+="</span>"
    
    return description

def sender():
    global received
    global ncfile
    global sent
    global resend
    global ovr_cmd
    global EOF
    global z_override
    global killed
    global print_started
    global rpm
    global progress
    
    
    gcode_line=0
    with open(ncfile, 'r+') as f:
        # this reads in one line at a time
        #trace("Print Started, now reaching temp")
        for line in f:
            if EOF==True:
                break
            if killed==True:
                resetTrace()
                break
            line=line.rstrip()
            
            #print line
            #print "sender: " + str(EOF) + " " + str(killed)
            
            gcode_line+=1
            #print str(gcode_line)+ " -"+line+"-"
            if not(line=="" or line[:1]==";"):
                doWriteMonitor=False
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
                        
                        override_splitted = override.split(':')
                        if override_splitted[0]=="!kill": #stop print
                            resetTrace()
                            trace("Terminating Process")
                            #kill the process
                            killed=True
                            EOF=True
                            break            
                                
                        elif override_splitted[0]=="!z_plus":  
                            #z_override +=0.1
                            z_override += float(override_splitted[1])
                            
                            serial.write("G91\r\n") 
                            serial.write("G0 Z+" + override_splitted[1] +"\r\n")  #move up
                            serial.write("G90\r\n") 
                            sent+=3
                            trace("<span class='override-command'>Z height incresed by "+ override_splitted[1]+" mm</span>")

                        elif override_splitted[0]=="!z_minus":
                            #z_override -=0.1
                            z_override -= float(override_splitted[1])                        
                            serial.write("G91\r\n") 
                            serial.write("G0 Z-"+ override_splitted[1] +"\r\n")  #move down
                            serial.write("G90\r\n") 
                            sent+=3
                            trace("<span class='override-command'>Z height decreased by " + override_splitted[1] +" mm</span>")
                    else:
                        #gcode is executed ASAP
                        serial.write(override+"\r\n")
                        trace(override_description(override))
                        sent+=1
                        
                #Normal Gcode
                #if received>sent: #buffer is empty, can send next line
                
                #Z override calculation
                if z_override!=0:    
                    z_str = re.search('(?<=Z)([+|-]*[0-9]*.[0-9]*)', line)
                    if z_str:
                        z_c = z_str.group(1)
                        if is_number(z_c):
                            new_z_c = float(z_c)+float(z_override)
                            #z_c = float(z_c)+z_override
                            #update Z coords.
                            line = line.replace(str(z_c), str(new_z_c))
                            #line =re.sub('Z.*? ','Z'+str(z_c)+' ',line, flags=re.DOTALL)
                        #trace(line)


                
                if print_started == False:
                    if line[0:2]== "G0" or line[0:2]=="G1":
                        trace("Mill Started")
                        print_started=True
                else:    
                    if(line[0:2] == "M3" or line[0:2] == "M4" or line[0:2] == "M6"):
                        
                        has_S = re.search('[sS]', line)
                        
                        if has_S:
                            rpm=line.split("S")[1].strip()
                            trace("RPM speed set to "+ str(rpm))
                            doWriteMonitor=True
                #Send the line
                #print line
                serial.write(line+"\r\n")
                
                if doWriteMonitor:
                    writeMonitor(progress,sent)
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
    
    global killed
    
    serial_in=""    
    while not EOF:
        
        while serial_in=="":
            try:
                serial_in=serial.readline().rstrip()
            #time.sleep(0.05)
                pass #wait!
            except SerialException as err:
                trace(str(err))
        
        #if there is serial in:
        #parse actions:
        #print "rcv" + str(serial_in)
        
        ##ok
        if serial_in=="ok":
            #print "received ok"
            received+=1
            #print "sent: "+str(sent) +" rec: " +str(received)
        
        ##error
        
        try:
            if serial_in[:6]=="Resend":
                #resend line
                resend=serial_in.split(":")[1].rstrip()
                received-=1 #lost a line!
                trace("Error: Line no "+str(resend) + " has not been received correctly")
        
        except IndexError as err:
            trace(str(err))
        #print "BED: "+str(bed_temp) + " EXT: "+ str(ext_temp)
        #ok is sent separately.
        
    #clear everything not recognized.
        serial_in=""

def tracker():
    global sent
    global lenght
    global EOF
    global progress
    global print_started
    global killed
    global time_interval
    
    #mtime=os.path.getmtime(comfile) #update override file mtime.
    elapsed=0
    last_update=0

    started=time.time()
    
    while not EOF:
           
        elapsed=time.time()-last_update
        if elapsed>5:
            #trace the progress
            progress = 100 * float(sent) / float(lenght)
            writeMonitor(progress,sent)
            last_update=time.time()



class MyHandler(PatternMatchingEventHandler):
    
    global killed
    global EOF
    global ovr_cmd
    
    def catch_all(self, event, op):
        if event.is_directory:
            return
        if(event.src_path == comfile):
            with open(event.src_path) as f:
                for line in f:
                    c=line.rstrip()
                    if not c in ovr_cmd and c != "": 
                        ovr_cmd.append(c)
                        if c=="!kill":
                            killed=True
                            EOF=True
                            
                            
            open(event.src_path, 'w').close()
    def on_modified(self, event):
        self.catch_all(event, 'MOD')


event_handler = MyHandler(patterns=[comfile])
#event_handler = MyHandler()
observer = Observer()
observer.schedule(event_handler, '/var/www/tasks/', recursive=True)
observer.start()



'''### GET TOTAL LAYERS ####'''
#if(isAdditive):
#    total_layers = getLayers(ncfile)            
#printlog initialization
writeMonitor(0,0)
    
#initialize serial        
'''#### SERIAL PORT COMMUNICATION ####'''
serial_port = serialconfig.get('serial', 'port')
serial_baud = serialconfig.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.6)

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
completed=True

status="performed"
#set the JSON job as completed
if not killed:
    #completed!
    trace("Program Completed...")
    completed_time=int(time.time())
    writeMonitor(100,lenght)
else:
    trace("Procedure Aborted")
    completed_time=int(time.time())
    writeMonitor(progress,sent)
    status="stopped"

trace("Now finalizing...")
call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" mill " +str(status)], shell=True)

observer.join()

tracker.join()
sender.join()
listener.join()
serial.close()
#trace("Serial Close")
sys.exit()


