<?php
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';


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

$ext_temp   = 0;
$bed_temp   = 0;
$ext_target = 0;
$bed_target = 0;




if(strpos($response, "ok T:") === 0){
    
    $temperature = str_replace('ok ', '', $response);
    $temperature = explode(' ', $temperature);
    
    //print_r($temperature);
    
    $ext_temp    = explode(':', $temperature[0])[1];
    $ext_target  = explode('/', $temperature[1])[1];
    
    $bed_temp    = explode(':', $temperature[2])[1];
    $bed_target  = explode('/', $temperature[3])[1];
    
}

header('Content-Type: application/json');
echo json_encode(array('ext' => $ext_temp, 'ext_target'=>$ext_target, 'bed'=>$bed_temp, 'bed_target'=>$bed_target));



?>