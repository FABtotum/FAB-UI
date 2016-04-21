<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';

$ini_array = parse_ini_file(SERIAL_INI);

$serial = new Serial();
$probe_lenght = 0;
    
$serial->deviceSet($ini_array['port']);
$serial->confBaudRate($ini_array['baud']);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial->sendMessage('M503'.PHP_EOL);
$response = $serial->readPort();
$serial->serialflush();
$serial->deviceClose();


$temp = explode(PHP_EOL, $response);
$length_string= '';

foreach($temp as $line){
	
	if(strpos($line, 'Probe Length') !== false ){
		$length_string = $line;	
	}
}

if($length_string != ''){
	$temp_length = explode(':', $length_string);
	$probe_lenght = trim($temp_length[2]);	
}


$_response_items['probe_length'] = $probe_lenght;

echo json_encode($_response_items);

?>


