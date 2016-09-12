import re
import subprocess as sub

def get_layers_count(file, max_lines = 500):
    objects = []
    total = 0
    end_file = sub.Popen(['tail', file, '-n ' + str(max_lines)],stdout=sub.PIPE,stderr=sub.PIPE)
    output, errors = end_file.communicate()
    last_lines = output.splitlines()
    for line in last_lines:
        search = re.search('; layer\s([0-9]+)', line)
        if search != None:
            total = int(search.group(1))
    objects.append(total)
    return objects

def process_comment(comment):
    if('; layer' in comment):
        comment_splitted = comment.split('; layer')
        return 'layer',  comment_splitted[1].strip().split(',')[0]
    return None