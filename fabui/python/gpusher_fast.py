#gpusher
import os,sys,time
import serial
from serial import SerialException
from threading import Thread
from subprocess import call
import re
import json
import ConfigParser
import logging


from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

#check if LOCK FILE EXISTS
if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()


#process params
try:
    ncfile=str(sys.argv[1])  #param for the gcode to execute
    logfile=str(sys.argv[2]) #param for the log file
    comfile=str(sys.argv[3]) #comand file
except:
    print "\nERROR missing critical params. Usage:\n python gpusher.py gcode.nc logfile.log comfile.txt\n optional: log_trace.log FABUI_task_id[INT]"
    sys.exit()

try:
    # =str(sys.argv[4])      #open slot
    log_trace=str(sys.argv[5])    #trace log file
    task_id=str(sys.argv[6])    #task ID
    print_type=str(sys.argv[7])    
except:
    print "running with no UI..."

#write LOCK FILE    
open(config.get('task', 'lock_file'), 'w').close()

logfile=config.get('task', 'monitor_file')
log_trace=config.get('task', 'trace_file')

logging.basicConfig(filename=log_trace,level=logging.INFO,format='%(message)s')

    
#debug    
print os.getpid()

isAdditive=False
eMode=False
if (print_type == "additive"):
    isAdditive = True
#ncfile ='/var/www/fabui/python/gcode.nc'
str_log=""
received=0

sent=0
ext_temp=bed_temp=0

#default starting temps (for UI feedback only)
ext_temp_target=180
bed_temp_target=60
fan=255


tip=False
tipMessage=""

#file
EOF=False
ovr_cmd=[]

progress=0
lenght=0
percent=0
sent=0
resend=0
started=last_update=time.time()
completed_time=0
resent=0
completed=0
z_override=0
print_started=False
total_layers=0
actual_layer=0
rpm=0

#overrides & controls
paused=False
shutdown=False #default shutdown printer on complete = no
killed=False  


def is_number(s):
    try:
        float(s)
        return True
    except ValueError:
        return False
    
def getLayers(file):
    layers=0
    for line in(open(file).readlines()):
        
        if(re.search('(?<=Z)([0-9]*.[0-9]*)', line)):
            layers = layers + 1
            
        #match = re.search("G[0-1] Z\d*\.?\d+", line.strip())
        #if match:
            #temp = match.group().split()
            #layers=temp[1].replace("Z", "")
            #break
    #return float(layers)*10
    return layers
    
def writeMonitor(percent,sent):
    
    global bed_temp_target    
    global ext_temp_target
    global ext_temp
    global bed_temp
    global tip
    global tipMessage
    global total_layers
    global actual_layer
    global fan
    global print_started
    global rpm
    
    _layers={'total':str(total_layers), 'actual': str(actual_layer)}
    _stats={"percent": str(percent), "line_number": str(sent), "extruder": str(ext_temp), "bed":str(bed_temp), "extruder_target":str(ext_temp_target), "bed_target": str(bed_temp_target), "z_override": str(z_override), "layers":_layers, "fan": str(fan), 'rpm':str(rpm) }
    _tip={"show":str(tip), "message": str(tipMessage)}
    _print = {"name": ncfile, "lines": str(lenght),  "print_started": str(print_started), "started": str(started), "paused": str(paused), "completed": str(completed), "completed_time": str(completed_time), "shutdown": str(shutdown), "tip": _tip, "stats": _stats}
    str_log = {"type": "print", "print": _print}
    
    #str_log='{"type":"print", "print":{"name": "'+ncfile+'","lines": "'+str(lenght)+'","started": "'+str(started)+'","paused": "'+str(paused)+'","completed": "'+str(completed)+'","completed_time": "'+str(completed_time)+'","shutdown": "'+str(shutdown)+'", "tip":{"show":"'+str(tip)+'", "message":"'+str(tipMessage)+'"}, "stats":{"percent":"'+str(percent)+'","line_number":'+str(sent)+',"extruder":'+str(ext_temp)+',"bed":'+str(bed_temp)+',"extruder_target":'+str(ext_temp_target)+',"bed_target":'+str(bed_temp_target)+',"z_override":'+str(z_override)+'}}}'
    #write log
    handle=open(logfile,'w+')
    print>>handle, json.dumps(str_log)
    handle.close()
    return    

