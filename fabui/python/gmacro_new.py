#!/usr/bin/env python
import os, time, sys
import argparse, logging
import json
import ConfigParser
import serial_utils
#from serial_utils import SerialUtils

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

parser = argparse.ArgumentParser()

parser.add_argument("-m", "--macro",    help="macro to execute",       type=str, required=True)
parser.add_argument("-t", "--trace",    help="log travce file",        default=config.get('macro', 'trace_file'))
parser.add_argument("-r", "--response", help="log response file",      default=config.get('macro', 'response_file'))
parser.add_argument("-d", "--debug",    help="Debug: print console",   action="store_true")
parser.add_argument("-p1", "--param1",  help="First extra parameter",  default=None)
parser.add_argument("-p2", "--param2",  help="Second extra parameter", default=None)
args = parser.parse_args()

""" ### init  ### """
macro_name    = args.macro
trace_file    = args.trace
response_file = args.response
param1        = args.param1
param2        = args.param2
debug         = args.debug

settings_file = open(config.get('printer', 'settings_file'))
settings = json.load(settings_file)

SECURITY_Z_OFFSET = 50
LASER_FOCUS_OFFSET = 2

'''
if 'settings_type' in settings and settings['settings_type'] == 'custom':
    settings_file = open(config.get('printer', 'custom_settings_file'))
    settings = json.load(settings_file)
settings_file.close()
'''
#write LOCK FILE    
open(config.get('task', 'lock_file'), 'w').close()

def response(string):
    global response_file
    f = open(response_file,'w')
    f.write(string) # python will convert \n to os.linesep
    f.close()

def handleExceptionEnd(serial_util, message):
    serial_util.trace(message)
    serial_util.trace('Macro Failed')
    response('false')
    if os.path.isfile(config.get('task', 'lock_file')):
        os.remove(config.get('task', 'lock_file'))
    raise SystemExit()
""" ### custom exceptions ### """

def saveSettings(settings):
    settings_file = config.get('printer', 'settings_file')
    with open(settings_file, 'w') as outfile:
        json.dump(settings, outfile)
    outfile.close()

""" ################################################################### """
def loadSpool(serial_util, settings, params=None):
    serial_util.doMacro('M104 S190', 'ok', -1, 'Heating nozzle...')
    serial_util.doMacro('G90', 'ok', 1, 'Set absolute position')
    serial_util.doMacro('G27', 'ok', -1, 'Zeroing Z axis')
    serial_util.doMacro('G0 X130 Y150 Z150 F10000', 'ok', -1, 'Rising bed and moving head')
    serial_util.doMacro('M302', 'ok', 1,   'Extrusion prevention disabled')
    serial_util.doMacro('G91', 'ok', 1, 'Set relative position')
    serial_util.doMacro('G92 E0', 'ok', 1, 'Reset extuder position')
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', 1, 'Setting extuder mode')
    serial_util.doMacro('M300', 'ok', 1, 'Start pushing')
    serial_util.doMacro('G0 E110 F500', 'ok', -1, 'Loading filament (slow)')
    serial_util.doMacro('G0 E660 F700', 'ok', -1, 'Loading filament (fast)')
    temperature = serial_util.getTemperature()
    if(temperature['extruder']['temperature'] < 190 ):
        serial_util.doMacro('M109 S190', 'ok', -1, 'Heating nozzle...(wait)')
    serial_util.doMacro('G0 E100 F200', 'ok', -1, 'Entering the hotend (slow)')
    serial_util.doMacro('M104 S0', 'ok', 1, 'Disabling extruder')
    serial_util.doMacro('M302 S170', 'ok', 1, 'Extrusion prevention enabled')
