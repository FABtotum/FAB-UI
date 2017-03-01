#!/bin/bash
python /var/www/fabui/python/force_reset.py
cat /dev/null > /var/www/temp/macro_trace
sleep 1
echo "M300\r\n" > /dev/ttyAMA0
echo "M728\r\n" > /dev/ttyAMA0
sudo shutdown -r now