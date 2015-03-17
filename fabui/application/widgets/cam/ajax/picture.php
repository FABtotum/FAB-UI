<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/utilities.php';
//raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o file.jpg  -t 0
$_image   = '/var/www/temp/picture.jpg';

if(!file_exists($_image)){
	write_file($_image, '', 'w');
}
chmod($_image, 0777);
$_command = 'sudo raspistill -n -hf -t 1 -rot 90 -awb sun -ISO 800 -w 768 -h 1024 -o '.$_image;
shell_exec ( $_command );
//sleep(6);
$_response_items['command'] = $_command;
header('Content-Type: application/json');
echo json_encode($_response_items);
?>