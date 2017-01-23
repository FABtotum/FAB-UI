import os
import sys
import json

def shell_exec(cmd):
    stdin,stdout = os.popen2(cmd)
    stdin.close()
    lines = stdout.readlines(); 
    stdout.close()
    return lines

def dequote(s):
    """
    If a string has single or double quotes around it, remove them.
    Make sure the pair of quotes match.
    If a matching pair of quotes is not found, return the string unchanged.
    """
    if (s[0] == s[-1]) and s.startswith(("'", '"')):
        return s[1:-1]
    return s

if len(sys.argv) > 1:
    iface = sys.argv[1]
else:
    iface = 'wlan0'

raw_results = shell_exec('iwlist {0} scan'.format(iface))

results = {}

cell_id = ""
important = ['ESSID', 'Channel', 'Frequency', 'Encryption key', 'Mode']
cell = {}
for line in raw_results:
    line = line.strip()
    tags = line.split(':')
    if line.startswith('Cell'):
        data = line.split()
        cell = results[data[1]] = {}
        cell['address'] = data[4].lower()
        cell['encryption'] = ''
        
    if tags[0] in important:
        tag = tags[0].lower().replace(' ', '_')
        cell[tag] = dequote(tags[1])
    
    if tags[0] == 'Quality':
        data = tags[1].split()
        cell['quality'] = data[0]
    
    if tags[0].startswith('Quality='):
        tmp = line.split()[0].split('=')[1].split('/')
        q = float(tmp[0])*100.0 / float(tmp[1])
        cell['quality'] = q
    
    if tags[0] == 'IE':
        tmp = tags[1].split()
        if tmp[0] == 'IEEE':
            cell['encryption'] = tmp[1]
        
print json.dumps(results)