def trace(string):
    #out_file = open(log_trace,"a+")
    #out_file.write(str(string) + "\n")
    #out_file.close()
    #headless
    #print string
    #print string
    logging.info(string) 
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
    
    if code=="M104":
        description+= "Nozzle temperature set to "+value+" &deg;C"
    elif code== "M140":
        description+= "Bed temperature set to "+value+" &deg;C"
    elif code=="M220":
        description+="Speed factor override set to "+value+"%"
    elif code=="M3":
        rpm=value
        description+="RPM speed set to "+value+""
    elif code=="!z_plus":
        description+="Z height incresed by 0.1 mm"
    elif code=="!z_minus":
        description+="Z height decreased by 0.1 mm"
    elif code=="M106":
        fan = value
        value = int((float(value) / 255) * 100)
        description+="Fan speed set to "+str(value)+"%"
    elif code=="M107":
        description+="Fan turn off"
        fan=0
    elif code=="M221":
        description+="Extruder factor override set to "+ str(value)+'%'
    else:
        description+="description none"
    
    description+="</span>"
    
    return description
    
#usage: print checksum(gcode,1)

def sender():
    global received
    global ncfile
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
    global rpm
    global progress
    
    
    gcode_line=0
    with open(ncfile, 'r+') as f:
        # this reads in one line at a time
        #trace("Print Started, now reaching temp")
        if(isAdditive):
            trace("Now reaching temperatures")
        
        for line in f:
            if EOF==True:
                break
            if killed==True:
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
                            #z_override -=0.1
                            z_override -= float(override_splitted[1])                        
                            serial.write("G91\r\n") 
                            serial.write("G0 Z-"+ override_splitted[1] +"\r\n")  #move down
                            serial.write("G90\r\n") 
                            sent+=3
                            trace("<span class='override-command'>Z height decreased by " + override_splitted[1] +" mm</span>")
                                            
                        elif override_splitted[0]=="!shutdown_on":
                            #will shutdown the machine after the print ends
                            trace("Auto-Shutdown engaged")
                            shutdown=True

                        elif override_splitted[0]=="!shutdown_off":
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
                
                #Z override calculation
                if z_override!=0:
                    #check if line is a z change.
                    #G1 Z0.100 F15000.000
                    #z_str = re.search('Z(.+?) ', line)
                    #z_str = re.search('(Z.*?) |(Z.*)', line)
                    #z_str = re.search('(?<=Z)([0-9]*.[0-9]*)', line)
                    
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
                        trace("Print Started")
                        print_started=True
                #if gcode_line<30:
                #UPDATE TARGET TEMP, only for early printing stage.
                    if isAdditive:
                        
                        if line[0:4]=="M109":
                            ext_temp_target=line.split("S")[1].strip()
                            trace("Wait for nozzle temperature to reach "+ str(ext_temp_target)+"&deg;C")
                        
                        elif line[0:4]=="M104":
                            ext_temp_target=line.split("S")[1].strip()
                            trace("Nozzle temperature set to "+ str(ext_temp_target)+"&deg;C")
                            doWriteMonitor=True
                        
                        elif line[0:4]=="M140":
                            bed_temp_target=line.split("S")[1].strip()
                            trace("Bed temperature set to "+ str(bed_temp_target)+"&deg;C")
                            doWriteMonitor=True
                            
                        elif line[0:4]=="M190":
                            bed_temp_target=line.split("S")[1].strip()
                            trace("Wait for bed temperature to reach "+ str(bed_temp_target)+"&deg;C")
                            
                        
                        elif line[0:4]=="M106":                        
                            fan=line.split("S")[1].strip()
                            if(fan == 0):
                                trace("Fan Off")
                            else:
                                trace("Fan value set to "+ str(int((float(fan) / 255) * 100)) + "%")
                            doWriteMonitor=True
                                
                        elif line[0:4]=="M107":
                            trace("Fan Off")
                            fan=0
                            doWriteMonitor=True
                    
                    #elif(re.search('(?<=Z)([0-9]*.[0-9]*)', line)):    
                    #elif(re.search("G[0-1] Z\d*\.?\d+", line)):
                        #match=re.search("G[0-1] Z\d*\.?\d+", line)
                        #match=re.search('(?<=Z)([0-9]*.[0-9]*)', line)
                        #tmp = match.group().split()
                        #actual_layer=float(tmp[1].replace("Z", ""))*10
                        #actual_layer=float(tmp[0])*10
                        #actual_layer = actual_layer + 1
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
                
            
            ##temperature report    
            #if(isAdditive):
            if serial_in[:4]=="ok T":
                #Collected M105: Get Extruder & bed Temperature (reply)
                #EXAMPLE:
                #ok T:219.7 /220.0 B:26.3 /0.0 T0:219.7 /220.0 @:35 B@:0
                #trace(serial_in);
                temps=serial_in.split(" ")
                
                if is_number(temps[1].split(":")[1]):
                    ext_temp=float(temps[1].split(":")[1])
                if is_number(temps[2].split("/")[1]):
                    ext_temp_target=float(temps[2].split("/")[1])
                #print ext_temp_target
                
                if is_number(temps[3].split(":")[1]):
                    bed_temp=float(temps[3].split(":")[1])
                
                if is_number(temps[4].split("/")[1]):
                    bed_temp_target=float(temps[4].split("/")[1])
                
                received+=1
            
                ## temp report (wait)    
            if serial_in[:2]=="T:":    
                #collected M109/M190 Snnn temp (Set temp and  wait until reached)
                #T:187.1 E:0 B:59
                #print serial_in
                temps=serial_in.split(" ")
                
                if is_number(temps[0].split(":")[1]):
                    ext_temp=float(temps[0].split(":")[1])
                if is_number(temps[2].split(":")[1]):
                    bed_temp=float(temps[2].split(":")[1])
        
        except IndexError as err:
            trace(str(err))
        #print "BED: "+str(bed_temp) + " EXT: "+ str(ext_temp)
        #ok is sent separately.
        
    #clear everything not recognized.
        serial_in=""
    
        if(sent>20 and bed_temp < 45):
            tip=True
            tipMessage="the bed is cooling check connections"
        elif(sent>20 and bed_temp > 45):
            tip=False
            tipMessage=""
        
    #print "listener closed"
        
