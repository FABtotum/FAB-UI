<?php

/**
 * 
 * SETTING VERSION 3
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';


define('HARDWARE_ID', 3);
define('SHOW_FEEDER', false);

//load config
$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

//set configs
$configs['hardware']['id'] = HARDWARE_ID;
$configs['feeder']['show'] = SHOW_FEEDER;

//write version
file_put_contents(FABUI_PATH.'config/config.json', json_encode($configs));

?>