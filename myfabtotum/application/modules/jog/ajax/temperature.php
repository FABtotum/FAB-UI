<?php
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/application/libraries/Serial.php';


/** LOAD SERIAL CLASS */
$serial = new Serial();

$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();


$_command = 'M105';


$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();
$serial->serialflush();

$serial->deviceClose();

$ext_temp = '';
$bed_temp = '';


if($response != ''){
    
    
    $temperature = str_replace('ok ', '', $response);
    $temperature = explode(' ', $temperature);
    $ext_temp    = explode(':', $temperature[0])[1];
    $bed_temp    = explode(':', $temperature[2])[1];
    
}

header('Content-Type: application/json');
echo json_encode(array('ext' => $ext_temp, 'bed'=>$bed_temp));



?>