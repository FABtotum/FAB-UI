#!/usr/bin/python
#Force Totumduino Reset
import RPi.GPIO as GPIO
import time,sys
import serial
import json
import ConfigParser


config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

json_f = open(config.get('printer', 'settings_file'))
settings = json.load(json_f)




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

time.sleep(5)
serial.write("M728\r\n")
serial.flush()

serial.write("M701 S"+str(settings['color']['r'])+"\r\n")
serial.write("M702 S"+str(settings['color']['g'])+"\r\n")
serial.write("M703 S"+str(settings['color']['b'])+"\r\n")
#SAFETY

try:
    safety_door = settings['safety']['door']
except KeyError:
    safety_door = 0

serial.write("M732 S"+str(safety_door)+"\r\n")

try:
    switch = settings['switch']
except KeyError:
    switch = 0

serial.write("M714 S"+str(switch)+"\r\n")


serial.flush()
serial.close()

GPIO.cleanup()
sys.exit()
