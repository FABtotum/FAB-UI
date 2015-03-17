import time
from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler
from watchdog.events import FileSystemEventHandler
import ConfigParser
import json
from ws4py.client.threadedclient import WebSocketClient
import serial
import RPi.GPIO as GPIO
import logging
import os, sys


monitorPID = os.getpid()

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')


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

'''### USB DEV FILE ###'''
dev_usb_file = config.get('system', 'dev_usb_file')
dev_path = config.get('system', 'dev_folder') 

'''#### LOG ####'''
log_file=config.get('monitor', 'log_file')
logging.basicConfig(filename=log_file,level=logging.INFO,format='[%(asctime)s] - %(message)s', datefmt='%d/%m/%Y %H:%M:%S')


'''#### WEB SOCKET CLIENT ####'''
host=config.get('socket', 'host')
port=config.get('socket', 'port')
ws = WebSocketClient('ws://'+host +':'+port+'/', protocols=['http-only', 'chat'])
ws.connect();

'''#### SERIAL PORT COMMUNICATION ####'''
serail_port = config.get('serial', 'port')
serail_baud = config.get('serial', 'baud')
serial = serial.Serial(serail_port, serail_baud, timeout=0.6)

'''#### SETTING GPIO ####'''
GPIO.cleanup()
GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)


def write_emergency(str):        
    safety = open(safety_file, 'w+')
    print >> safety, str
    return


def safety_callback(channel):
    
    try:
        code=""
        type=""
        
        if(GPIO.input(2) == GPIO.LOW):
            #todo
            type="emergency"
            serial.flushInput()
            serial.write("M730\r\n")
            reply=serial.readline()
            
            try:
                code=float(reply.split("ERROR : ")[1].rstrip())
            except:
                code=100
            
        
        if(GPIO.input(2) == GPIO.HIGH):
            #to do
            type=""
            code=""
                
        message = {'type': str(type), 'code': str(code)}
        ws.send(json.dumps(message))
        write_emergency(json.dumps(message))
        
    except Exception, e:
        logging.info(str(e))
        
 
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
        
        if event.is_directory:
            return

        if(event.src_path == macro_trace_file):
            content= open(macro_trace_file, 'r').read()
            data = {'type': 'trace', 'content': str(content)}
            messageType="macro"
            #message = {'type': 'macro', 'data': data}
            
        
        elif(event.src_path == macro_response_file):
            content= open(macro_response_file, 'r').read()
            data= {'type': 'response', 'content': str(content)}
            messageType="macro"
            #message = {'type': 'macro', 'data': data}
            #ws.send(json.dumps(message))
        
        elif(event.src_path == macro_status_file):
            messageType="macro"
            data=json.loads(open(macro_status_file, 'r').read())
        
        
        elif(event.src_path == task_trace_file):
            content= open(task_trace_file, 'r').read()
            data = {'type': 'trace', 'content': str(content)}
            messageType="task"
            #message = {'type': 'task', 'data': data}
            #ws.send(json.dumps(message))
        
        elif(event.src_path == task_monitor_file):
            content=open(task_monitor_file, 'r').read()
            data = {'type': 'monitor', 'content': str(content)}
            messageType="task"
            #message = {'type': 'task', 'data': data}
        
        elif(event.src_path == task_notifications_file):
            data=json.loads(open(task_notifications_file, 'r').read())
            messageType="task"
            #message = {'type': 'tasks', 'data': str(content)}
               
        self.sendMessage(messageType, data)
        
                
    def on_modified(self, event):
        self.catch_all(event, 'MOD')
            
    def sendMessage(self, messageType, data):
        try:
            message = {'type': messageType, 'data':data}
            ws.send(json.dumps(message))
        except:
            print "Unexpected error:", sys.exc_info()[0]
            cmd = 'sudo php /var/www/fabui/script/kill_raise.php ' + str(monitorPID) + ' &'
            os.system(cmd)
        


class UsbEventHandler (FileSystemEventHandler):
    def __init__(self, observer, filename):
        self.observer = observer
        self.usb_file = filename
        
    def on_created(self, event):
        global ws
        
        if(event.src_path == self.usb_file):
            data={'type': 'usb', 'status': True, 'alert':True}
            message={'type':'system', 'data':data}
            ws.send(json.dumps(message))
    
    def on_deleted(self, event):
        global ws
        
        if(event.src_path == self.usb_file):
            data={'type': 'usb', 'status':False, 'alert': True}
            message={'type': 'system', 'data':data}
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

try:
    observer.join()
    usb_observer.join()
except KeyboardInterrupt:
    observer.stop()
    GPIO.cleanup()
    