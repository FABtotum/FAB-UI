import RPi.GPIO as GPIO
import time  
import sys,os
import serial
from ws4py.client.threadedclient import WebSocketClient
import ConfigParser
import json

#realtime emergency management.
#if GPIO Pin 5 is set to low , emergency mode will be triggered.
GPIO.cleanup()

GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)

emergency=False #default emergency flag

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

safety_log_path="/var/www/temp/fab_ui_safety.json" #/var/www/temp

def write_emergency(code):    
    if(code != ''):
        status_string='{"type": "emergency", "code": '+ code +'}'
    else:
        status_string='{"type": ""}'
    
    safety = open(safety_log_path, 'w+')
    print >> safety, status_string
    return

code=''
write_emergency(code)  #safety log = safe!

#wait 5 seconds

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.6)

host=config.get('socket', 'host')
port=config.get('socket', 'port')

ws = WebSocketClient('ws://'+host +':'+port+'/', protocols=['http-only', 'chat'])
ws.connect();

print "Connected to socket"

while True:
    
    if(GPIO.input(2) == GPIO.LOW):
        #Pin is set as low, switch to emergency mode!
        if not emergency:                    
            #read status
            
            serial.flushInput()
            serial.write("M730\r\n")
            #time.sleep(0.5)
            reply=serial.readline()

            try:
                code=float(reply.split("ERROR : ")[1].rstrip())
            except:
                code=100
            
            #print '{"type": "emergency", "code": '+ str(code) +'}'
            #ws.send('{"type": "emergency", "code": '+ str(code) +'}')
            
            message = {'type': 'emergency', 'code': str(code)}
            ws.send(json.dumps(message))
            emergency=True
            
            
    if(GPIO.input(2) == GPIO.HIGH):
        #normal ops
        if emergency:
            #disable emergency mode
            code=''
            emergency=False
            #send M999 TO RESET!
            #DEBUG
           
                
    write_emergency(str(code))
    
    time.sleep(0.5)
GPIO.cleanup()