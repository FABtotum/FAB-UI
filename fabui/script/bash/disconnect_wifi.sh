#!/bin/bash

usage()
{
cat << EOF
usage: $0 interface

This script disconnect a wifi interface.
EOF
}

if [ -z "$1" ]; then
    usage
    exit 1
fi

IFACE="$1"

wpa_cli -p /run/wpa_supplicant -i$IFACE disconnect