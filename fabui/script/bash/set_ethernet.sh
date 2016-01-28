#!/bin/sh

IP=${1}

if [ -z "$IP" ]; then
	echo "missing ip parameter"
	exit
fi

CONFIG="auto lo\niface lo inet loopback\n\nallow-hotplug eth0\nauto eth0\niface eth0 inet static\naddress $IP\nnetmask 255.255.0.0\n\nallow-hotplug wlan0\nauto wlan0\niface wlan0 inet dhcp\nwpa-conf /etc/wpa_supplicant/wpa_supplicant.conf"

#echo $CONFIG

sudo cp /etc/network/interfaces /etc/network/interfaces.sav
sudo chmod 666 /etc/network/interfaces
echo $CONFIG > /etc/network/interfaces
sudo chmod 644 /etc/network/interfaces
sudo /etc/init.d/networking reload