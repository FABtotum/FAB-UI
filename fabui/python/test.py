import time

start = time.clock()

time.sleep(10);

end = time.clock()


diff = end-start

print "%.2gs" % (diff)