import os, sys
import time
import serial
from subprocess import call

#startup script (see crontab)
time.sleep(60) #wait 60 seconds so connections can be made.

#tell the board that the raspi has been connected.

ser = serial.Serial("/dev/ttyAMA0",115200,timeout=1)
ser.flushInput()
ser.flushOutput()

ser.write('M728\r\n') #machine alive

time.sleep(0.5) 

#ENABLE SAFETY 
call (["sudo python /var/www/recovery/python/safety.py"], shell=True) #the script will run forever.

#clean the buffer and leave
serial.flush()
serial.close()

#quit
sys.exit()
#end