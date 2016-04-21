#!/bin/bash
echo  > /var/www/temp/LOCK
python /var/www/fabui/python/force_reset.py
> /var/www/temp/fab_ui_safety.json
sleep 3
php /var/www/fabui/script/boot.php