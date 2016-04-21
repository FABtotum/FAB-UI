#!/bin/bash

package=${1}
action=${2}

PKG_OK=$(dpkg-query -W --showformat='${Status}\n' $package|grep "install ok installed")

if [ "" == "$PKG_OK" ]; then
    echo "$package not installed"
    
    if [ "$action" == "install" ]; then
        echo "Installing $package.."
        sudo apt-get --force-yes --yes install $package
    fi
else
    echo "$package is installed"
    if [ "$action" == "uninstall" ]; then
        echo "Uninstalling $package.."
        sudo apt-get remove --purge --force-yes --yes $package
    fi
fi