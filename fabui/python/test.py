import re
a="G01 Z1234 X123 Y293 F23100"

m = re.search('Z(.+?) ', a)
if m:
	found = m.group(1)
	print found 
	found = float(found)+1.5
	b=re.sub('Z.*? ','F'+str(found)+' ',a, flags=re.DOTALL)
	print b

