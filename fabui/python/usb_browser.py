import os, sys, getopt
import glob
import json
import ConfigParser


config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

media_folder =  config.get('system', 'usb_folder')
filter = False
path=media_folder + '/'
destination=""

filters_extensions=[".gcode",".nc",".gc",".stl",".obj"]

try:
    opts, args = getopt.getopt(sys.argv[1:],"hdpf",["help","dest=","path=","filter="])
except getopt.GetoptError as err:
    #Error handling for unknown or incorrect number of options
    print "Correct Use"
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
        path = path + arg 
    elif opt in ("-f", "--filter"):
        filter = True #if enabled show only 
        #print "filter enabled"

files=[]


if os.path.isdir(path):

    for fn in os.listdir(path):
        
        include=False
        
        if(os.path.isfile(path+"/"+fn)):
        
            extension = os.path.splitext(path+"/"+fn)[1]
            if extension in filters_extensions:
                include=True        
        elif(os.path.isdir(path+"/"+fn)):
            include=True
            fn = fn + "/"
            
        if(include):    
            files.append(fn)
            
    print json.dumps(files)
    if(destination != ""):
        dest_file= open(destination, 'w')
        print>>dest_file, json.dumps(files)
        dest_file.close()
else:
    print str(path) + " is not a directory or doesn't exists"