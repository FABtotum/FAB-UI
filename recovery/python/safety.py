import RPi.GPIO as GPIO
import time  
import sys,os
import serial

#realtime emergency management.
#if GPIO Pin 5 is set to low , emergency mode will be triggered.

GPIO.cleanup()

GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.IN, pull_up_down = GPIO.PUD_DOWN)

emergency=False #default emergency flag

safety_log_path="/var/www/temp/fab_ui_safety.json" #/var/www/temp

def switch_safety(emergency,status):
	status_string='{"state":{"emergency":"'+ str(emergency)+'","status":"'+ str(status)+'"}}'
	safety= open(safety_log_path, 'w+')  
	print>>safety, status_string
	return
	
switch_safety(0,"ok")  #safety log = safe!
#i=0
while True:
	if(GPIO.input(2) ==0):
		#Pin is set as low, switch to emergency mode!
		if not emergency:					
		
			switch_safety(1,"Emergency")
			emergency=True
			#DEBUG 
			#print("Emergency")
			
	if(GPIO.input(2) == 1):
		#normal ops
		if emergency:
			#disable emergency mode
			switch_safety(0,"ok")
			emergency=False
			#DEBUG
			#print("Safe")
	
	#print str(i)+ " Emergency :"+ str(emergency)
	#i+=1
	time.sleep(1)
GPIO.cleanup()