#!/usr/bin/python
#Force Totumduino Reset
import RPi.GPIO as GPIO
import time,sys
import serial

GPIO.cleanup()
GPIO.setmode(GPIO.BOARD)

def reset():
  pin = 11
  GPIO.setup(pin, GPIO.OUT)
  GPIO.output(pin, GPIO.HIGH)
  time.sleep(0.12)
  GPIO.output(pin, GPIO.LOW)
  time.sleep(0.12)
  GPIO.output(pin, GPIO.HIGH)

reset()

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.5)
serial.flushInput()

time.sleep(5)
serial.write("M728\r\n")

serial.flush()
serial.close()
print "done"
GPIO.cleanup()
sys.exit()
