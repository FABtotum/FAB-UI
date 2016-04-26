import os,sys,time
import serial
from serial import SerialException
from threading import Thread
from subprocess import call
import subprocess as sub
import re
import json
import ConfigParser
import logging
import argparse
from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler
''' utils function for gcode files '''
import gcode_utils
''' utils function for gcode made with cura '''
import cura_utils

''' LOAD INI FILE '''
config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

serialconfig = ConfigParser.ConfigParser()
serialconfig.read('/var/www/lib/serial.ini')

''' LOCK FILE (if exists it means printer is already busy, else create it and take over) '''
if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()
else:
    open(config.get('task', 'lock_file'), 'w').close()


''' SETTING EXPECTED ARGUMENTS  '''
parser = argparse.ArgumentParser()
parser.add_argument("file", help="gcode file to execute")
parser.add_argument("command_file", help="command file")
parser.add_argument("task_id", help="id_task")
parser.add_argument("monitor", help="monitor file",  default=config.get('task', 'monitor_file'), nargs='?')
parser.add_argument("trace", help="trace file",  default=config.get('task', 'trace_file'), nargs='?')
parser.add_argument("--ext_temp", help="extruder temperature (for UI feedback only)",  default=180, nargs='?')
parser.add_argument("--bed_temp", help="bed temperature (for UI feedback only)",  default=50,  nargs='?')

''' GET ARGUMENTS '''
args = parser.parse_args()

''' INIT VARs '''
gcode_file= args.file #''' GCODE FILE '''
command_file= args.command_file #''' OVERRIDE DATA FILE '''
task_id= args.task_id #''' TASK ID  '''
monitor_file= args.monitor #''' TASK MONITOR FILE (write stats & task info, es: temperatures, speed, etc'''
log_trace= args.trace #''' TASK TRACE FILE '''
ext_temp_target=args.ext_temp #''' EXTRUDER TARGET TEMPERATURE (previously readed from file) '''
bed_temp_target=args.bed_temp #''' BED TARGET TEMPERATURE (previously readed from file) '''

received=0 #''' RECEIVED COMMANDS COUNTER '''
sent=0 #''' SENT COMMANDS COUNTER '''
ext_temp=0 #''' EXTRUDER TEMPRATURE '''
bed_temp=0 #''' BED TEMPERATURE '''
fan=255 #''' FAN VALUE '''
tip=False #''' ENABLE/DISABLE TIP MESSAGE '''
tipMessage="" #''' TIP MESSAGE '''
EOF=False #''' END OF FILE  '''
ovr_cmd=[] #''' OVERRIDE LIST COMMANDS '''
progress=0 #''' PRINT PROGRESS % '''
lenght=0 #''' LENGHT OF GCODE FILE, NUMBER OF COMMANDS '''
resend=0 #''' RESEND COMMAND COUNTER '''
started=last_update=time.time() #''' TIME VARS '''
completed_time=0 #''' COMPLETED TIME ''' 
completed=False #''' COMPLETED PRINT FLAG '''
z_override=0 #''' Z OVERRIDE TOTAL '''
print_started=False #''' STARTED PRINT FLAG '''
layers=[] #''' TOTAL LAYERS '''
actual_layer=0 #''' CURRENT LAYER '''
paused=False #''' PRINT PAUSED FLAG ''' 
shutdown=False #''' SHUTDOWN FLAG ''' 
killed=False  #''' PRINT KILLED FLAG '''
engine=""
is_cura=False
is_slic3r=False

''' INIT LOG '''
logging.basicConfig(filename=log_trace,level=logging.INFO,format='%(message)s')
'''#### CREATE SERIAL PORT COMMUNICATION OBJECT ####'''
serial_port = serialconfig.get('serial', 'port')
serial_baud = serialconfig.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.6)
serial.flushInput()

''' READ TEMPERATURES BEFORE PRINT STARTS (improve UI feedback response) '''
serial.write("M105\r\n")
serial_reply=serial.readline().rstrip()
temperature_match = re.search('ok\sT:([0-9]+\.[0-9]+)\s\/([0-9]+\.[0-9]+)\sB:([0-9]+\.[0-9]+)\s\/([0-9]+\.[0-9]+)\s', serial_reply)
if temperature_match != None:
    ext_temp = float(temperature_match.group(1))
    ext_temp_target = float(temperature_match.group(2))
    bed_temp = float(temperature_match.group(3))
    bed_temp_target = float(temperature_match.group(4))
    
