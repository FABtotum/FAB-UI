import os, sys
import datetime,time

#join multiple ascii files

#for file in range(1,len(sys.argv-1)):
output_file=sys.argv[1]

with open(output_file, 'w') as outfile:
	for fname in sys.argv[2:]:
		with open(fname) as infile:
			for line in infile:
					line = line.replace("\r","")
					line = line.replace("\n","")
					line= line+"\n"
					if(line!=""): #won't merge lines with nothing more than a carriage return
						outfile.write(line)
			print "file added: ", fname
			#outfile.write("\n")
print "\nFinal file created:", output_file,"\n"
sys.exit()
