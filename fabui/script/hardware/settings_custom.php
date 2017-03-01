<?php

/**
 * 
 * CUSTOM SETTINGS - DEFINED IN SETTINGS->ADVANCED
 * 
 * 
 */

require_once '/var/www/lib/config.php';

$ini_array = parse_ini_file(SERIAL_INI);

//load config
$configs          = json_decode(file_get_contents(FABUI_PATH.'config/custom_config.json'), TRUE);
$custom_overrides = file_get_contents($configs['custom_overrides']);



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
 * SEND COUSTOM OVERRIDES
 */

/**
 * INVERT X ENDSTOP LOGIC
 */
$extra_ovverride = 'M747 X';
$extra_ovverride = $configs['invert_x_endstop_logic'] ? 'M747 X1' : 'M747 X0';

$custom_overrides =  $extra_ovverride.PHP_EOL.$custom_overrides;
$serial->sendMessage($custom_overrides.PHP_EOL);
$serial->sendMessage('M500'.PHP_EOL);
sleep(0.5);
$serial->deviceClose();

?>