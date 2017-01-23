#!/usr/bin/python
import argparse
import commands
import re

MANUFACTORING_ADDITIVE  = 'ADDITIVE'
MANUFACTORING_SUBTRACTIVE  = 'SUBTRACTIVE'
MANUFACTORING_LASER  = 'LASER'

parser = argparse.ArgumentParser()
parser.add_argument("-f", help="file to read")
parser.add_argument("-n", help="Num lines to check",  default=500, nargs='?', type=int)
parser.add_argument("-d", "--debug",    help="Debug: print console",   action="store_true")

args = parser.parse_args()

file=args.f
num_lines=args.n
debug = args.debug


def isSubtractive(line):
    match = re.search('(M3\s|M4\s|M03\s)', line)
    return match != None

def isLaser(line):
    match = re.search('(M60\s|M61\s|M62\s)', line)
    return match != None

def isPrint(line):
    return False



file_total_num_lines =  int(commands.getoutput('wc -l < "{0}"'.format(file)))

if(file_total_num_lines < num_lines):
    num_lines = file_total_num_lines

''' READ FIRST NUM_LINES '''
with open(file) as myfile:
    lines = [next(myfile) for x in xrange(num_lines)]
    
manufactoring = MANUFACTORING_ADDITIVE

for line in lines:
    
    if(isSubtractive(line)):
        manufactoring = MANUFACTORING_SUBTRACTIVE
        break
    elif(isLaser(line)):
        manufactoring = MANUFACTORING_LASER
        break
    else:
        manufactoring = MANUFACTORING_ADDITIVE

print manufactoring


    
    
    
    
