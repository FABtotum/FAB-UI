#!/bin/bash

usage() {
	echo "usage: <WIFI_ESSID> <WIFI_PASSWORD>"
	exit 1
}

#essid is mandatory
#["$1"] || usage 

SSID=${1}
PASSWORD=${2}

if [ -z "$SSID" ] ; then
	cat <<EOF> /etc/wpa_supplicant/wpa_supplicant.conf
ctrl_interface=DIR=/run/wpa_supplicant GROUP=netdev
update_config=1
EOF
else
	PWDLINE=""
	if [ -z "$PASSWORD" ] ; then
		PWDLINE="key_mgmt=NONE"
	else
		PWDLINE="psk=\"$PASSWORD\""
	fi

	cat <<EOF > /etc/wpa_supplicant/wpa_supplicant.conf
ctrl_interface=DIR=/run/wpa_supplicant GROUP=netdev
update_config=1

network={
	ssid="$SSID"
	$PWDLINE
}
EOF
fi
sudo ifdown wlan0
sudo ifup wlan0

sudo bash /var/www/fabui/script/bash/cron.sh