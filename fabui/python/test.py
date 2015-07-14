import time
from watchdog.observers import Observer
from watchdog.events import PatternMatchingEventHandler
from watchdog.events import FileSystemEventHandler
import ConfigParser
import json
from ws4py.client.threadedclient import WebSocketClient
import serial
import RPi.GPIO as GPIO
import logging
import os, sys

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

'''### READ PRINTER SETTINGS ###'''
json_f = open(config.get('printer', 'settings_file'))
units = json.load(json_f)



print units

if 'bothy' in units and units['bothy']:
    print "exists"
else:
    print "doesnt exists"