''' ========================================== UTILITY FUNCTIONS ================== '''
''' RETURN IF (s) IS NUMBER '''
def is_number(s):
    try:
        float(s)
        return True
    except ValueError:
        return False

''' RETURN IF LINE CAN BE PROCESSED (avoiding blank lines and comments) '''
def is_valid_line(line):
    if(line[:1]==';'):
        process_comment(line)
        return False
    return not(line=="")
    
''' WRITE STATS ON MONITOR FILE '''
def writeMonitor(percent,sent):
    
    global bed_temp_target    
    global ext_temp_target
    global ext_temp
    global bed_temp
    global tip
    global tipMessage
    global layers
    global actual_layer
    global fan
    global print_started
    global monitor_file
    global engine
    
    _layers={'total':layers, 'actual': str(actual_layer)}
    _stats={"percent": str(percent), "line_number": str(sent), "extruder": str(ext_temp), "bed":str(bed_temp), "extruder_target":str(ext_temp_target), "bed_target": str(bed_temp_target), "z_override": str(z_override), "layers":_layers, "fan": str(fan) }
    _tip={"show":str(tip), "message": str(tipMessage)}
    _print = {"name": gcode_file, "lines": str(lenght),  "print_started": str(print_started), "started": str(started), "paused": str(paused), "completed": str(completed), "completed_time": str(completed_time), "shutdown": str(shutdown), "tip": _tip, "stats": _stats}
    stats = {"type": "print", "print": _print, "engine": str(engine)}
    
    stats_file=open(monitor_file,'w+')
    stats_file.write(json.dumps(stats))
    #print>>handle, json.dumps(str_log)
    stats_file.close()
    return

''' WRITE TO TRACE FILE '''
def trace(string):
    print string
    logging.info(string) 
    return
''' RESET LOG TRACE to avoid annoing verbose '''
def resetTrace():
    with open(log_trace, 'w'):
        pass

def process_comment(comment):
    if is_cura == True:
        process_cura_comment(comment)
        
def process_cura_comment(comment):
    global actual_layer
    cp = cura_utils.process_comment(comment)
    if(cp != None):
        if(cp[0] == 'layer'):
            actual_layer = int(cp[1])+1     
    writeMonitor(progress,sent)
    
''' ========================================== END UTILITY FUNCTIONS ================== '''

''' WATCHDOG CLASS HANDLER FOR DATA FILE COMMAND '''
class OverrideCommandsHandler(PatternMatchingEventHandler):
    global killed
    global EOF
    global ovr_cmd
    
    def catch_all(self, event, op):
        if event.is_directory:
            return
        if(event.src_path == command_file):
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

''' ========================================== THREADS DEFINITIONS ================== '''
''' GCODE SENDER THREAD
    Loop through the gcode file one line at time and send command '''
