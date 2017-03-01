#!/bin/env python
import os, re
import commands
class PiCameraUtils():
    
    def __init__(self):    
        self._exists_picamera_library = True
        
        try:
            from picamera import PiCamera
        except ImportError:
            self._exists_picamera_library = False
        
        self.camera_version = None
        
    def setCameraVersion(self):
        
        if(self._exists_picamera_library == False):
            output = commands.getoutput('sudo raspistill -v -t 1')
            match_width = re.search('Width\s(\d+)',  output)
            match_height = re.search('Height\s(\d+)', output)
            
            if(match_width != None):
                width = int(match_width.group(1))
            if(match_height != None):
                height = int(match_height.group(1))
                
            resolution = width * height
            
            if(resolution == 8081920):
                self.camera_version = 2
                return
            
            if(resolution == 5038848):
                self.camera_version = 1
                return
    def version(self):
        if(self.camera_version == None):
            self.setCameraVersion()
        return self.camera_version