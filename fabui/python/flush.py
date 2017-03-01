''' SERIAL FLUSH INPUT & OUTPUT BUFFER '''
import serial
import ConfigParser

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/serial.ini')

serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')

serial = serial.Serial(serial_port, serial_baud, timeout=0.5)
serial.flushInput()
serial.flushOutput()
serial.flush()
serial.close()
