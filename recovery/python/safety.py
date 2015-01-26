import RPi.GPIO as GPIO
import time  
import sys,os
import serial

#realtime emergency management.
#if GPIO Pin 5 is set to low , emergency mode will be triggered.

def err_msg(code):
	if code == 0:
		msg="All Nominal"
	elif code == 100:
		msg= "General Safety Lockdown"
	elif code == 101:
		msg= "Printer stopped due to errors"
	elif code == 102:
		msg= "Front panel is open, cannot continue"
	elif code == 103:
		msg= "Head not properly locked in place"
	elif code == 104:
		msg= "Extruder Temperature critical, shutting down"
	elif code == 105:
		msg= "Bed Temperature critical, shutting down"
	elif code == 106:
		msg= "X max Endstop hit"
	elif code == 107:
		msg= "X min Endstop hit"
	elif code == 108:
		msg= "Y max Endstop hit"
	elif code == 109:
		msg= "Y min Endstop hit"
	else:
		msg="Unknown error Error code: ", code
	return msg
		
		
GPIO.cleanup()

GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)

emergency=False #default emergency flag

safety_log_path="/var/www/temp/fab_ui_safety.json" #/var/www/temp


def switch_safety(emergency,status):
	
	status_string='{"state":{"emergency":"'+ str(emergency) + '","status":"' + str(status)  + '"}}'
	safety = open(safety_log_path, 'w+')  
	
	print >> safety, status_string
	print "written" + str(emergency)
	return
switch_safety(0,"Ok")  #safety log = safe!

port = '/dev/ttyAMA0'
baud = 115200
serial = serial.Serial(port, baud, timeout=0.6)

while True:
	if(GPIO.input(2) == 0):
		#Pin is set as low, switch to emergency mode!
		if not emergency:					
			#read status
			
			serial.flushInput()
			serial.write("M730\r\n")
			time.sleep(0.5)
			reply=serial.readline()

			try:
				code=float(reply.split("ERROR : ")[1].rstrip())
			except:
				code=100
				
			status=err_msg(code)
			#print "[!] (",code,") "+str(status)
			
			#Write UI-Level emergency status JSON
			switch_safety(1,status)
			emergency=True
			
			
	if(GPIO.input(2) == 1):
		#normal ops
		if emergency:
			#disable emergency mode
			switch_safety(0,"ok")
			emergency=False
			#send M999 TO RESET!
			#DEBUG
			#print("Safe")
	
	#print str(i)+ " Emergency :"+ str(emergency)
	#i+=1
	time.sleep(1)
GPIO.cleanup()