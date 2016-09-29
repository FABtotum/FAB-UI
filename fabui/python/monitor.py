import time
from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler
from watchdog.events import FileSystemEventHandler
import ConfigParser
import json
from ws4py.client.threadedclient import WebSocketClient
import RPi.GPIO as GPIO
import logging
import os, sys
from subprocess import call,Popen,PIPE
import signal
from serial_utils import SerialUtils
import re

monitorPID = os.getpid()
            
config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

'''### ###'''
php_script_path = config.get('system', 'php_script_path')
python_script_path = config.get('system', 'python_script_path')

'''#### SAFETY ###'''
safety_file=config.get('safety', 'file')

'''##### MACRO ####'''
macro_status_file=config.get('macro', 'status_file')
macro_trace_file=config.get('macro', 'trace_file')
macro_response_file=config.get('macro', 'response_file')

'''#### TASKS ####'''
task_trace_file=config.get('task', 'trace_file')
task_monitor_file=config.get('task', 'monitor_file')
task_notifications_file=config.get('task', 'notifications_file')

'''### LOCK FILE ####'''
lock_file=config.get('task', 'lock_file')

'''### USB DEV FILE ###'''
dev_usb_file = config.get('system', 'dev_usb_file')
dev_path = config.get('system', 'dev_folder')
usb_folder = config.get('system', 'usb_folder') 

'''#### LOG ####'''
log_file=config.get('monitor', 'log_file')
logging.basicConfig(filename=log_file,level=logging.INFO,format='%(message)s')

'''### READ PRINTER SETTINGS ###'''
json_f = open(config.get('printer', 'settings_file'))
settings = json.load(json_f)

'''#### WEB SOCKET CLIENT ####'''
host=config.get('socket', 'host')
port=config.get('socket', 'port')
try:
    ws = WebSocketClient('ws://'+host +':'+port+'/')
    ws.connect();
    SOCKET_CONNECTED = True
except Exception as inst:
    print inst
    SOCKET_CONNECTED = False



'''#### SETTING GPIO ####'''
#GPIO.cleanup()
GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)


def killAndRaise():
    '''
    global php_script_path
    command = 'sudo php ' + php_script_path +'/kill_raise.php ' + str(monitorPID) + ' "python" "' + python_script_path + '/monitor.py"  &'
    print command
    os.system(command)
    '''
    
    global host
    global port
    global ws
    global SOCKET_CONNECTED
    try:
        ws = WebSocketClient('ws://'+host +':'+port+'/')
        ws.connect();
        SOCKET_CONNECTED = True
    except Exception as inst:
        SOCKET_CONNECTED = False
    

def write_emergency(str):        
    safety = open(safety_file, 'w+')
    print >> safety, str
    safety.close()
    return

def is_int(s):
    try: 
        int(s)
        return True
    except ValueError:
        return False
    
lasterr=0
last_status=2


def decodeReply(string):
    match = re.search('ERROR\s:\s([0-9.]+)', string, re.IGNORECASE)
    if match != None:
        return int(match.group(1))
    match = re.search('Error:Printer', string, re.IGNORECASE)
    if match != None:
        return 102
    return None

def safety_callback(channel):
    
    open(config.get('task', 'lock_file'), 'w').close()
    message = {'type': '', 'code': ''}
    write_emergency(json.dumps(message))
    su = SerialUtils()
    GPIO_STATUS=GPIO.input(2)
    type="emergency"
    if(GPIO_STATUS == 0):
        su.sendGCode('M730')
        reply = su.getReply()
        decodedReply =  decodeReply(reply)
        if decodedReply != None :
            special_codes = [110, 120, 121]
            if(decodedReply in special_codes):
                if decodedReply == 110:
                    type="alert"
                    su.flush()
                    su.sendGCode('M999')
                elif decodedReply == 120 or decodedReply == 121:
                    if(settings['bothy'] == 'Shutdown' or settings['bothz'] == 'Shutdown'):
                        call (['sudo php /var/www/fabui/application/modules/controller/ajax/shutdown.php'], shell=True)
                        GPIO.cleanup() #we can disable GPIO as we are rebooting the system.
                        
            message = {'type': type, 'code': str(decodedReply)}
            ws.send(json.dumps(message))
            write_emergency(json.dumps(message))
            
    su.flush()
    su.close()
    GPIO_STATUS=GPIO.HIGH   
    if os.path.isfile(config.get('task', 'lock_file')):
        os.remove(config.get('task', 'lock_file'))

