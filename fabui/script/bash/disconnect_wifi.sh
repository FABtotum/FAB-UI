#!/bin/bash

CONFIG="ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev\nupdate_config=1\n"

sudo chmod 666 /etc/wpa_supplicant/wpa_supplicant.conf
echo -e $CONFIG > /etc/wpa_supplicant/wpa_supplicant.conf
sudo chmod 644 /etc/wpa_supplicant/wpa_supplicant.conf

sudo ifdown wlan0
sudo ifup wlan0