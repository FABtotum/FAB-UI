import serial, os
import ConfigParser
import re
import argparse
import json
from serial_utils import SerialUtils
from picamera_utils import PiCameraUtils


config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

parser = argparse.ArgumentParser()

parser.add_argument("o", help="output type",  default='json', nargs='?',)
args = parser.parse_args()

output = args.o

open(config.get('task', 'lock_file'), 'w').close()

su = SerialUtils()
fw_version = su.fwVersion()
fw_date    = su.fwBuildDate()
fw_author  = su.fwAuthor()
hw_version = su.hwVersion()
piCamera   = PiCameraUtils()



if output == 'json':
    sysinfo = {
        'fw': {
            'version' : fw_version,
            'build_date' : fw_date,
            'author' : fw_author
         }, 
        'hw': hw_version,
        'camera': {
            'version' : piCamera.version()
        }
    }
    print json.dumps(sysinfo)

if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))
    

    
    