""" ################################################################### """
def unloadSpool(serial_util, settings, params=None):
    serial_util.doMacro('M104 S190', 'ok', -1, 'Heating nozzle...')
    serial_util.doMacro('G90',  'ok', 1,   'Set absoulte position' )
    serial_util.doMacro('M302', 'ok', 1,   'Extrusion prevention disabled')
    serial_util.doMacro('G27',  'ok', -1, 'Zeroing Z axis')
    serial_util.doMacro('G0 Z100 F1000', 'ok', -1, 'Rising bed')
    serial_util.doMacro('G91', 'ok', 1, 'Set relative position')
    serial_util.doMacro('G92 E0', 'ok', 1, 'Reset extuder position')
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', 1, 'Setting extuder mode')
    serial_util.doMacro('M300', 'ok', 1, 'Start Pulling')
    serial_util.doMacro('G0 E-800 F550', 'ok', -1, 'Expelling filament')
    serial_util.doMacro('G0 E-200 F550', 'ok', -1, 'Expelling filament', verbose=False)
    serial_util.doMacro('M104 S0', 'ok', 1, 'Disabling extruder')
    serial_util.doMacro('M302 S170', 'ok', 1, 'Extrusion prevention enabled')
""" ################################################################### """       
def preUnloadSpool(serial_util, settings, params=None):
    """ pre heat nozzle  """
    temperature = serial_util.getTemperature()
    if(temperature['extruder']['temperature'] < 160 ):
        serial_util.doMacro('M109 S160', 'ok', -1, 'Heating nozzle... reaching temperature 160&deg;C (please wait)') ### set target and wait to reach it
    serial_util.doMacro('M104 S190', 'ok', -1, 'Heating nozzle...', verbose=False) ### set target
""" ################################################################### """
def checkPrePrint(serial_util, settings, params=None):
    """ preparing printer to print """
    serial_util.trace("Checking safety measures")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M744', 'TRIGGERED', 1, 'Check Heat bed', errorMessage='Heat bed not correctly inserted')
    #serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel control',  warning=True)
""" ################################################################### """
def checkPreMill(serial_util, settings, params=None):
    """ preparing printer to print """
    serial_util.trace("Checking safety measures")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M744', 'open', 1, 'Check platform', errorMessage='Please invert platform')
    #serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel control',  warning=True)
""" ################################################################### """
def endPrintAdditive(serial_util, settings, params=None):
    serial_util.doMacro('G90', 'ok', -1, 'Set Absolute movement')
    serial_util.doMacro('G27 Z0', 'ok', -1, 'Lowering the plane')
    serial_util.doMacro('M104 S0', 'ok', -1, 'Shutting down extruder')
    serial_util.doMacro('M140 S0', 'ok', -1, 'Shutting down heated Bed')
    serial_util.doMacro('M220 S100', 'ok', -1, 'Reset Speed factor override')
    serial_util.doMacro('M221 S100', 'ok', -1, 'Reset Extruder factor override')
    serial_util.doMacro('M107 S100', 'ok', -1, 'Turning Fan off')
    serial_util.doMacro('M18', 'ok', -1, 'Motor off')
    serial_util.doMacro('M300', 'ok', -1, 'Done!')
""" ################################################################### """
def checkPreScan(serial_util, settings, params=None):
    serial_util.trace("Preparing the FABtotum to scan")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel control', verbose=False, warning=True)
    serial_util.doMacro('G90', 'ok', 1, 'Set Absolute movement', verbose=False)
    serial_util.doMacro('G27', 'ok', -1, 'Zeroing axis')
    serial_util.doMacro('G28 X0 Y0', 'ok', -1, 'Zeroing axis', verbose=False)
    serial_util.doMacro('G91', 'ok', 1, 'Setting relative position', verbose=False)
    serial_util.doMacro('G0 X5 Y5 Z-' + str(settings['feeder']['disengage-offset']) + ' F400', 'ok', -1, 'Engaging 4th Axis Motion')
    serial_util.doMacro('G90', 'ok', 1, 'Set Absolute movement', verbose=False)
    serial_util.doMacro('M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode')
    serial_util.doMacro('G0 Z135 F1000', 'ok', -1, 'Moving to pre-scan position')    