def sender():
    global received
    global gcode_file
    global sent
    global resend
    global ovr_cmd
    global EOF
    global z_override
    global bed_temp_target    
    global ext_temp_target
    global killed
    global print_started
    global actual_layer
    global fan
    global progress
    gcode_line=0
    
    ''' taking possession of the file '''
    with open(gcode_file, 'r+') as file:
        trace("Now reaching temperatures..")
        
        ''' start loop '''
        for line in file:
            
            if EOF==True: #''' IF EOF REACHED BREAK EXIT LOOP''' 
                break
            if killed==True: #''' IF KILLED BREAK EXIT LOOP '''
                resetTrace()
                break
            
            line=line.rstrip()
            gcode_line+=1
            
            if is_valid_line(line): #''' IF LINE IS VALID '''
                doWriteMonitor=False
                command=line.split(";")[0] #''' remove command inline comment '''
                
                while received<sent and sent>0 and EOF==False:
                    pass
                
                ''' SEND BEFORE ALL OVERRIDES COMMANDS IF ARE PRESENT '''
                while len(ovr_cmd)>0 and received>=sent and EOF==False:
                    override=ovr_cmd.pop(0) #''' GET COMMAND '''
                    
                    if override[:1]=="!": #''' IF IS NOT A GCODE OVERRIDE COMMAND '''
                        
                        override_splitted = override.split(':')
                        
                        if override_splitted[0]=="!kill": #stop print
                            resetTrace()
                            trace("Terminating Process")
                            #kill the process
                            killed=True
                            EOF=True
                            break            
                        elif override_splitted[0]=="!pause":
                            if not paused:
                                serial.write("G0 X200 Y200\r\n") #move in the corner
                                trace("Print is now paused")
                                paused=True
                        elif override_splitted[0]=="!resume":
                            if paused:
                                trace("Resuming print")
                                paused=False
                        elif override_splitted[0]=="!z_plus":  
                            #z_override +=0.1
                            z_override += float(override_splitted[1])
                            serial.write("G91\r\n") 
                            serial.write("G0 Z+" + override_splitted[1] +"\r\n")  #move up
                            serial.write("G90\r\n") 
                            sent+=3
                            trace("<span class='override-command'>Z height incresed by "+ override_splitted[1]+" mm</span>")
                        elif override_splitted[0]=="!z_minus":
                            z_override -= float(override_splitted[1])                        
                            serial.write("G91\r\n") 
                            serial.write("G0 Z-"+ override_splitted[1] +"\r\n")  #move down
                            serial.write("G90\r\n") 
                            sent+=3
                            trace("<span class='override-command'>Z height decreased by " + override_splitted[1] +" mm</span>")
                        elif override_splitted[0]=="!shutdown_on":
                            trace("Auto-Shutdown engaged")
                            shutdown=True
                        elif override_splitted[0]=="!shutdown_off":
                            trace("Auto-Shutdown has been revoked")
                            #will not shutdown the machine.
                            shutdown=False
                        writeMonitor(progress,sent)   
                    else:# ''' END NOT GCODE OVERRIDE COMMAND '''
                        serial.write(override+"\r\n")
                        
                        if override[:4]!="M105":
                            override_data = gcode_utils.override_data(override)
                            trace(override_data[2])
                            if(override_data[0] == 'M106'):
                                fan = override_data[1]
                            writeMonitor(progress,sent)
                            
                                
                        sent+=1
                ''' END OVERRIDE COMMANDS '''
                        
                
                if z_override!=0:
                    search_z = re.search('Z', command)
                    if search_z != None:
                        get_z_match = re.search('(?<=Z)([+|-]*[0-9]*.[0-9]*)', command)
                        if get_z_match != None:
                            if is_number(get_z_match.group(1)):
                                new_z_c = float(get_z_match.group(1))+float(z_override)
                                command = command.replace(str(get_z_match.group(1)), str(new_z_c))
                
                '''if z_override!=0: # IF Z OVERRIDE
                    z_str = re.search('(?<=Z)([+|-]*[0-9]*.[0-9]*)', command)
                    if z_str:
                        z_c = z_str.group(1)
                        if is_number(z_c):
                            new_z_c = float(z_c)+float(z_override)
                            command = command.replace(str(z_c), str(new_z_c)) '''
                
                if print_started == False: #''' CHECK IF PRINT IS STARTED '''
                    if command[0:2]== "G0" or command[0:2]=="G1":
                        trace("Print Started")
                        print_started=True
                
                if command[0:4]=="M109":
                    ext_temp_target=float(command.split("S")[1].split(" ")[0].strip())
                    trace("Wait for nozzle temperature to reach "+ str(ext_temp_target)+"&deg;C")
                elif command[0:4]=="M104":
                    ext_temp_target=float(command.split("S")[1].split(" ")[0].strip())
                    trace("Nozzle temperature set to "+ str(ext_temp_target)+"&deg;C")
                    doWriteMonitor=True
                elif command[0:4]=="M140":
                    bed_temp_target=float(command.split("S")[1].strip())
                    trace("Bed temperature set to "+ str(bed_temp_target)+"&deg;C")
                    doWriteMonitor=True
                elif command[0:4]=="M190":
                    bed_temp_target=float(command.split("S")[1].strip())
                    trace("Wait for bed temperature to reach "+ str(bed_temp_target)+"&deg;C")
                elif command[0:4]=="M106":
                    fan=command.split("S")[1].strip()
                    if(fan == 0):
                        trace("Fan Off")
                    else:
                        trace("Fan value set to "+ str(int((float(fan) / 255) * 100)) + "%")
                    doWriteMonitor=True
                elif command[0:4]=="M107":
                    trace("Fan Off")
                    fan=0
                    doWriteMonitor=True
                    
                serial.write(command+"\r\n")
                if doWriteMonitor:
                    writeMonitor(progress,sent)    
                sent+=1    
            ''' END LINE VALID '''
        ''' end loop '''
    ''' end file possesion & close file '''
    EOF=True

''' GCODE SENDER THREAD
    Serial Port Listener '''
