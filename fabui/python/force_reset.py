#!/usr/bin/python
#Force Totumduino Reset
import RPi.GPIO as GPIO
import time,sys
import serial
import ConfigParser
import logging
import os
config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

serialconfig = ConfigParser.ConfigParser()
serialconfig.read('/var/www/lib/serial.ini')
#write LOCK FILE  
open(config.get('task', 'lock_file'), 'w').close()

trace_file=config.get('macro', 'trace_file')
response_file=config.get('macro', 'response_file')
logging.basicConfig(filename=trace_file,level=logging.INFO,format='%(message)s')

open(trace_file, 'w').close() #reset trace file

def trace(string):
    logging.info(string)
    return


#trace("Start reset controller...")

#GPIO.cleanup()
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)

def reset():
  pin = 11
  GPIO.setup(pin, GPIO.OUT)
  GPIO.output(pin, GPIO.HIGH)
  time.sleep(0.15)
  GPIO.output(pin, GPIO.LOW)
  time.sleep(0.15)
  GPIO.output(pin, GPIO.HIGH)

reset()
GPIO.cleanup()


serial_port = serialconfig.get('serial', 'port')
serial_baud = serialconfig.get('serial', 'baud')

serial = serial.Serial(serial_port, serial_baud, timeout=0.5)
serial.flush()
serial.flushInput()
serial.flushOutput()
serial.close()
#trace("Controller ready")
#write_status(False)
#os.remove(config.get('task', 'lock_file'))

