#!/bin/bash

usage() {
   echo "uso: $0 <new hostname>"
   exit 1
}

[ "$1" ] || usage

CURRENT_HOSTNAME=$(</etc/hostname)
NEW_HOSTNAME=$1
NEW_SERVICE_DESCRIPTION=$2

for file in /etc/hostname /etc/hosts
do
	[ -f $file ] && sed -i.old -e "s:$CURRENT_HOSTNAME:$NEW_HOSTNAME:g" $file
done

sudo /etc/init.d/hostname.sh start

echo "Setting new hostname for Avahi-daemon.."
sudo avahi-set-host-name $NEW_HOSTNAME
echo "Setting service description"
FABOTUM_SERICE="<?xml version=\"1.0\" standalone='no'?>\n
<!DOCTYPE service-group SYSTEM \"avahi-service.dtd\">\n
\t<service-group>\n
\t\t<name replace-wildcards=\"yes\">$NEW_SERVICE_DESCRIPTION (%h)</name>\n
\t\t<service>\n
\t\t\t<type>_http._tcp</type>\n
\t\t\t<port>80</port>\n
\t\t\t<txt-record>product=Fabtotum Personal Fabricator - 3D Printer</txt-record>\n
\t\t\t<domain-name>local</domain-name>\n
\t\t</service>\n
\t</service-group>"
echo -e $FABOTUM_SERICE > /etc/avahi/services/fabtotum.service
