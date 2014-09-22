##
import sys
import serial


try:
    code_to_execute=str(sys.argv[1])
except:
    print "Warning - parameter <CODE> is required"
    sys.exit()

if code_to_execute != "" :
    port = '/dev/ttyAMA0'
    baud = 115200
    serial = serial.Serial(port, baud, timeout=0)
    serial.flushInput()
    serial.flushInput()
    serial.write(code_to_execute)
    data=serial.read(8)
    print data
    
