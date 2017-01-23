#!/usr/bin/python
import argparse, ConfigParser, json, os, time
from serial_utils import SerialUtils

config = ConfigParser.ConfigParser()
config.read('/var/www/lib/config.ini')


class SerialFactory():
    
    def __init__(self):
        self.serial   = SerialUtils(debug=False)
        self.reply    = None
        self.command  = None
        self.response = {}
    
    def get_eeprom(self):
        self.response = self.serial.eeprom()
        self.output()
        
    def restore_eeprom(self):
        self.send('M502', False)
        self.send('M500', False)
        self.output()
    
    def send(self, codes, output = True):
        codes = codes.split('-')
        replies = []
        for code in codes:
            self.serial.sendGCode(code)
            replies.append(self.serial.getReply())
            time.sleep(0.2)
        self.command = codes
        self.reply   = replies
        if(output):
            self.output()
        
    def output(self):
        object = {
            'response' : self.response,
            'command'  : self.command,
            'reply'    : self.reply
        }
        
        print json.dumps(object)
        
def main():
    # SETTING EXPECTED ARGUMENTS
    parser = argparse.ArgumentParser()
    parser.add_argument("-m", "--method", help="method to call")
    parser.add_argument("-c", "--code",   help="code to send",  default="", type=str)
    # GET ARGUMENTS
    args        = parser.parse_args()
    method_name = args.method
    code        = args.code
    
    open(config.get('task', 'lock_file'), 'w').close()
    
    sf = SerialFactory()
    
    try:
        method = getattr(sf, method_name)
    except AttributeError:
       print ("Class `{}` does not implement `{}`".format(sf.__class__.__name__, method_name))
    
    try:
        if(code != ''):
            method(code)
        else:
            method()
    except TypeError:
        method()
    
    if os.path.isfile(config.get('task', 'lock_file')):
        os.remove(config.get('task', 'lock_file'))
        
    
if __name__ == "__main__":
    main()