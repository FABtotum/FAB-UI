#!/bin/bash

cat <<EOF> /etc/wpa_supplicant/wpa_supplicant.conf
ctrl_interface=DIR=/run/wpa_supplicant GROUP=netdev
update_config=1
EOF
sudo ifdown wlan0
sudo ifup wlan0