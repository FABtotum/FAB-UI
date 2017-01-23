<?php
//error_reporting(E_ALL);
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';


$security_file['code'] = '';
$security_file['type'] = '';

//file_put_contents('/var/www/temp/fab_ui_safety.json', json_encode($security_file));
write_file('/var/www/temp/fab_ui_safety.json', '', 'w+');

$mode = $_POST['mode'] == 1 ? true : false;



if($mode){
	$command = 'M999-M728';
}else{
	$command = 'M731-M999-M728';
}

header('Content-Type: application/json');
echo shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "'.$command.'"');



?>