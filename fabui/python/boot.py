#!/usr/bin/env python
from serial_utils import SerialUtils
import ConfigParser, json, os, time, argparse
from subprocess import call

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

open(config.get('task', 'lock_file'), 'w+').close()


parser = argparse.ArgumentParser()
parser.add_argument("-R", "--reset", action="store_true")
parser.add_argument("-f", "--flush", action="store_true")
parser.add_argument("-s", "--save",  action="store_true")
parser.add_argument("-d", "--debug", action="store_true")
args = parser.parse_args()

hardware_reset = args.reset
flush          = args.flush
save_settings  = args.save
debug          = args.debug

commands = []

if(os.path.isfile(config.get('printer', 'settings_file')) == False):
    call(["sudo php /var/www/fabui/index.php settings recreateSettingsFiles"],shell=True)
#read config steps/units
settings_file = open(config.get('printer', 'settings_file'))
settings = json.load(settings_file)

su = SerialUtils(debug = debug)

fw_version = su.fwVersion()
hw_version = su.hwVersion()


def saveSettings(data, file):
    ### save config file
    with open(file, 'w') as outfile:
        json.dump(data, outfile)
    outfile.close()

def customHardware(serialUtils, settings, settings_file):
    """
    Revision for customs edits
    """
    if(debug):
        print "Harware Revision: Custom"
    ### send extra commands
    with open(settings['custom']['overrides'], 'r') as custom_overrides_file:
        for line in custom_overrides_file:
            commands.append(line.strip())
    logic = 1 if settings['custom']['invert_x_endstop_logic'] == True else 0
    ### invert x endstop logic
    commands.append('M747 X{0}'.format(logic))
    #commands.append('M500')
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
    if(debug):
        print "Harware Revision: 1"
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
    if(debug):
        print "Harware Revision: 2"
    ### invert x endstop logic
    commands.append('M747 X1')
    ### Maximum feedrates (mm/s):
    commands.append('M203 X550.00 Y550.00 Z15.00 E12.00')
    ### save config file
    settings['hardware']['id'] = 2
    settings['feeder']['show'] = True
    settings['a'] = 177.777778
    saveSettings(settings, settings_file)
    return None

def hardware3(serialUtils, settings, settings_file):
    if(debug):
        print "Harware Revision: 3"
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
    commands.append('M747 X1')
    ### Maximum feedrates (mm/s):
    commands.append('M203 X550.00 Y550.00 Z15.00 E12.00')
    ### save config file
    settings['hardware']['id'] = 3
    settings['feeder']['show'] = False
    settings['a'] = 177.777778
    saveSettings(settings, settings_file)
    return None

def hardware4(serialUtils, settings, settings_file):
    """
    Rev4:
    """
    if(debug):
        print "Harware Revision: 4"
    ### invert x endstop logic
    commands.append('M747 X1')
    ### Maximum feedrates (mm/s):
    ### save config file
    settings['hardware']['id'] = 4
    settings['feeder']['show'] = False
    settings['a'] = 88.888889
    saveSettings(settings, settings_file)
    return None

def hardware5(serialUtils, settings, settings_file):
    """
    - Rev5:
    """
    if(debug):
        print "Harware Revision: 5"
    ### invert x endstop logic
    commands.append('M747 X1')
    ### Maximum feedrates (mm/s):
    commands.append('M203 X550.00 Y550.00 Z15.00 E23.00')
    ### save config file
    settings['hardware']['id'] = 5
    settings['feeder']['show'] = False
    settings['a'] = 88.888889
    saveSettings(settings, settings_file)
    return None

def hybridHead(serialUtils, settings):
    if(debug):
        print "Set Hybrid Head"
    ### set pid
    commands.append('M301 P15 I5 D30')
    ### set head id 
    commands.append('M793 S1')
    return None
def printHead2(serialUtils, settings):
    if(debug):
        print "Set Printing Head"
    #commands = []
    ### set pid
    commands.append('M301 P20 I3.5 D30')
    ### set head id 
    commands.append('M793 S2')
    return None
def millHead2(serialUtils, settings):
    if(debug):
        print "Set Milling Head"
    ### set head id 
    commands.append('M793 S3')
    return None

def laserHead1(serialUtils, settings):
    if(debug):
        print "Set Laser Head"
    ### set head id 
    commands.append('M794 S4')

HW_VERSION_CMDS = {
 'custom' : customHardware,
 '1'      : hardware1,
 '2'      : hardware2,
 '3'      : hardware3,
 '4'      : hardware4,
 '5'      : hardware5
}

HEAD_VERSION_CMDS = {
 'hybrid'  : hybridHead,
 'print_v2': printHead2,
 'mill_v2' : millHead2,
 'laser_v1': laserHead1
}
"""
START BOOTSTRAP
"""
if(hardware_reset):
    if(debug):
        print "Hardware Reset"
    su.reset()
    time.sleep(3)

if(flush):
    if(debug):
        print "Flush"
    su.flush()

if(hardware_reset == False):
    if(debug):
        print "Not hardware Reset"
    commands.append('M300')

### rise probe
commands.append('M402')
### laser off
commands.append('M60 S0')
commands.append('M61 S0')
### set safety door open: enable/disable warnings
commands.append('M732 S{0}'.format(settings['safety']['door']))
### set collision-warning enable/disable warnings
commands.append('M734 S{0}'.format(settings['safety']['collision-warning']))
### set homing preferences
commands.append('M714 S{0}'.format(settings['switch']))

""" install head """
if(settings['hardware']['head']['type'] in HEAD_VERSION_CMDS):
    HEAD_VERSION_CMDS[settings['hardware']['head']['type']](su, settings)
else:
    hybridHead(su, settings)

""" exec hardware revisions """
if(settings['settings_type'] == 'custom'):
    customHardware(su, settings, config.get('printer', 'settings_file'))
elif hw_version in HW_VERSION_CMDS:
    HW_VERSION_CMDS[hw_version](su, settings, config.get('printer', 'settings_file'))
else:
    settings['feeder']['show'] = True
    saveSettings(settings, config.get('printer', 'settings_file'))
    
if(save_settings):    
    eeprom        = su.eeprom()
    settings['e'] = eeprom['steps_per_unit']['e']
    saveSettings(settings, config.get('printer', 'settings_file'))    
#### save all to EEPROM
commands.append('M500')
### alive machine
commands.append('M728')
### set ambient lights ###
commands.append('M701 S{0}'.format(settings['color']['r']))
commands.append('M702 S{0}'.format(settings['color']['g']))
commands.append('M703 S{0}'.format(settings['color']['b']))
su.sendGCode(commands)
su.close()
if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))


