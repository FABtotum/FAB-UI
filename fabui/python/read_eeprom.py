import serial, os
import argparse
import json
from serial_utils import SerialUtils

parser = argparse.ArgumentParser()

parser.add_argument("o", help="output type",  default='json', nargs='?',)
args = parser.parse_args()

output = args.o
su = SerialUtils()
eeprom = su.eeprom()

if output == 'json':
    print json.dumps(eeprom)
