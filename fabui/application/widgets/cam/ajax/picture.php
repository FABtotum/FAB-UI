<?php
//raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o file.jpg  -t 0
$_image   = '/var/www/temp/picture.jpg';
//$_command = 'sudo raspistill -t 2000 -o '.$_image.' ' ;
$_command = 'raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o '.$_image.'  -t 0';
shell_exec ( $_command );

$_response_items['command'] = $_command;
header('Content-Type: application/json');
echo json_encode($_response_items);
?>