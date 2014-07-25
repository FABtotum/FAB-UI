<?php
//ini_set('display_errors', '1');
include "php_serial.class.php";

$serial = new phpSerial;
$serial->deviceSet("/dev/ttyAMA0");
$serial->confBaudRate(115200);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
//$serial->sendMessage($value."\r\n");

while(1){
$reply=$serial->readPort();
echo $reply;
} 

$serial->deviceClose();
 