def listener():
    global received
    global sent
    global resend
    global EOF
    global ext_temp
    global bed_temp
    global ext_temp_target
    global bed_temp_target
    global tip
    global tipMessage
    global killed
    
    serial_in=""
    
    while not EOF:
        while serial_in=="":
            try:
                serial_in=serial.readline().rstrip()
                pass
            except SerialException as err:
                print "ERROR: " + str(err)
                trace(str(err))
        if serial_in=="ok":
            received+=1
        try: #''' READ SERIAL REPLYs '''
            if serial_in[:6]=="Resend":
                #resend line
                resend=serial_in.split(":")[1].rstrip()
                received-=1 #lost a line!
                trace("Error: Line no "+str(resend) + " has not been received correctly")
            elif serial_in[:4]=="ok T":
                temperatures = gcode_utils.read_temperature_line(serial_in)
                ext_temp = temperatures[0]
                ext_temp_target = temperatures[1]
                bed_temp = temperatures[2]
                bed_temp_target = temperatures[3]
                received+=1
            elif serial_in[:2]=="T:":
                temperatures = gcode_utils.red_wait_temperature_line(serial_in)
                ext_temp = temperatures[0]
                if temperatures[1] != '':
                    bed_temp = temperatures[1]
        except IndexError as err:
            trace(str(err))
        serial_in=""
        if(sent>20 and bed_temp < 45):
            tip=True
            tipMessage="the bed is cooling check connections"
        elif(sent>20 and bed_temp > 45):
            tip=False
            tipMessage=""

def tracker():
    global sent
    global lenght
    global EOF
    global tip
    global tipMessage
    global progress
    global print_started
    global killed
    
    #mtime=os.path.getmtime(command_file) #update override file mtime.
    elapsed=0
    last_update=0

    started=time.time()
    
    while not EOF:
        if (time.time()-started>100 and sent<20):
            tip=True
            tipMessage="TIP: If the job hasn't started yet, check bed and head connections."
            started=time.time()
        elif(sent>20):
            tip=False
            tipMessage=""
            
        elapsed=time.time()-last_update
        if elapsed>5:
            progress = 100 * float(sent) / float(lenght)
            writeMonitor(progress,sent)
            if len(ovr_cmd)>0:# ''' request temp status once '''
                if ovr_cmd[len(ovr_cmd)-1]!="M105":
                    ovr_cmd.append("M105")
            else:
                ovr_cmd.append("M105")
            last_update=time.time()# ''' refresh counter '''



''' ========================================== STARTING PRINT ================== '''           

''' DEFINE ENGINE '''
engine = gcode_utils.who_generate_file(gcode_file)
if engine == 'CURA':
    is_cura=True
    layers=cura_utils.get_layers_count(gcode_file)
elif engine == 'SLIC3R':
    is_slic3r=True

event_handler = OverrideCommandsHandler(patterns=[command_file])
observer = Observer()
observer.schedule(event_handler, '/var/www/tasks/', recursive=True)
observer.start()

''' FIRST WRITE TO STATS FILE '''
writeMonitor(0,0)

''' PRELOAD GCODE FILE '''
with open(gcode_file) as f:
    for line in f:
        lenght+=1
f.close()

trace( "File loaded.")

''' START TRACKER THREAD '''
tracker = Thread(target=tracker)
tracker.start()
''' START LISTENER THREAD '''
listener = Thread(target=listener)
listener.start()
''' START SENDER THREAD '''         
sender = Thread(target=sender)
sender.start()


#wait EOF
while not EOF:
    pass
    
completed=True #''' SET PRINT COMPLETED ''' 
status="performed"

if not killed:
    resetTrace()
    trace("Program Completed...")
    completed_time=int(time.time())
    writeMonitor(100,lenght)
else:
    resetTrace()
    trace("Procedure Aborted")
    completed_time=int(time.time())
    writeMonitor(progress,sent)
    status="stopped"

trace("Now finalizing...")
if progress >= 0.2:
    trace("Moving to safe zone")
    serial.write("G91\r\n")
    serial.write("G0 E-5 F1000\r\n")
    serial.write("G0 Z+1 F1000\r\n")
    serial.write("G90\r\n")
    serial.write("G27 Z0\r\n")
    serial.write("G0 X210 Y210 F1000\r\n")
    time.sleep(10)

call (['sudo php /var/www/fabui/script/finalize.php '+str(task_id)+" print " +str(status)], shell=True)

#shudown the printer if requested
if shutdown:
    trace("Shutting down...")
    call(['echo "M729">/dev/ttyAMA0'], shell=True)
    time.sleep(10)
    call (['sudo shutdown -h now'], shell=True)

''' CLOSING ALL '''
observer.join()
tracker.join()
sender.join()
listener.join()
serial.close()
sys.exit()    
    