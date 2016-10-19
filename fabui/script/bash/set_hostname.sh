#!/bin/bash

OLD_HOSTNAME="$( hostname )"
NEW_HOSTNAME="$1"
NEW_SERVICE_DESCRIPTION=$2

if [ -z "$NEW_HOSTNAME" ]; then
 echo -n "Please enter new hostname: "
 read NEW_HOSTNAME < /dev/tty
fi

if [ -z "$NEW_HOSTNAME" ]; then
 echo "Error: no hostname entered. Exiting."
 exit 1
fi

echo "Changing hostname from $OLD_HOSTNAME to $NEW_HOSTNAME..."

hostname "$NEW_HOSTNAME"

#sed -i "s/HOSTNAME=.*/HOSTNAME=$NEW_HOSTNAME/g" /etc/systemd/network

if [ -n "$( grep "$OLD_HOSTNAME" /etc/hosts )" ]; then
 sed -i "s/$OLD_HOSTNAME/$NEW_HOSTNAME/g" /etc/hosts
else
 echo -e "$( hostname -I | awk '{ print $1 }' )\t$NEW_HOSTNAME" >> /etc/hosts
fi

echo "$NEW_HOSTNAME" > /etc/hostname

echo "Setting $NEW_HOSTNAME as new hostname for Avahi-daemon.."
sudo avahi-set-host-name $NEW_HOSTNAME
echo "Setting service description"
cat <<EOF> /etc/avahi/services/fabtotum.service
<?xml version="1.0" standalone='no'?>
 <!DOCTYPE service-group SYSTEM "avahi-service.dtd">
        <service-group>
                <name replace-wildcards="yes">$NEW_SERVICE_DESCRIPTION (%h)</name>
                <service>
                        <type>_http._tcp</type>
                        <port>80</port>
                        <txt-record>product=Fabtotum Personal Fabricator - 3D Printer</txt-record>
                        <domain-name>local</domain-name>
                </service>
        </service-group>
EOF
echo "Done."