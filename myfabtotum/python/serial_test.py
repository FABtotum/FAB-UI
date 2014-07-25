#with open('gcode.nc') as f:
#    content = f.readlines()


#def readlineCR(port):
#    rv = ""
#    while True:
#        ch = serial.read()
#        rv += ch
#        if ch=='\r' or ch=='':
#            return rv

import serial

i=0

serial = serial.Serial('/dev/ttyAMA0', 9600)
while True :
    try:
        state=serial.readline().rstrip()
        print "Received (" + state +")"
    except:
        pass
print "Exception"