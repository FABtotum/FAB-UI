#!/bin/sh
sudo wget https://pypi.python.org/packages/source/p/pyserial/pyserial-3.0.1.tar.gz -P /usr/lib/pymodules/python2.7
cd /usr/lib/pymodules/python2.7
sudo tar -xzvf pyserial-3.0.1.tar.gz
cd pyserial-3.0.1
sudo python setup.py install
cd ..
rm -r pyserial-3.0.1.tar.gz