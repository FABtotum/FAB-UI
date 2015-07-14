import serial
import json
import ConfigParser


EOL = '\r\n'

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

''' LOAD CUSTOM FABTOTUM SETTINGS '''
json_config_settings = open(config.get('printer', 'settings_file'))
fabtotum_settings    = json.load(json_config_settings)

''' LOAD SERIAL '''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.5)



''' FLUSH BUFFER FUNCTION '''
def flush():
    
    global serial
    
    print "FLUSH SERIAL"
    #serial.flushInput()
    serial.flushOutput()
    #serial.flush()


''' FLUSH SERIAL '''
#flush()

''' ALIVE MACHINE '''
serial.write('M728' + EOL)
serial.flushOutput()
#flush()

''' SETTINGS AMBIENT COLOR '''
color_red   = str(fabtotum_settings['color']['r'])
color_green = str(fabtotum_settings['color']['g'])
color_blue  = str(fabtotum_settings['color']['b'])

serial.write('M701 S' + color_red + EOL)
serial.flushOutput()
serial.write('M702 S' + color_green + EOL)
serial.flushOutput()
serial.write('M703 S' + color_blue + EOL)
serial.flushOutput()
#flush()
''' SETTING SAFETY PANEL DOOR WARNING '''
safety_door = str(fabtotum_settings['safety']['door'])

serial.write('M732 S' + safety_door + EOL)
serial.flushOutput()
#flush()
''' SETTING HOMING PREFERENCES '''
homing_preferences = str(fabtotum_settings['switch'])

serial.write('M714 S' + homing_preferences + EOL)
serial.flushOutput()
#flush()

''' FLUSH SERIAL '''
#flush()

''' GET HARDWARE VERSION '''
serial.write('M763\r\n')
print  serial.read(1024)


serial.close()