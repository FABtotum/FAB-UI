#!/usr/bin/env python
from serial_utils import SerialUtils
import ConfigParser, json, os, time, argparse

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

open(config.get('task', 'lock_file'), 'w+').close()

parser = argparse.ArgumentParser()
parser.add_argument("-R", "--reset", action="store_true")
parser.add_argument("-f", "--flush", action="store_true")
args = parser.parse_args()
hardware_reset = args.reset
flush = args.flush

#read config steps/units
settings_file = open(config.get('printer', 'settings_file'))
settings = json.load(settings_file)

#process params
if 'settings_type' in settings and settings['settings_type'] == 'custom':
    settings_file = open(config.get('printer', 'custom_settings_file'))
    settings = json.load(settings_file)
settings_file.close()

su = SerialUtils()

fw_version = su.fwVersion()
hw_version = su.hwVersion()


def customHardware(serialUtils, settings, settings_file):
    """
    Revision for customs edits
    """
    ### send extra commands
    with open(settings['custom_overrides'], 'r') as custom_overrides_file:
        for line in custom_overrides_file:
            serialUtils.sendGCode(line.strip())
    logic = 1 if settings['invert_x_endstop_logic'] == True else 0
    ### invert x endstop logic
    serialUtils.sendGCode(('M747 X%s' % logic))
    serialUtils.sendGCode('M500')
    """ 
    save settings file
    """
    saveSettings(settings, settings_file)
    return None
def hardware1(serialUtils, settings, settings_file):
    """
    Rev1: September 2014 - May 2015
    - Original FABtotum
    """
    ### save config file
    settings['hardware']['id'] = 1
    settings['feeder']['show'] = True
    settings['a'] = 177.777778
    saveSettings(settings, settings_file)
    return None

def hardware2(serialUtils, settings, settings_file):
    """
    Rev2: June 2015 - August 2015
    - Simplified Feeder (Removed the disengagement and engagement procedure), if you want you can update it easily following this Tutorial: Feeder update.
    - Bowden tube improvement (Added a protection external sleeve to avoid the bowden tube get stuck in the back panel).
    - Endstops logic inverted.
    """
    ### invert x endstop logic
    serialUtils.sendGCode("M747 X1")
    ### Maximum feedrates (mm/s):
    serialUtils.sendGCode("M203 X550.00 Y550.00 Z15.00 E12.00")
    ### save to eeprom
    serialUtils.sendGCode("M500")
    ### save config file
    settings['hardware']['id'] = 2
    settings['feeder']['show'] = True
    settings['a'] = 177.777778
    saveSettings(settings, settings_file)
    return None

def hardware3(serialUtils, settings, settings_file):
    """
    Rev3: Aug 2015 - Jan 2016
    - Back panel modified to minimize bowden tube collisions
    - Hotplate V2 as standard duty hotplate
    - Reed sensor (Contactless sensor for the frontal door)
    - Head V1 (hybrid) discontinued
    - Milling Head V2 (store.fabtotum.com/eu/store/milling-head-v2.html).
    - Print head V2 (store.fabtotum.com/eu/store/printing-head-v2.html).
    """
    ### invert x endstop logic
    serialUtils.sendGCode("M747 X1")
    ### Maximum feedrates (mm/s):
    serialUtils.sendGCode("M203 X550.00 Y550.00 Z15.00 E12.00")
    ### save to eeprom
    serialUtils.sendGCode("M500")
    ### save config file
    settings['hardware']['id'] = 3
    settings['feeder']['show'] = False
    settings['a'] = 177.777778
    saveSettings(settings, settings_file)
    return None

def hardware4():
    """
    Rev4:
    """
    ### invert x endstop logic
    serialUtils.sendGCode("M747 X1")
    ### save to eeprom
    serialUtils.sendGCode("M500")
    ### save config file
    settings['hardware']['id'] = 4
    settings['feeder']['show'] = False
    settings['a'] = 88.888889
    saveSettings(settings, settings_file)
    return None

def hardware5():
    """
    - Rev4:
    """
    ### invert x endstop logic
    serialUtils.sendGCode("M747 X1")
    ### save to eeprom
    serialUtils.sendGCode("M500")
    ### save config file
    settings['hardware']['id'] = 5
    settings['feeder']['show'] = False
    settings['a'] = 88.888889
    saveSettings(settings, settings_file)
    return None

def saveSettings(data, file):
    ### save config file
    with open(file, 'w') as outfile:
        json.dump(data, outfile)
    outfile.close()

def hybridHead(serialUtils, settings):
    ### set pid
    serialUtils.sendGCode('M301 P15 I5 D30') 
    ### set fw version
    serialUtils.sendGCode('M793 S1')
    ### save to eeprom
    serialUtils.sendGCode('M500')
    return None
def printHead2(serialUtils, settings):
    ### set pid
    serialUtils.sendGCode('M301 P20 I3.5 D30')
    ### set fw version
    serialUtils.sendGCode('M793 S2')
    ### save to eeprom
    serialUtils.sendGCode('M500')
    return None
def millHead2(serialUtils, settings):
    ### set fw version
    serialUtils.sendGCode('M793 S3')
    ### save to eeprom
    serialUtils.sendGCode('M500')
    return None

HW_VERSION_CMDS = {
 'custom' : customHardware,
 '1'      : hardware1,
 '2'      : hardware2,
 '3'      : hardware3,
 '4'      : hardware4,
 '5'      : hardware5
}

HEAD_VERSION_CMDS = {
 'hybrid' : hybridHead,
 'print_v2': printHead2,
 'mill_v2': millHead2
}
"""
START BOOTSTRAP
"""
if(hardware_reset):
    su.reset()
    time.sleep(3)

if(flush):
    su.flush()

if(hardware_reset == False):
    su.sendGCode('M300')
    su.sendGCode('M701 S0\r\nM702 S0\r\nM703 S0')

### rise probe
su.sendGCode('M402')
time.sleep(1)
### set ambient colors
su.sendGCode(('M701 S%s\r\nM702 S%s\r\nM703 S%s' % (settings['color']['r'], settings['color']['g'], settings['color']['b'])))
### set safety door open: enable/disable warnings
su.sendGCode(('M732 S%s' % settings['safety']['door']))
### set collision-warning enable/disable warnings
su.sendGCode(('M734 S%s' % settings['safety']['collision-warning']))
### set homing preferences
su.sendGCode(('M714 S%s' % settings['switch']))

""" install head """
if(settings['hardware']['head']['type'] in HEAD_VERSION_CMDS):
    HEAD_VERSION_CMDS[settings['hardware']['head']['type']](su, settings)
else:
    hybridHead(su, settings)

""" exec hardware revisions """
if(settings['settings_type'] == 'custom'):
    customHardware(su, settings, config.get('printer', 'custom_settings_file'))
elif hw_version in HW_VERSION_CMDS:
    HW_VERSION_CMDS[hw_version](su, settings, config.get('printer', 'settings_file'))
else:
    settings['feeder']['show'] = True
    saveSettings(settings, config.get('printer', 'settings_file'))

### alive machine
su.sendGCode('M728')
su.close()
if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))


