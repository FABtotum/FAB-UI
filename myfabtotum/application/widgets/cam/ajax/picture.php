<?php

$_image   = '/var/www/temp/picture.jpg';
$_command = 'sudo raspistill -t 2000 -o '.$_image.' ' ;
shell_exec ( $_command );

$_response_items['command'] = $_command;
header('Content-Type: application/json');
echo json_encode($_response_items);


?>