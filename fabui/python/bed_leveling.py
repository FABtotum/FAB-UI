#!/usr/bin/python
import argparse, ConfigParser, json, os,time
from serial_utils import SerialUtils
import threading
from threading import Event, Thread
import numpy as np

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

class BedLeveling():
    
    PROBE_POINTS = [
                        [5+17,      5+61.5,     0.0], #point 1
                        [5+17,      158.5+61.5, 0.0], #point 2
                        [178+17,    158.5+61.5, 0.0], #point 3
                        [178+17,    5+61.5,     0.0], #point 4
                   ]
    SCREW_OFFSET = [8.726, 10.579, 0]
    SCREW_PITCH  = 0.5 # mm/deg
    CARRIAGE_POSITION   = [17, 61.5]
    XY_FEEDRATE = 10000
    Z_FEEDRATE  = 10000
    PROBE_OFFSET_SECURITY = 15
    MAX_NUM_PROBES = 4
    
    def __init__(self, trace_file, response_file, settings, num_probes = 1, skip_homing = False):
        print "init"
        self.serial = SerialUtils(debug=True)
        self.trace_file            = trace_file
        self.response_file         = response_file
        self.num_probes            = num_probes
        self.skip_homing           = skip_homing
        self.hardware_settings     = settings
        self.probe_height          = 50
        self.milling_offset        = 0
        self.safety_door           = False
        self.eeprom                = None
        self.screw_turns           = ["" for x in range(4)]
        self.screw_height          = ["" for x in range(4)]
        self.screw_degrees         = ["" for x in range(4)]
        if(self.num_probes > self.MAX_NUM_PROBES):
            self.num_probes = self.MAX_NUM_PROBES
        
    def prepareTask(self):
        self.eeprom = self.serial.eeprom()
        self.probe_height = (abs(float(self.eeprom['probe_length'])) + 1 ) + self.PROBE_OFFSET_SECURITY
        self.safety_door = int (self.hardware_settings['safety']['door']) == 1
        #print self.probe_height
        #print self.safety_door
        self.serial.doMacro('G21', 'ok', 1, "Set units millimeters", verbose=False)
        if(self.safety_door):
            self.serial.doMacro('M741', 'TRIGGERED', -1, 'Front panel door control', verbose=False)
        self.serial.doMacro('M744', 'TRIGGERED', 1, 'Milling bed side up', verbose=False, errorMessage="Please revert platform")
        self.serial.doMacro('M402', 'ok', -1, 'Retracting Probe (safety)', verbose=False)
        self.serial.doMacro('G90', 'ok', -1, 'Setting absolute mode', verbose=False)
        if(self.skip_homing == False):
            self.serial.doMacro('G27', 'ok', -1, 'Homing Z - Fast', verbose=False)
            self.serial.doMacro('G92 Z241.2', 'ok', -1, 'Setting correct Z', verbose=False)
            #self.serial.doMacro('M402', 'ok', 1, 'Retracting Probe (safety)', verbose=False)
        self.serial.doMacro("G0 Z"+str(self.probe_height)+" F15000", 'ok', -1, 'Moving to start Z height Z{0}'.format(self.probe_height), verbose=False)#mandatory!
        if os.path.isfile(config.get('task', 'lock_file')) == False:
            open(config.get('task', 'lock_file'), 'w').close()
        
    def fitplane(self, XYZ):
        [npts,rows] = XYZ.shape

        if not rows == 3:
            #print XYZ.shape
            raise ('data is not 3D')
            return None

        if npts < 3:
            raise ('too few points to fit plane')
            return None

        # Set up constraint equations of the form  AB = 0,
        
        # where B is a column vector of the plane coefficients
        # in the form   b(1)*X + b(2)*Y +b(3)*Z + b(4) = 0.
        t = XYZ
        p = (np.ones((npts,1)))
        A = np.hstack([t,p])

        if npts == 3:                       # Pad A with zeros
            A = [A, np.zeros(1,4)]

        [u, d, v] = np.linalg.svd(A)        # Singular value decomposition.
        #print v[3,:]
        B = v[3,:];                         # Solution is last column of v.
        nn = np.linalg.norm(B[0:3])
        B = B / nn
        #plane = Plane(Point(B[0],B[1],B[2]),D=B[3])
        #return plane
        return B[:]
            
    def probe(self, x, y, open):
        print "probe point %s %s " % (x, y)
        # move to position
        self.serial.doMacro('G90', 'ok', -1, 'Setting absolute mode', verbose=False)
        self.serial.doMacro('G0 X{0} Y{1} F{2}'.format(x, y, self.XY_FEEDRATE), 'ok', -1, 'Moving to Pos', verbose=False)
        # open probe
        if(open==True):
            self.serial.doMacro('M401', 'ok', -1, 'Open probe', verbose=False)
        # rise bed
        z_touched = self.serial.g30()
        #self.serial.sendGCode('G0 Z{0} F{1}'.format(self.probe_height, self.Z_FEEDRATE))
        #self.serial.doMacro('G0 Z{0} F{1}'.format(self.probe_height, self.Z_FEEDRATE), 'ok', -1, 'Safety Z height', verbose=True)
        #self.serial.doMacro('M402', 'ok', -1, 'Retracting Probe (safety)', verbose=False)
        return z_touched
      
    def idlePosition(self, x=5, y=5):
        # move to idle position
        self.serial.doMacro('G90', 'ok', -1, 'Setting absolute mode', verbose=False)
        self.serial.doMacro('G0 X{0} Y{1} Z{2} F{3}'.format(x, y, self.probe_height, self.XY_FEEDRATE), 'ok', -1, 'Idle Position', verbose=False)
        
    def outuput(self):
        output = {
            "bed_calibration" : {
                 "t1": self.screw_turns[0],
                 "t2": self.screw_turns[1],
                 "t3": self.screw_turns[2],
                 "t4": self.screw_turns[3],
                 "s1": self.screw_height[0],
                 "s2": self.screw_height[1],
                 "s3": self.screw_height[2],
                 "s4": self.screw_height[3],
                 "d1": self.screw_degrees[0],
                 "d2": self.screw_degrees[1],
                 "d3": self.screw_degrees[2],
                 "d4": self.screw_degrees[3]
            }
        }
        print output
        with open(self.response_file, 'w') as response:
            json.dump(output, response)
        response.close()
        
    def run(self):
        self.serial.trace('Bed leveling procedure')
        self.prepareTask()
        probed_points = np.array(self.PROBE_POINTS)
        openProbe = True
        for (p,point) in enumerate(self.PROBE_POINTS):    
            self.serial.trace('Measuring point {0} of {1} ({2} times)'.format(p+1, len(probed_points), self.num_probes))
            # Real carriage position
            x = point[0] - self.CARRIAGE_POSITION[0]
            y = point[1] - self.CARRIAGE_POSITION[1]
            #self.serial.doMacro('M401', 'ok', -1, 'Open Probe (safety)', verbose=False)
            for i in range(0,self.num_probes):
                z = self.probe(x, y, openProbe)
                if(openProbe == True):
                    openProbe = False
                probed_points[p,2]+=float(z) # store Z
            
            probed_points[p,0]=probed_points[p,0]
            probed_points[p,1]=probed_points[p,1]
            probed_points[p,2]=probed_points[p,2]/self.num_probes;
            #self.serial.doMacro('M402', 'ok', -1, 'Retracting Probe (safety)', verbose=False)
        #move to idle position
        self.serial.doMacro('M402', 'ok', -1, 'Retracting Probe (safety)', verbose=False)
        self.idlePosition()
        self.serial.doMacro("M18", 'ok', -1, 'Motors off', verbose=False)
        self.serial.trace('Processing points..')
        
        probed_points=np.add(probed_points,self.SCREW_OFFSET)
        Fit = self.fitplane(probed_points)
        coeff = Fit[0:3]
        d = Fit[3]
        
        d_ovr=d
        cal_point = np.array([
            [0.0-8.726, 0.0-10.579,     0.0],
            [0.0-8.726, 257.5-10.579,   0.0],
            [223-8.726, 257.5-10.579,   0.0],
            [223-8.726, 0.0-10.579,     0.0]
        ])
        
        idx=0
        for (p,point) in enumerate(cal_point):
            z=(-coeff[0]*point[0] - coeff[1]*point[1] +d)/coeff[2]
            #difference from titled plane to straight plane
            #distance=P2-P1
            diff=abs(-d_ovr)-abs(z)
            #number of screw turns, pitch 0.5mm
            turns=round(diff/0.5, 2) #
            degrees= turns*360
            degrees=int(5 * round(float(degrees)/5))  #lets round to upper 5
            
            self.screw_turns[idx]=turns
            self.screw_height[idx]=diff
            self.screw_degrees[idx]=degrees
            idx+=1
            print "Calculated=" + str(z) + " Difference " + str(diff) +" Turns: "+ str(turns) + " deg: " + str(degrees)
        self.outuput()
        
