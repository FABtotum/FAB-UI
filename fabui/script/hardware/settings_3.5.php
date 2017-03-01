<?php

/**
 * 
 * SETTING VERSION 3.5 - Prototipo
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';

$ini_array = parse_ini_file(SERIAL_INI);

define('HARDWARE_ID', 3.5);
define('SHOW_FEEDER', false);
define('E_MODE', 177.777778);
define('A_MODE',  88.888889);

//init serial
$serial = new Serial;
$serial->deviceSet($ini_array['port']);
$serial->confBaudRate($ini_array['baud']);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial -> serialflush();

/**
 * 
 *  INVERT X ENDSTOP LOGIC - M732
 * 
 */
$serial->sendMessage('M747 X1'.PHP_EOL);
sleep(0.5);
//close serial
$serial->deviceClose();


//load config
$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

//set configs
//$configs['e'] = E_MODE;
$configs['a'] = A_MODE;

$configs['hardware']['id'] = HARDWARE_ID;
$configs['feeder']['show'] = SHOW_FEEDER;

//write version
file_put_contents(FABUI_PATH.'config/config.json', json_encode($configs));

?>