import serial
import json
import ConfigParser

config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')

''' LOAD CUSTOM FABTOTUM SETTINGS '''
json_config_settings = open(config.get('printer', 'settings_file'))
fabtotum_settings    = json.load(json_config_settings)

''' LOAD SERIAL '''
serial_port = config.get('serial', 'port')
serial_baud = config.get('serial', 'baud')
serial = serial.Serial(serial_port, serial_baud, timeout=0.5)




def writeToSerial():
    
    #write to serial
    #wait 



def macro(code,expected_reply,timeout,error_msg,delay_after,warning=False,verbose=True):
    serial.flushInput()
    if s_error==0:
        serial_reply=""
        macro_start_time = time.time()
        serial.write(code+"\r\n")
        if verbose:
            trace(error_msg)
        time.sleep(0.3) #give it some tome to start
        while not (serial_reply==expected_reply or serial_reply[:4]==expected_reply):
            #Expected reply
            #no reply:
            if (time.time()>=macro_start_time+timeout+5):
                if serial_reply=="":
                    serial_reply="<nothing>"
                #trace_msg="failed macro (timeout):"+ code+ " expected "+ expected_reply+ ", received : "+ serial_reply
                #trace(trace_msg,log_trace)
                #print trace_msg
                if not warning:
                    trace(error_msg + ": Failed (" +serial_reply +")")
                else:
                    trace(error_msg + ": Warning! ")
                return False #leave the function
            serial_reply=serial.readline().rstrip()
            #add safety timeout
            time.sleep(0.2) #no hammering
            pass
        time.sleep(delay_after) #wait the desired amount
    else:
        trace(error_msg + ": Skipped")
        
        return False
    return serial_reply