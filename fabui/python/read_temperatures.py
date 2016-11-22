#!/usr/bin/python
#gmacro.py controlled 1by1 gcode operations
import json
import re
import argparse
import commands


parser = argparse.ArgumentParser()
parser.add_argument("-f", help="file to read")
parser.add_argument("-n", help="Num lines to check",  default=20, nargs='?', type=int)
args = parser.parse_args()

file=args.f
num_lines=args.n

extruder_gcodes=[109]
bed_gcodes=[190]

extrudert_target=0
bed_target=0

file_total_num_lines =  int(commands.getoutput('wc -l < %s' % file))


if(file_total_num_lines < num_lines):
    num_lines = file_total_num_lines


''' READ FIRST NUM_LINES '''
with open(file) as myfile:
    lines = [next(myfile) for x in xrange(num_lines)]
    
for line in lines:
    line = line.rstrip()
    match = re.search('M(\d+)\sS([+|-]*[0-9]*.[0-9]*)', line)
    if match != None:
        if int(match.group(1)) in extruder_gcodes:
            extrudert_target = match.group(2).strip()
        elif int(match.group(1)) in bed_gcodes:
            bed_target = match.group(2).strip()

print json.dumps({'extruder':extrudert_target, 'bed': bed_target})