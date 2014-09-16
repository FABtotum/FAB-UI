#!/usr/bin/python
import os, sys, getopt
import glob
import json
	

filter=False
destination=""
path="/media" #default dir

usage='sudo python usb_browser.py -d<destination> -p<path to analize> -f<filter flag>'

try:
	opts, args = getopt.getopt(sys.argv[1:],"hdpf",["help","dest=","path=","filter="])
except getopt.GetoptError as err:
	#Error handling for unknown or incorrect number of options
	print "\nCorrect Use"
	print usage
	print err
	
	sys.exit(2)
for opt, arg in opts:
	if opt == '-h':
		print usage
		sys.exit()
	elif opt in ("-d", "--dest"):
		destination = arg 
	elif opt in ("-p", "--path"):
		path= "/media" + arg 
	elif opt in ("-f", "--filter"):
		filter = True #if enabled show only 
		#print "filter enabled"
	  		 
#def compose_folder():
#compose json

if os.path.isdir(path):
	os.chdir(path)
	
	if filter: 	
		#enable filter
		files_ext=("*.gcode","*.nc","*.gc","*.stl","obj","*/")
	elif not filter:
		#show all
		files_ext=("*")

	grabbed = []
	for files in files_ext:
		grabbed.extend(glob.glob(files))

	for ind,file in enumerate(grabbed):
		if file.endswith("/"):
			#strip away useless /
			file=file[:-1]
			
		file=path+"/"+file
		grabbed[ind]=file
				
		#print file

	dest_file= open(destination, 'w')  
	print>>dest_file, json.dumps(grabbed) #dumps to json

	
if not os.path.isdir(path):
	print "could not open external drive" 