def tracker():
    global sent
    global lenght
    global EOF
    global tip
    global tipMessage
    global progress
    global print_started
    global killed
    
    #mtime=os.path.getmtime(comfile) #update override file mtime.
    elapsed=0
    last_update=0

    started=time.time()
    
    while not EOF:
        #print "tracker: " + str(EOF) + " " + str(killed)
        
        '''if(print_started==False):
            if(time.time()-started>60):
                trace("Attenzione Problema")
                EOF=True
                killed=True'''
              
        if (time.time()-started>100 and sent<20):
            tip=True
            tipMessage="TIP: If the job hasn't started yet, check bed and head connections."
            #trace("<strong class='warning'>TIP: If the job hasn't started yet, check bed and head connections.</strong>")
            started=time.time()
            #trace("--> in " + str(sent))
        elif(sent>20):
            tip=False
            tipMessage=""
            #started=time.time()
            #trace("--> out " + str(sent))
            
        elapsed=time.time()-last_update
        if elapsed>5:
            #trace the progress
            progress = 100 * float(sent) / float(lenght)
            writeMonitor(progress,sent)
        
            #update the override comand queue each 5 seconds
            #if (os.path.getmtime(comfile)!=mtime): #force a new command if the comand override file has been modified recently
                #while(not(os.access(comfile, os.F_OK)) or not(os.access(comfile, os.W_OK))):
                    #time.sleep(0.5) #no hammering
                    #pass
                #file is readeable, can proceed
                #mtime=os.path.getmtime(comfile) #update file mtime.
                
                #append new command(s)
                #with open(comfile) as f:
                    #for line in f:
                        #ovr_cmd.append(line.rstrip())
                        
                #clear the override file
                #open(comfile, 'w').close() 
                
            
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
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.6)

serial.flushInput()

#preload
with open(ncfile) as f:
    for line in f:
        lenght+=1
f.close()

#DEBUG 
trace( "File loaded.")

if(isAdditive):
    trace("Additive mode")
else:
    trace("Subtractive Mode")
    
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
    writeMonitor(100,lenght)
else:
    trace("Procedure Aborted")
    completed_time=int(time.time())
    writeMonitor(progress,sent)
    status="stopped"

trace("Now finalizing...")
#serial.flushInput()
if print_type == "additive" and progress >= 0.2:
    trace("Moving to safe zone")
    #serial.write("G90\r\nG0 X210 Y210 Z240 F10000") #Setting Absolute movement and moving to safe zone
    serial.write("G91\r\n") #Setting Absolute movement and moving to safe zone
    serial.write("G0 E-5 F1000\r\n")
    serial.write("G0 Z+1 F1000\r\n")
    serial.write("G90\r\n")
    serial.write("G27 Z0\r\n")
    serial.write("G0 X210 Y210\r\n")

#remove LOCK FILE    
os.remove(config.get('task', 'lock_file'))    
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

observer.join()

#terminate operations
tracker.join()
#trace("Tracker.join")
#trace("tracker ok");

sender.join()
#trace("Sender.join")
#trace("sender ok");
listener.join()
#trace("Listener.join")
#trace("listener ok");
#trace("Done!");
serial.close()
#trace("Serial Close")
sys.exit()