<?php

/**
 * 
 * SETTING VERSION 1
 * 
 * 
 */

define('E_MODE', 3048.1593);
define('A_MODE', 177.777778); 
require_once '/var/www/lib/config.php';
//load config
$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

//set configs
$configs['hardware']['id'] = 1;
$configs['feeder']['show'] = true;
$configs['e'] = E_MODE;
$configs['a'] = A_MODE;

file_put_contents(FABUI_PATH.'config/config.json', json_encode($configs));

?>