""" ################################################################### """
def endScan(serial_util, settings, params=None):
    serial_util.trace("Terminating digitalization procedure")
    serial_util.doMacro('M402', 'ok', -1, 'Retracting Probe')
    serial_util.doMacro('M700 S0', 'ok', 1, 'Shutting down laser')
    serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    serial_util.doMacro('G0 Z140 E0 F5000', 'ok', -1, 'Rasing Probe', verbose=False)
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', 1, 'Setting extruder mode', verbose=False)
    serial_util.doMacro('M701 S' + str(settings['color']['r']), 'ok', 1, 'Turning on lights')
    serial_util.doMacro('M702 S' + str(settings['color']['g']), 'ok', 1, 'Turning on lights', verbose=False)
    serial_util.doMacro('M703 S' + str(settings['color']['b']), 'ok', 1, 'Turning on lights', verbose=False)
    serial_util.doMacro('M402', 'ok', -1, 'Retracting Probe', verbose=False)
    serial_util.doMacro('M300', 'ok', 1, 'Scan completed', verbose=False)
""" ################################################################### """
def sweepScan(serial_util, settings, params=None):
    serial_util.trace("Initializing Sweeping Laserscanner")
    serial_util.trace("checking panel door status and bed inserted")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M744', 'open', 1, 'Building plane removed!', warning=True)
    serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel is not closed!', verbose=False, warning=True)
    serial_util.doMacro('M701 S0', 'ok', -1, 'Turning off lights')
    serial_util.doMacro('M702 S0', 'ok', -1, 'Turning off lights', verbose=False)
    serial_util.doMacro('M703 S0', 'ok', -1, 'Turning off lights', verbose=False)
    serial_util.doMacro('G28 X0 Y0', 'ok', -1, 'Homing all axis')
    serial_util.doMacro('G90', 'ok', 1, 'Setting Absolute position')
    serial_util.doMacro('G0 Z145 F1000', 'ok', -1, 'Lowering the plane')
""" ################################################################### """
def probingScan(serial_util, settings, params=None):
    serial_util.trace("Initializing Probing procedure")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M402', 'ok', -1, 'Retracting Probe')
    serial_util.doMacro('M744', 'open', 1, 'Building plane is absent!', warning=True)
    serial_util.doMacro('G90', 'ok', 1, 'Setting Absolute position', verbose=False)
    serial_util.doMacro('M302 S0', 'ok', 1, 'Disabling cold extrusion prevention', verbose=False)
    serial_util.doMacro('M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode', verbose=False)
""" ################################################################### """
def rotatingScan(serial_util, settings, params=None):
    serial_util.trace("Initializing Rotative Laser scanner")
    serial_util.trace("Checking panel door status and bed inserted")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control')
    serial_util.doMacro('M744', 'open', 1, 'Building plane (must be removed)')
    serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel closed', verbose=False, warning=True)
    serial_util.doMacro('M701 S0', 'ok', 1, 'Turning off lights')
    serial_util.doMacro('M702 S0', 'ok', 1, 'Turning off lights', verbose=False)
    serial_util.doMacro('M703 S0', 'ok', 1, 'Turning off lights', verbose=False)
    serial_util.doMacro('G90', 'ok', -1, 'Setting Absolute position', verbose=False)
    serial_util.doMacro('G0 X96 Y175 Z135 E0 F10000', 'ok', -1, 'Moving to collimation position', verbose=False)
    serial_util.doMacro('M302 S0', 'ok', 1, 'Disabling cold extrusion prevention', verbose=False)