def main():
    print "main"
    # SETTING EXPECTED ARGUMENTS
    parser = argparse.ArgumentParser()
    parser.add_argument("-t", "--trace",    help="log trace file",        default=config.get('macro', 'trace_file'))
    parser.add_argument("-r", "--response", help="log response file",      default=config.get('macro', 'response_file'))
    parser.add_argument("-l", "--heigth",   help="height",                 default=38, type=int)
    parser.add_argument("-n", "--probes",   help="Number of probes",       default=1,  type=int)
    parser.add_argument("-s", "--skip",     help="Skip homing",            action="store_true")
    parser.add_argument("-d", "--debug",    help="Debug: print console",   action="store_true")
    # GET ARGUMENTS
    args = parser.parse_args()
    # INIT VARs
    log_trace     = args.trace
    response_file = args.response
    num_probes    = args.probes
    skip_homing   = args.skip
    debug         = args.debug
    # Load hardware settings
    settings_file = open(config.get('printer', 'settings_file'))
    settings = json.load(settings_file)
    
    '''if 'settings_type' in settings and settings['settings_type'] == 'custom':
        settings_file = open(config.get('printer', 'custom_settings_file'))
        settings = json.load(settings_file) '''
    
    settings_file.close()
    open(config.get('task', 'lock_file'), 'w').close()
    app = BedLeveling(log_trace, response_file, settings, num_probes, skip_homing)
    app_thread = Thread(target = app.run)
    app_thread.start()
    app_thread.join()
    if os.path.isfile(config.get('task', 'lock_file')):
        os.remove(config.get('task', 'lock_file'))


if __name__ == "__main__":
    try:
        main()
    except:
        print "Caught it!"