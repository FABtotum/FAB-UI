import sys
import requests  #pip install requests!

url = 'http://update.fabtotum.com/reports/post.php'
files = {'file': open('report.txt', 'rb')}
info={'ID':'1234'}
r = requests.post(url,info, files=files)
print r.text
sys.exit()