'''
def safety_callback(channel):

    #reset error msg
    message = {'type': "", 'code': ""}
    write_emergency(json.dumps(message))
                
    global SOCKET_CONNECTED
    global lasterr
    global last_status
    
    code=0
    type=""

    GPIO_STATUS=GPIO.input(2)
    #print "----------------------------"
    #print "GPIO status: ", GPIO_STATUS
    if(GPIO_STATUS == 0):
        open(config.get('task', 'lock_file'), 'w').close()
        
        #### SERIAL PORT COMMUNICATION ####
        serial_port = serialconfig.get('serial', 'port')
        serial_baud = serialconfig.get('serial', 'baud')
        ser = serial.Serial(serial_port, serial_baud, timeout=0.6)
        
        ser.flushInput()
        ser.write("M730\r\n")
        reply=ser.readline()
        ser.flushInput()
        print "Reply: ", reply   
        type="emergency"
        
        try:
            
            if('Error:Printer' in reply):
                code = 102
            else:
                code=float(reply.split("ERROR : ")[1].rstrip())
                    
            #code management
            if (is_int(code) and code!=0):
                
                if(code == 110):
                    type="alert"
                    ser.flushInput()
                    ser.write("M999\r\n")
                        

                if(code == 120 or code==121):
                    
                    if 'bothy' in units and units['bothy']:
                        if (units['bothy']=="Shutdown" and type=="emergency" and int(code)==120):
                            #print "call shutdown 1"
                            call (['sudo php /var/www/fabui/application/modules/controller/ajax/shutdown.php'], shell=True)
                            
                            
                    if 'bothz' in units and units['bothz']:
                        if (units['bothz']=="Shutdown" and type=="emergency" and int(code)==121):
                            #print "call shutdown 2"
                            call (['sudo php /var/www/fabui/application/modules/controller/ajax/shutdown.php'], shell=True)
                                
                    GPIO.cleanup() #we can disable GPIO as we are rebooting the system.
                    
                else:                                 
                    message = {'type': str(type), 'code': str(code)}
                    write_emergency(json.dumps(message))
                    print json.dumps(message)
                    
                    try:
                        if(SOCKET_CONNECTED==False):
                            killAndRaise()
                        ws.send(json.dumps(message))
                            
                    except Exception, e:
                        logging.info(str(e))
                        killAndRaise()
                        
        except:
            print "Not recognized: " , code
            print "reply: ", reply
            print sys.exc_info()
            
                        
        #close serial
        ser.close();
        if os.path.isfile(config.get('task', 'lock_file')):
            os.remove(config.get('task', 'lock_file'))
        GPIO_STATUS=GPIO.HIGH                

'''       
#event manager
GPIO.add_event_detect(2, GPIO.BOTH, callback=safety_callback, bouncetime=300)


'''#### MONITOR HANDLER CLASS ####'''
class MonitorHandler(PatternMatchingEventHandler):
        
    def catch_all(self, event, op):
        
        global macro_trace_file
        global task_trace_file
        global macro_response_file
        global monitor_file
        global task_notifications_file
        global macro_status_file
        global ws
        global monitorPID
        global lock_file
        
        if event.is_directory:
            return
        
        try:
            
            obj = open(event.src_path, 'r')
            content= obj.read()
            obj.close()

            if(event.src_path == macro_trace_file):
                data = {'type': 'trace', 'content': str(content)}
                messageType="macro"
                
            elif(event.src_path == macro_response_file):
                data= {'type': 'response', 'content': str(content)}
                messageType="macro"
            
            elif(event.src_path == macro_status_file):
                messageType="macro"
                data=json.loads(content)
            
            
            elif(event.src_path == task_trace_file):
                data = {'type': 'trace', 'content': str(content)}
                messageType="task"
            
            elif(event.src_path == task_monitor_file):
                data = {'type': 'monitor', 'content': str(content)}
                messageType="task"
            
            elif(event.src_path == task_notifications_file):
                data=json.loads(content)
                messageType="task"
                   
            self.sendMessage(messageType, data)
        except Exception, e:
            print "Unexpected error:", str(e)
            killAndRaise()
        
                
    def on_modified(self, event):
        self.catch_all(event, 'MOD')
            
    def sendMessage(self, messageType, data):
        try:
            message = {'type': messageType, 'data':data}
            if SOCKET_CONNECTED:
                ws.send(json.dumps(message))
        except Exception, e:
            print "Unexpected error:", str(e)
            killAndRaise()
            
        


class UsbEventHandler (FileSystemEventHandler):
    def __init__(self, observer, filename):
        self.observer = observer
        self.usb_file = filename
        
    def on_created(self, event):
        global ws
        if(event.src_path == self.usb_file):
            #mount usb disk usb_folder
            #os.system('sudo mount ' + dev_usb_file + ' ' + usb_folder)
            
            data={'type': 'usb', 'status': True, 'alert':True}
            message={'type':'system', 'data':data}
            if SOCKET_CONNECTED:
                ws.send(json.dumps(message))
    
    def on_deleted(self, event):
        global ws
        
        if(event.src_path == self.usb_file):
            #unmount usb disk
            #os.system('sudo umount ' + usb_folder)
            data={'type': 'usb', 'status':False, 'alert': True}
            message={'type': 'system', 'data':data}
            if SOCKET_CONNECTED:
                ws.send(json.dumps(message))
                

class LockFileHandler (FileSystemEventHandler):
    def __init__(self, observer, filename):
        self.observer = observer
        self.lock_file = filename
    
    def on_created(self, event):
        global ws
        
        if(event.src_path == self.lock_file):
            data={'type': 'lock', 'status': True, 'alert':True}
            message={'type': 'system', 'data':data}
            if SOCKET_CONNECTED:
                ws.send(json.dumps(message))
            
    def on_deleted(self, event):
        global ws
        
        if(event.src_path == self.lock_file):
            data={'type': 'lock', 'status': False, 'alert':True}
            message={'type': 'system', 'data':data}
            
            if SOCKET_CONNECTED:
                ws.send(json.dumps(message))
    
        

'''### FABUI FILE MONITOR ###'''        
event_handler = MonitorHandler(patterns=[macro_trace_file,macro_response_file, task_trace_file, task_monitor_file, task_notifications_file, macro_status_file])
observer = Observer()
observer.schedule(event_handler, '/var/www/temp/', recursive=False)
observer.start()

'''### USB MONITOR ###'''
usb_observer = Observer()
usb_event_handler = UsbEventHandler(usb_observer, dev_usb_file)
usb_observer.schedule(usb_event_handler, dev_path, recursive=False)
usb_observer.start()

'''### USB MONITOR ###'''
lock_observer = Observer()
lock_event_handler = LockFileHandler(lock_observer, lock_file)
lock_observer.schedule(lock_event_handler, '/var/www/temp/', recursive=False)
lock_observer.start()

try:
    observer.join()
    usb_observer.join()
    lock_observer.join()
except KeyboardInterrupt:
    observer.stop()
    GPIO.cleanup()
    