#SERIAL FLUSH PORT
import sys
import serial

port=str(sys.argv[1])
baudrate=(sys.argv[2])

ser = serial.Serial(port,baudrate,timeout=1)
ser.flushInput()
ser.flushOutput()