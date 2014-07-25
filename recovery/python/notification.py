import time  
import sys,os
#from subprocess import call

while True:
    #call (["sudo php /var/www/myfabtotum/script/notifications.php > /var/www/temp/log.txt"], shell=True)
    os.system('sudo php /var/www/myfabtotum/script/notifications.php > /var/www/temp/log.txt')
    time.sleep(5)
