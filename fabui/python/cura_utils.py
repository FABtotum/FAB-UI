import re
import subprocess as sub


''' GET TOTAL LAYER - SEARCH FOR: {Layer count:}
def get_layers_count(file, max_lines = 50):
     READ FIRST MAX_LINES 
    start_file = sub.Popen(['head', '-' + str(max_lines), file],stdout=sub.PIPE,stderr=sub.PIPE)
    output, errors = start_file.communicate()
    first_lines = output.splitlines()
    for line in first_lines:
        search = re.search('(?<=Layer count:)([+|-]*[0-9]*.[0-9]*)', line);
        if search != None:
            return int(search.group(1)) 
    return 0 '''

''' IF IS A VALID CURA COMMENT RETURN THE INFO '''
def process_comment(comment):
    if(';layer:' in comment.lower()):
        comment_splitted = comment.split(':')
        return 'layer',  comment_splitted[1]
    return None

''' IF IS A VALID CURA COMMENT RETURN THE INFO (use more cpu)
def process_comment(comment):
    layer_match = re.search(';LAYER:(\d+)', comment)
    if(layer_match != None):
        return 'layer', layer_match.group(1)
    return None
'''

def get_layers_count(file):
    objects = []
    with open(file) as f:
        for line in f:
            if('layer count:' in line.lower()):
                line_splitted = line.split(':')
                objects.append(line_splitted[1].strip())
    f.close()
    return objects