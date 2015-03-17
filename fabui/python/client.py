import socket
import sys
import time

HOST, PORT = "192.168.0.102", 9999
class Client:
    gateway = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    #host = socket.gethostname()
    host = ''
    port = 0
    file = ''

    def __init__(self, host, port, file):
        self.port = port
        self.host = host
        self.file = file
        self.connect()
        

    def connect(self):
        print "connected"
        self.gateway.connect((self.host, self.port))
        #self.sendFileName()
        self.sendFile()

    def sendFileName(self):
        self.gateway.send("name:" + self.file)

    def sendFile(self):
        print "Sending file: " + self.file
        readByte = open(self.file, "rb")
        data = readByte.read()
        readByte.close()
        self.gateway.send(data)
        print "File sent"
        raw_input("Press Enter to Close...")
        self.gateway.close()
        
a = Client(HOST, PORT, '/var/www/assets/img/logo.png')
