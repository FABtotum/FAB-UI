import os, sys, getopt

usage='sudo python connection_setup.py -n<networkname> -p<password>'

try:
    opts, args = getopt.getopt(sys.argv[1:],"hn:p:",["network=","password="])
except getopt.GetoptError:
    #Error handling for unknown or incorrect number of options
    print usage
    sys.exit(2)
for opt, arg in opts:
   if opt == '-h':
      print usage
      sys.exit()
   elif opt in ("-n", "--network"):
      ssid = arg
   elif opt in ("-p", "--password"):
      password = arg
	  
#prepare config

network_config="""auto lo
iface lo inet loopback

allow-hotplug eth0
	auto eth0
	iface eth0 inet static
	address 169.254.1.2
	netmask 255.255.0.0

allow-hotplug wlan0
    auto wlan0
    iface wlan0 inet dhcp
    wpa-ssid """ +'"'+ ssid + '"'+ """
    wpa-psk """+ '"'+ password +'"'
	  
#compile wifi
handle= open('/etc/network/interfaces', 'w')  
print>>handle, network_config

#print "Configuration has been updated"

sys.exit()  