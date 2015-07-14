#!/usr/bin/python
#Force Totumduino Reset
import RPi.GPIO as GPIO
import time,sys
import serial
import ConfigParser
import logging



config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')


trace_file=config.get('macro', 'trace_file')
response_file=config.get('macro', 'response_file')
logging.basicConfig(filename=trace_file,level=logging.INFO,format='%(message)s')

open(trace_file, 'w').close() #reset trace file



def trace(string):
    logging.info(string)
    return


trace("Start reset controller...")

GPIO.cleanup()
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)

def reset():
  pin = 11
  GPIO.setup(pin, GPIO.OUT)
  GPIO.output(pin, GPIO.HIGH)
  time.sleep(0.12)
  GPIO.output(pin, GPIO.LOW)
  time.sleep(0.12)
  GPIO.output(pin, GPIO.HIGH)

reset()

serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')

serial = serial.Serial(serial_port, serial_baud, timeout=0.5)
serial.flushInput()

serial.flush()
serial.close()

GPIO.cleanup()

trace("Controller ready")
sys.exit()
