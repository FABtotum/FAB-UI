#!/bin/sh

SSID=${1}
PASSWORD=${2}

CONFIG="ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev\nupdate_config=1\n\nnetwork={\n\tssid=\"$SSID\"\n"

if [ -z "$PASSWORD" ] ; then
	CONFIG="$CONFIG\tkey_mgmt=NONE\n"
else
	CONFIG="$CONFIG\tpsk=\"$PASSWORD\"\n"
fi
CONFIG="$CONFIG\tscan_ssid=1\n}"


sudo chmod 666 /etc/wpa_supplicant/wpa_supplicant.conf
echo $CONFIG > /etc/wpa_supplicant/wpa_supplicant.conf
sudo chmod 644 /etc/wpa_supplicant/wpa_supplicant.conf

sudo ifdown wlan0
sudo ifup wlan0