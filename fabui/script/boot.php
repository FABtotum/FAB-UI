<?php
/**
 *  BOOT SCRIPT FILE - INITIALIZE MACHINE CUSTOM PARAMETERS
 * 
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';


define('TIME_TO_SLEEP', 1);

//==================================================================
//force serial flush
shell_exec('sudo python '.PYTHON_PATH.'flush.py');
sleep(1);

//==================================================================
//load config
$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

//init serial
$serial = new Serial;
$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial -> serialflush();
//==================================================================
//alive machine
$serial -> serialflush();
$serial->sendMessage('M728'.PHP_EOL);
sleep(TIME_TO_SLEEP);
$alive_machine = $serial->readPort();
//==================================================================
$serial -> serialflush();
$serial->sendMessage('M701 S'.$configs['color']['r'].PHP_EOL); //red
sleep(TIME_TO_SLEEP);
$color_red = $serial->readPort();

//==================================================================
$serial -> serialflush();
$serial->sendMessage('M702 S'.$configs['color']['g'].PHP_EOL); //green
sleep(TIME_TO_SLEEP);
$color_green = $serial->readPort();

//==================================================================
$serial -> serialflush();
$serial->sendMessage('M703 S'.$configs['color']['b'].PHP_EOL); //blue
sleep(TIME_TO_SLEEP);
$color_blue = $serial->readPort();

//==================================================================
//set safety door open: enable/disable warnings
$serial -> serialflush();
$serial->sendMessage('M732 S'.$configs['safety']['door'].PHP_EOL);
sleep(TIME_TO_SLEEP);
$safety_door = $serial->readPort();

//==================================================================
//set homing preferences
$serial -> serialflush();
$serial->sendMessage('M714 S'.$configs['switch'].PHP_EOL);
sleep(TIME_TO_SLEEP);
$homing_preferences = $serial->readPort();
//==================================================================
//get hardware version
$serial -> serialflush();
$serial->sendMessage('M763'.PHP_EOL);
sleep(TIME_TO_SLEEP);
$hw_id_reply = $serial->readPort();
$hw_id = trim(str_replace('ok', '', $hw_id_reply));
//==================================================================
$serial->deviceClose();
//==================================================================
//include and exec specific hardware config settings
if(file_exists(dirname(__FILE__).'/hardware/settings_'.$hw_id.'.php')){
		
	include dirname(__FILE__).'/hardware/settings_'.$hw_id.'.php';

}else{
	$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);	
	$configs['feeder']['show'] = true;

	file_put_contents(FABUI_PATH.'config/config.json', json_encode($configs));
}
//==================================================================
?>