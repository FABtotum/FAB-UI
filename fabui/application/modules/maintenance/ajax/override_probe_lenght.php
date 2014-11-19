<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/libraries/Serial.php';


$over = $_POST['over'];


$serial = new Serial();
$probe_lenght = 0;
    
$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

$serial->sendMessage('M503'.PHP_EOL);
$response = $serial->readPort();

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


$new_lenght =  abs($probe_lenght) - $over;



$serial->sendMessage('M710 S'.abs($new_lenght).PHP_EOL);
$response = $serial->readPort();
$serial->serialflush();
$serial->deviceClose();

$_response_items['old_probe_lengt'] = $probe_lenght;
$_response_items['over'] = $over;
$_response_items['probe_length'] = $new_lenght;

echo json_encode($_response_items);


?>


