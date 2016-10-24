#!/usr/bin/env python
import time
import sys, os
import serial
from subprocess import call
import numpy as np
import json
import ConfigParser
import logging
import re
from geometry import Point, Line, Plane
from serial_utils import SerialUtils
import argparse

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')

#check if LOCK FILE EXISTS
if os.path.isfile(config.get('task', 'lock_file')):
    print "printer busy"
    sys.exit()



def fitPlaneSVD(XYZ):
    #unused
    [rows,cols] = XYZ.shape
    # Set up constraint equations of the form  AB = 0,
    # where B is a column vector of the plane coefficients
    # in the form b(1)*X + b(2)*Y +b(3)*Z + b(4) = 0.
    p = (np.ones((rows,1)))
    AB = np.hstack([XYZ,p])
    [u, d, v] = np.linalg.svd(AB,0)        
    B = v[3,:];                    # Solution is last column of v.
    nn = np.linalg.norm(B[0:3])
    B = B / nn
    return B[0:3] #a b c

def fitplane(XYZ):
    [npts,rows] = XYZ.shape

    if not rows == 3:
        #print XYZ.shape
        raise ('data is not 3D')
        return None

    if npts <3:
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

def main():
    
    parser = argparse.ArgumentParser()

    parser.add_argument("-t", "--trace",    help="log travce file",        default=config.get('macro', 'trace_file'))
    parser.add_argument("-r", "--response", help="log response file",      default=config.get('macro', 'response_file'))
    parser.add_argument("-l", "--heigth",   help="height",                 default=38, type=int)
    parser.add_argument("-n", "--probes",   help="Number of probes",       default=1,  type=int)
    parser.add_argument("-s", "--skip",     help="Skip homing",            action="store_true")
    parser.add_argument("-d", "--debug",    help="Debug: print console",   action="store_true")
    args = parser.parse_args()
    #write LOCK FILE    
    open(config.get('task', 'lock_file'), 'w').close()
    
    """ ### init  ### """
    log_trace     = args.trace
    logfile       = args.response
    response_file = args.response
    num_probes    = args.probes
    skip_homing   = args.skip
    debug         = args.debug
    
    cycle=True
    s_warning=s_error=s_skipped=0
    probe_height=50.0
    milling_offset=0.0
    probe_offset_security=15
    
    screw_turns=["" for x in range(4)]
    screw_height=["" for x in range(4)]
    screw_degrees=["" for x in range(4)]
    
    #points to probe
    probed_points=np.array([[5+17,5+61.5,0],[5+17,148.5+61.5,0],[178+17,148.5+61.5,0],[178+17,5+61.5,0]])
    #first screw offset (lower left corner)
    screw_offset=[8.726,10.579,0]
    
    ''' #### START #### '''
    su = SerialUtils(debug = debug)
    su.trace('Manual Bed Calibration Wizard Initiated')
    eeprom = su.eeprom();
    probe_height = (abs(float(eeprom['probe_length'])) + 1 ) + probe_offset_security
    
    settings_file = open(config.get('printer', 'settings_file'))
    settings = json.load(settings_file)
    
    if 'settings_type' in settings and settings['settings_type'] == 'custom':
        settings_file = open(config.get('printer', 'custom_settings_file'))
        settings = json.load(settings_file)
    settings_file.close()
    
    try:
        safety_door = int (settings['safety']['door']) == 1
        milling_offset = float(settings['milling']['layer-offset'])
    except KeyError:
        safety_door = False
        milling_offset = 0
    
    
    if(safety_door):
        su.doMacro('M741', 'TRIGGERED', -1, 'Front panel door control', verbose=False)
    
    su.doMacro('M744', 'TRIGGERED', -1, 'Milling bed side up', warning=True, verbose=False)
    #su.trace("Milling sacrificial layer thickness: "+str(milling_offset))
    
    su.doMacro('M402', 'ok', 1, 'Retracting Probe (safety)', verbose=False)
    su.doMacro('G90', 'ok', -1, 'Setting absolute mode', verbose=False)
    
    if(skip_homing == False):
        su.doMacro('G27', 'ok', -1, 'Homing Z - Fast', verbose=False)
        su.doMacro('G92 Z241.2', 'ok', -1, 'Setting correct Z', verbose=False)
        su.doMacro('M402', 'ok', 1, 'Retracting Probe (safety)', verbose=False)
    
    su.doMacro("G0 Z"+str(probe_height)+" F5000", 'ok', -1, 'Moving to start Z height', verbose=False)#mandatory!
    
    for (p,point) in enumerate(probed_points):
    
        #real carriage position
        x=point[0]-17
        y=point[1]-61.5
        
        su.doMacro("G0 X"+str(x)+" Y"+str(y)+" Z"+str(probe_height)+" F10000", 'ok', -1, 'Moving to Pos', verbose=False)
        msg="Measuring point " +str(p+1)+ " of "+ str(len(probed_points)) + " (" +str(num_probes) + " times)"
        su.trace(msg)
        #Touches 4 times the bed in the same position
        probes=num_probes #temp
        for i in range(0,num_probes):
            
            #M401
            su.doMacro("M401", 'ok', -1, 'Lowering Probe', verbose=False)
            #serial.flushInput()
            #G30
            su.doMacro("G30", 'ok', -1, 'Lowering Probe', verbose=False)
            position = su.getPosition()
            #serial.write("G30\r\n")
            #time.sleep(0.5)            #give it some to to start  
            '''probe_start_time = time.time()
            while not serial_reply[:22]=="echo:endstops hit:  Z:":
                serial_reply=serial.readline().rstrip()    
                #issue G30 Xnn Ynn and waits reply.
                if (time.time() - probe_start_time>20):  #timeout management
                    trace("Probe failed on this point")
                    probes-=1 #failed, update counter
                    break    
                pass
               
            #print serial_reply
            #if probes==0:
            #print serial_reply
            if probes==0:
                trace("Aborting Not enough contacts. Please check bed height!")
                call("sudo python /var/www/fabui/python/force_reset.py", shell=True) #safety reset.
                time.sleep(5)
                call("sudo python /var/www/fabui/python/gmacro.py start_up log.log trace.trace", shell=True) #safety reset.
                time.sleep(1)
                sys.exit();
            #get the z position
            if serial_reply!="":
                z=float(serial_reply.split("Z:")[1].strip())
                #trace("probe no. "+str(i+1)+" = "+str(z) )
                probed_points[p,2]+=z # store Z
                
            serial_reply=""
            serial.flushInput()
            ''' 
            probed_points[p,2]+=float(position['count']['z']) # store Z
            
            #G0 Z40 F5000
            #macro("G0 Z40 F5000","ok",10,"Rising Bed",0.1, warning=True, verbose=False)
            su.doMacro("G0 Z"+str(probe_height)+" F5000", 'ok', -1, 'Rising Bed', verbose=False)
            
        #mean of the num of measurements
        probed_points[p,0]=probed_points[p,0]
        probed_points[p,1]=probed_points[p,1]
        probed_points[p,2]=probed_points[p,2]/probes; #mean of the Z value on point "p"
        
        #trace("Mean ="+ str(probed_points[p,2]))
        
        #msg="Point " +str(p+1)+ "/"+ str(len(probed_points)) + " , Z= " +str(probed_points[p,2])
        #trace(msg)
        su.doMacro("M402", 'ok', -1, 'Raising Probe', verbose=False)
        #macro("M402","ok",2,"Raising Probe",0.1, warning=True, verbose=False)    
        
        #G0 Z40 F5000
        #macro("G0 Z40 F5000","ok",2,"Rising Bed",0.1, warning=True, verbose=False)
        su.doMacro("G0 Z"+str(probe_height)+" F5000", 'ok', -1, 'Rising Bed', verbose=False)
        #macro("G0 Z"+str(probe_height)+" F5000","ok",2,"Rising Bed",0.5, warning=True, verbose=False)
        
    su.doMacro("G0 X5 Y5 Z"+str(probe_height)+" F10000", 'ok', -1, 'Idle Position', verbose=False)
    su.doMacro("M18", 'ok', -1, 'Motors off', verbose=False)
    su.trace("Completed")
    
    probed_points=np.add(probed_points,screw_offset)
    
    Fit = str(fitplane(probed_points))
    
    Fit = fitplane(probed_points)
    coeff = Fit[0:3]
    d = Fit[3]
    
    eeprom = su.eeprom();
    z_probe = float(eeprom['probe_length'])
    
    d_ovr=d
    
    cal_point=np.array([[0-8.726,0-10.579,0],[0-8.726,257.5-10.579,0],[223-8.726,257.5-10.579,0],[223-8.726,0-10.579,0]])
    
    idx=0
    for (p,point) in enumerate(cal_point):
        #cal_point[p][0][1]  => point[1]  #Y coordinate of point 0
    
        z=(-coeff[0]*point[0] - coeff[1]*point[1] +d)/coeff[2]
            
        #difference from titled plane to straight plane
        #distance=P2-P1
        diff=abs(-d_ovr)-abs(z)
        
        #msg= "d :"+str(d)+", P :"+str(p)+" , Z:" +str(z) +" Diff: "+str(diff) +" d_ovr: "+str(d_ovr) 
        #msg= str(d_ovr)+ "-"+str(abs(z))+" = " + str(diff)
        #trace(msg)
        
        #number of screw turns, pitch 0.5mm
        turns=round(diff/0.5, 2) #
        degrees= turns*360
        degrees=int(5 * round(float(degrees)/5))  #lets round to upper 5
        
        screw_turns[idx]=turns
        screw_height[idx]=diff
        screw_degrees[idx]=degrees
        
        idx+=1
        print "Calculated=" + str(z) + " Difference " + str(diff) +" Turns: "+ str(turns) + " deg: " + str(degrees)
    
    #save everything
    bed_calibration = {
     "t1": screw_turns[0],
     "t2": screw_turns[1],
     "t3": screw_turns[2],
     "t4": screw_turns[3],
     "s1": screw_height[0],
     "s2": screw_height[1],
     "s3": screw_height[2],
     "s4": screw_height[3],
     "d1": screw_degrees[0],
     "d2": screw_degrees[1],
     "d3": screw_degrees[2],
     "d4": screw_degrees[3]
    }
    
    result = {
     "bed_calibration" : bed_calibration
    }
    
    with open(logfile, 'w') as outfile:
        json.dump(result, outfile)
    outfile.close()
    
    if os.path.isfile(config.get('task', 'lock_file')):
        os.remove(config.get('task', 'lock_file'))


if __name__ == "__main__":
    main()
