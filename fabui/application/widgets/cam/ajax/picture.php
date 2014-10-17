<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/fabui/ajax/lib/utilities.php';
//raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o file.jpg  -t 0
$_image   = '/var/www/temp/picture.jpg';

if(!file_exists($_image)){
	write_file($_image, '', 'w');
}
chmod($_image, 0777);
$_command = 'sudo raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o '.$_image.'  -t 0';
shell_exec ( $_command );

$_response_items['command'] = $_command;
header('Content-Type: application/json');
echo json_encode($_response_items);
?>