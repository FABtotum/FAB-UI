#!/bin/bash

usage() {
   echo "uso: $0 <new hostname>"
   exit 1
}

[ "$1" ] || usage

CURRENT_HOSTNAME=$(</etc/hostname)
NEW_HOSTNAME=$1
NEW_SERVICE_DESCRIPTION=$2

echo "Setting new hostname for Avahi-daemon.."
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