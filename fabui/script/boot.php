<?php
/**
 *  BOOT SCRIPT FILE - INITIALIZE MACHINE CUSTOM PARAMETERS
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';
require_once '/var/www/fabui/application/config/production/fabtotum.php';
require_once '/var/www/lib/utilities.php';

$ini_array = parse_ini_file(SERIAL_INI);

//==== LOCK FILE
fopen(LOCK_FILE, "w");

define('TIME_TO_SLEEP', 0.3);

//==================================================================

if(!file_exists(FABUI_PATH.'config/config.json')){ //if config doesn't exists then create default
	create_default_config();
}

//load config
$json_config = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

//init serial
$serial = new Serial;
$serial->deviceSet($ini_array['port']);
$serial->confBaudRate($ini_array['baud']);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial -> serialflush();

//=== hw id ======================================================
$serial -> serialflush();
$serial->sendMessage('M763'.PHP_EOL);
sleep(TIME_TO_SLEEP);
$hw_id_reply = $serial->readPort(4096);
$hw_id = trim(str_replace('ok', '', $hw_id_reply));
//==================================================================
//rise probe
$serial -> serialflush();
$serial->sendMessage('M402'.PHP_EOL);
sleep(TIME_TO_SLEEP);
$alive_machine = $serial->readPort();
//alive machine
$serial -> serialflush();
$serial->sendMessage('M728'.PHP_EOL);
sleep(TIME_TO_SLEEP);
$alive_machine = $serial->readPort();


$serial -> serialflush();
$serial->sendMessage('M701 S'.$json_config['color']['r'].PHP_EOL); //red
$serial->sendMessage('M702 S'.$json_config['color']['g'].PHP_EOL); //green
$serial->sendMessage('M703 S'.$json_config['color']['b'].PHP_EOL); //blue
sleep(TIME_TO_SLEEP);
$colors = $serial->readPort();

//==================================================================
//set safety door open: enable/disable warnings
$serial -> serialflush();
$serial->sendMessage('M732 S'.$json_config['safety']['door'].PHP_EOL);
sleep(TIME_TO_SLEEP);
$safety_door = $serial->readPort();

//set collision-warning enable/disable warnings
$serial -> serialflush();
$serial->sendMessage('M734 S'.$json_config['safety']['collision-warning'].PHP_EOL);
sleep(TIME_TO_SLEEP);
$safety_door = $serial->readPort();

//==================================================================
//set homing preferences
$serial -> serialflush();
$serial->sendMessage('M714 S'.$json_config['switch'].PHP_EOL);
sleep(TIME_TO_SLEEP);
$homing_preferences = $serial->readPort();
//==================================================================

//==================================================================
//set head pids
$head = isset($json_config['hardware']['head']['type']) && $json_config['hardware']['head']['type'] != '' ? $json_config['hardware']['head']['type'] : 'hybrid';
$serial -> serialflush();
$serial->sendMessage($config['heads_pids'][$head].PHP_EOL);
sleep(0.1);
$serial -> sendMessage('M500' . PHP_EOL);
sleep(0.1);
$serial -> sendMessage('M793 S'.$config['heads_fw_id'][$head] . PHP_EOL);
sleep(0.1);
$serial -> sendMessage('M500' . PHP_EOL);
$serial -> deviceClose();
//==================================================================
$hw_id = (isset($json_config['settings_type']) && $json_config['settings_type'] == 'custom') ? 'custom' : $hw_id;
//include and exec specific hardware config settings
if(file_exists(dirname(__FILE__).'/hardware/settings_'.$hw_id.'.php')){
	include dirname(__FILE__).'/hardware/settings_'.$hw_id.'.php';
}else{
	
	$json_config = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);	
	$json_config['feeder']['show'] = true;
	file_put_contents(FABUI_PATH.'config/config.json', json_encode($json_config));
	
	
}
//==================================================================
$serial->deviceOpen();
$serial->sendMessage('M999'.PHP_EOL.'M728'.PHP_EOL);
$serial -> deviceClose();
shell_exec('sudo rm '.LOCK_FILE);
?>