""" ################################################################### """
def photogrammetryScan(serial_util, settings, params=None):
    serial_util.trace("Initializing Photogrammetry")
    serial_util.trace("Checking panel door status and bed inserted")
    if(settings['safety']['door'] == 1):
        serial_util.doMacro('M741', 'TRIGGERED', 1, 'Front panel door control', verbose=False)
    serial_util.doMacro('M744', 'open', 1, 'Building plane (must be removed)')
    serial_util.doMacro('M744', 'TRIGGERED', 1, 'Spool panel closed', verbose=False, warning=True)
    serial_util.doMacro('M701 S255', 'ok', -1, 'Turning on lights')
    serial_util.doMacro('M702 S255', 'ok', -1, 'Turning on lights', verbose=False)
    serial_util.doMacro('M703 S255', 'ok', -1, 'Turning on lights', verbose=False)
    serial_util.doMacro('G90', 'ok', -1, 'Setting Absolute position', verbose=False)
    serial_util.doMacro('G0 X96 Y175 Z135 E0 F10000', 'ok', -1, 'Moving to collimation position', verbose=False)
    serial_util.doMacro('M302 S0', 'ok', -1, 'Disabling cold extrusion prevention', verbose=False)
""" ################################################################### """
def raiseBed(serial_util, settings, params=None):
    serial_util.doMacro('M402', 'ok', -1, 'Retracting Probe', verbose=False)
    serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    try:
        ####
        zprobe_disabled = int(settings['zprobe']['disable']) == 1
        zprobe_zmax     = settings['zprobe']['zmax']
    except KeyError:
        ###
        zprobe_disabled = False
        zprobe_zmax     = 206.0
        
    if(zprobe_disabled == True):
        serial_util.trace("Touching probe disabled")
        serial_util.doMacro('G27', 'ok', -1, 'Lowering bed', verbose=True)
        serial_util.doMacro('G92 X1 Y1 Z{0}'.format(zprobe_zmax), 'ok', -1, 'Setting fixed offset ({0})'.format(zprobe_zmax), verbose=False)
        serial_util.doMacro('G0 X10 Y10 Z70 F1000', 'ok', -1, 'Raising bed')
    else:
        serial_util.trace("Touching probe enabled")
        serial_util.doMacro('G27', 'ok', -1, 'Homing all axes', verbose=True)
        serial_util.doMacro('G0 Z{0} F10000'.format(SECURITY_Z_OFFSET), 'ok', -1, 'Raising', verbose=False)
        serial_util.doMacro('G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def raiseBedNo27(serial_util, settings, params=None):
    serial_util.doMacro('M402', 'ok', -1, 'Retracting Probe')
    serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    try:
        ####
        zprobe_disabled = int(settings['zprobe']['disable']) == 1
        zprobe_zmax   = settings['zprobe']['zmax']
    except KeyError:
        ###
        zprobe_disabled = False
        zprobe_zmax = 206.0
        
    if(zprobe_disabled == True):
        serial_util.trace("Use of probe disabled")
        serial_util.doMacro('G27 X0 Y0 Z{0}'.format(zprobe_zmax), 'ok', -1, 'Homing all axes')
        serial_util.doMacro('G0 Z50 F10000', 'ok', -1, 'Rising')
    else:
        serial_util.doMacro('G0 Z20 F10000', 'ok', -1, 'Raising bed', verbose=False)
        serial_util.doMacro('G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def fourthAxisMode(serial_util, settings, params=None):
    serial_util.doMacro('M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th axis mode', verbose=False)
""" ################################################################### """
def homeAll(serial_util, settings, params=None):
    serial_util.trace("Now homing all axes")
    try:
        ####
        zprobe_disabled = int(settings['zprobe']['disable']) == 1
        zprobe_zmax   = settings['zprobe']['zmax']
    except KeyError:
        ###
        zprobe_disabled = False
        zprobe_zmax = 206.0
    serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    if(zprobe_disabled == True):
        serial_util.trace("Use of probe disabled")
        serial_util.doMacro('G27 X0 Y0 Z' + str(zprobe_zmax), 'ok', -1, 'Homing all axes', verbose=False)
        serial_util.doMacro('G0 Z50 F10000', 'ok', -1, 'Rising', verbose=False)
    else:
        serial_util.doMacro('G28', 'ok', -1, 'Homing all axes', verbose=False)
""" ################################################################### """
def autoBedLeveling(serial_util, settings, params=None):
    serial_util.trace("Auto Bed leveling Initialized")
    #serial_util.doMacro('G91', 'ok', 1, 'Setting relative position', verbose=False)
    #serial_util.doMacro('G0 Z25 F1000', 'ok', -1, 'Moving away from the plane', verbose=False)
    #serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    serial_util.doMacro('G28 X Y', 'ok', -1, 'Homing all axes')
    serial_util.doMacro('G29', 'ok', -1, 'Auto bed leveling procedure')
    serial_util.doMacro('G0 X5 Y5 Z60 F2000', 'ok', -1, 'Getting to idle position')
""" ################################################################### """
def startPrint(serial_util, settings, params=None):
    serial_util.trace("Preparing the FABtotum Personal Fabricator")
    serial_util.doMacro('G90', 'ok', 1, 'Setting absolute position', verbose=False)
    serial_util.doMacro('G0 X5 Y5 Z60 F1500', 'ok', -1, 'Moving to oozing point')
    serial_util.trace('Pre heating nozzle (' + str(params['param1']) + '&deg;) (fast)')
    serial_util.sendGCode('M104 S' + str(params['param1']))
    serial_util.trace('Pre heating bed (' + str(params['param2']) + '&deg;) (fast)')
    serial_util.sendGCode('M140 S' + str(params['param2']))
    serial_util.doMacro('M220 S100', 'ok', -1, 'Reset Speed factor override', verbose=False)
    serial_util.doMacro('M221 S100', 'ok', -1, 'Reset Extruder factor override', verbose=False)
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', 1, 'Setting extruder mode', verbose=False)
""" ################################################################### """
def startSubtractivePrint(serial_util, settings, params=None):
    serial_util.doMacro('M92 E' + str(settings['a']), 'ok', 1, 'Setting 4th Axis mode', verbose=False)
    serial_util.doMacro('M3', 'ok', -1, 'Turning on milling motor', verbose=True)
def endPrintSubtractive(serial_util, settings, params=None):
    #serial_util.doMacro('M3 S0', 'ok', -1, 'Shutting Down Milling Motor')
    serial_util.doMacro('M5', 'ok', -1, 'Shutting Down Milling Motor', verbose=True)
    serial_util.doMacro('M220 S100', 'ok', -1, 'Reset Speed factor override')
    serial_util.doMacro('M221 S100', 'ok', -1, 'Reset Extruder factor override')
    serial_util.doMacro('M107', 'ok', -1, 'Turning Fan off')
    serial_util.doMacro('M18', 'ok', -1, 'Motor Off')
    serial_util.doMacro('M300', 'ok', -1, 'Completed!')
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', -1, 'Setting extruder mode', verbose=False)
    
""" ################################################################### """  
def probeSetupPrepare(serial_util, settings, params=None):
    try:
        zprobe_disabled = int(settings['zprobe']['disable']) == 1
    except KeyError:
        zprobe_disabled = False
    
    #if(zprobe_disabled == True):
    serial_util.doMacro('G90', 'ok', -1, 'Setting absolute position', verbose=False)
    serial_util.doMacro('G0 X86 Y58 Z50 F10000', 'ok', -1, 'Raising bed', verbose=True)
        
    serial_util.trace("Preparing Calibration procedure")
    serial_util.trace("This may take a wile")
    serial_util.doMacro('M104 S200', 'ok', -1, 'Heating extruder')
    serial_util.doMacro('M140 S45', 'ok', -1, 'Heating Bed - fast')
    serial_util.doMacro('G91', 'ok', 1, 'Relative mode', verbose=False)
    serial_util.doMacro('G0 X17 Y61.5 F6000', 'ok', -1, 'Offset', verbose=False)
    #serial_util.doMacro('G0 Z5 F1000', 'ok', -1, 'Moving to calibration position')
""" ################################################################### """  
def probeSetupCalibrate(serial_util, settings, params=None):
    #serial_util.trace("Calibrating probe")
    serial_util.trace("Calculating Z Max Height")
    serial_util.doMacro('M104 S0', 'ok', -1, 'Shutting down extruder', verbose=False)
    serial_util.doMacro('M140 S0', 'ok', -1, 'Shutting down bed', verbose=False)
    serial_util.doMacro('G90', 'ok', -1, 'Set absolute coordinates', verbose=False)
    serial_util.doMacro('G92 Z0.08', 'ok', -1, 'Setting paper heigth', verbose=False)
    serial_util.doMacro('G0 Z300 F1000', 'ok', -1, 'Lowering bed', verbose=False)
    current_position = serial_util.getPosition()
    z_offset_max = current_position['count']['z']
    serial_util.doMacro('G92 Z{0}'.format(z_offset_max), 'ok', -1, 'setting position', verbose=False)
    #serial_util.trace("Z Lenght calibrated : {0} mm".format(z_offset_max))
    ''' ============================================== '''
    ### probe lenght calibration
    serial_util.trace("Calculating Probe Length")
    serial_util.doMacro('G0 X86 Y58 Z50 F1000', 'ok', -1, 'calibration point', verbose=False)
    serial_util.doMacro('M401', 'ok', -1, 'Open probe', verbose=False)
    probe_length = serial_util.g30()
    
    serial_util.doMacro('G91', 'ok', 1, 'Relative mode', verbose=False)
    #serial_util.doMacro('G0 Z5 F1000', 'ok', -1, 'Moving the plane', verbose=False)
    serial_util.doMacro('M402', 'ok', -1, 'close probe', verbose=False)
    serial_util.doMacro('M710 S{0}'.format(probe_length), 'ok', -1, 'Saving new value: {0}'.format(probe_length), verbose=False)
    serial_util.doMacro('G90', 'ok', -1, 'Setting absolute position', verbose=False)
    serial_util.doMacro('G28 X0 Y0', 'ok', -1, 'Homing all axes', verbose=False)
    serial_util.doMacro('M300', 'ok', -1, 'Complete')
    settings['zprobe']['zmax'] = z_offset_max
    serial_util.trace("New Z Max Height : {0}".format(z_offset_max) + " mm" )
    serial_util.trace("New Probe Length : {0}".format(probe_length) + " mm" )
    saveSettings(settings)
    #else:
    #    raise serial_utils.MacroException('Get current Z position failed')
""" ################################################################### """  
def extrude(serial_util, settings, params=None):
    serial_util.doMacro('M92 E' + str(settings['e']), 'ok', 1, 'Setting extruder mode', verbose=False)
    serial_util.doMacro('M302', 'ok', 1, 'Extrusion prevention disabled', verbose=False)
    #temperature = serial_util.getTemperature()
    #if(temperature['extruder']['temperature'] < 180):
    #    serial_util.doMacro('M109 S185', 'ok', -1, 'Heating nozzle...(wait)')
    #    serial_util.trace("Temperature reached")
    serial_util.doMacro('G91', 'ok', 1, 'Setting relative position', verbose=False)
    serial_util.doMacro('G0 E{0}'.format(params['param1']) + ' F400', 'ok', -1, 'Expelling filament', verbose=True)
""" ################################################################### """  
def preLaser(serial_util, settings, params=None):
    serial_util.trace("Preparing laser engraving procedure")
    serial_util.doMacro('M744', 'open', 1, 'Checking platform',verbose=False, errorMessage='Please revert platform')
    #if(int(params['param1']) == 0): ## if is not restart
    #    serial_util.doMacro('G27 Z', 'ok', -1, 'Lowering platform', verbose=False)
    #    serial_util.doMacro('G28 X Y', 'ok', -1, 'G28 Homing', verbose=False)
    #    serial_util.doMacro('G90', 'ok', -1, 'Setting absolute position', verbose=False)
    #    serial_util.doMacro('G0 Z100 F1000', 'ok', -1, 'Raising platform', verbose=False)
    serial_util.doMacro('M61 S5', 'ok', -1, 'Laser ON (Minimum Power)', verbose=True)
def startLaserPrint(serial_util, settings, params=None):
    serial_util.doMacro('M732 S1', 'ok', -1, 'Door control enabled', verbose=True)
    serial_util.doMacro('G92 X0 Y0 Z0', 'ok', -1, 'Set home position', verbose=False)
    if(int(params['param1']) == 1): ## if is not restart
        serial_util.doMacro('G91', 'ok', -1, 'Set relative position', verbose=False)
        serial_util.doMacro('G0 Z+{0} F1000'.format(LASER_FOCUS_OFFSET), 'ok', -1, 'Going to focus point', verbose=True)
    position= serial_util.getPosition()
    #serial_util.trace("Position saved: X{0} Y{1} Z{2}".format(position["x"], position["y"], position['z']))
    settings['position'] = position
    saveSettings(settings)
    
def endLaserPrint(serial_util, settings, params=None):
    if(params['param1'] != None):
        z_height = float(settings['position']['z']) + float(params['param1'])
    else:
        z_height = settings['position']['z']
    
    serial_util.trace("End laser macro")
    serial_util.doMacro('G90', 'ok', -1, 'Set absoulte position', verbose=False)
    serial_util.doMacro('G0 X{0} Y{1} Z{2} F10000'.format(settings['position']['x'], settings['position']['y'], z_height), 'ok', -1, "Going to home", verbose=False)
    serial_util.doMacro('M61 S0', 'ok', -1, 'Shutting down laser', verbose=True)
    serial_util.doMacro('M60 S0', 'ok', -1, 'Shutting down laser', verbose=False)
    serial_util.doMacro('M732 S{0}'.format(settings['safety']['door']), 'ok', -1, 'Door control restored', verbose=True)

MACROS_CMDS = {
 'load_spool'              : loadSpool,
 'unload_spool'            : unloadSpool,
 'pre_unload_spool'        : preUnloadSpool,
 'check_pre_print'         : checkPrePrint,
 'end_print_additive'      : endPrintAdditive,
 's_scan'                  : sweepScan,
 'p_scan'                  : probingScan,
 'r_scan'                  : rotatingScan,
 'pg_scan'                 : photogrammetryScan,
 'end_scan'                : endScan,
 'check_pre_scan'          : checkPreScan,
 'raise_bed'               : raiseBed,
 'raise_bed_no_g27'        : raiseBedNo27,
 '4th_axis_mode'           : fourthAxisMode,
 'home_all'                : homeAll,
 'auto_bed_leveling'       : autoBedLeveling,
 'start_print'             : startPrint,
 'probe_setup_prepare'     : probeSetupPrepare,
 'probe_setup_calibrate'   : probeSetupCalibrate,
 'start_subtractive_print' : startSubtractivePrint,
 'end_print_subtractive'   : endPrintSubtractive,
 'end_laser_print'         : endLaserPrint,
 'extrude'                 : extrude,
 'pre_laser'               : preLaser,
 'start_laser'             : startLaserPrint,
 'check_pre_mill'          : checkPreMill
}

su = serial_utils.SerialUtils(trace_file = trace_file, debug=debug)
if macro_name in MACROS_CMDS:
    try:
        params = {
            'param1' : param1,
            'param2' : param2
        }
        MACROS_CMDS[macro_name](su, settings, params)
        response('true')
    except serial_utils.MacroException as e:
        handleExceptionEnd(su, e)
    except serial_utils.MacroTimeOutException as e:
        handleExceptionEnd(su, e.message)
    except Exception as e:
        handleExceptionEnd(su, 'Error : ' + e.__doc__ + "  '" + e.message + "'")
else:
    response('false')

if os.path.isfile(config.get('task', 'lock_file')):
    os.remove(config.get('task', 'lock_file'))