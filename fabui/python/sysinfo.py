import serial, os
import ConfigParser
import re
import argparse
import json
from serial_utils import SerialUtils

parser = argparse.ArgumentParser()

parser.add_argument("o", help="output type",  default='json', nargs='?',)
args = parser.parse_args()

output = args.o

su = SerialUtils()
fw_version = su.fwVersion()
hw_version = su.hwVersion()

if output == 'json':
    sysinfo = {'fw': fw_version, 'hw': hw_version}
    print json.dumps(sysinfo)
    

    
    

