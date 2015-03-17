import datetime
import time
import mysql.connector
from ws4py.client.threadedclient import WebSocketClient
import json
import ConfigParser
    
cnx = mysql.connector.connect(user='root', database='fabtotum', password="fabtotum")
cursor = cnx.cursor()
query = ('select * from sys_tasks where status="running"')
cursor.execute(query)
rows = cursor.fetchall()
num_rows =  len(rows)
items=[]
count=0
#ws.send('{"type": "notifications"}')

print 'select * from sys_tasks where status="running"'

for row in rows:
    item = {'id': row[0], 'user': row[1], 'controller':row[2], 'type':row[3], 'status': row[4], 'attributes':row[5], 'start_date': str(row[6])}
    items.append(item)


tasks = {'number': num_rows, 'items' : items}
response = {'type': 'tasks', 'data': tasks}

cursor.close()
cnx.close()


config = ConfigParser.ConfigParser()
config.read('/var/www/fabui/python/config.ini')


host=config.get('socket', 'host')
port=config.get('socket', 'port')

#
#ws = WebSocketClient('ws://'+host +':'+port+'/', protocols=['http-only', 'chat'])

#ws.connect();

#message = json.dumps(response)

#ws.send(message)
#ws.close()
#time.sleep(2)