<?php
/**
 * read values from eeprom 
 * and save to config files
 * 
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/jog_factory.php';

//read values from eeprom
$eeprom = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_eeprom.py'), true);
//get system info
$system_info = json_decode(shell_exec('sudo python '.PYTHON_PATH.'sysinfo.py'), true);

//save values to config files
if(!file_exists(CONFIG_UNITS)){ //if settings file doesn't exists create it
	shell_exec('sudo php '.FABUI_PATH.'index.php settings recreateSettingsFiles');
}
$units       = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
$customUnits = json_decode(file_get_contents(CUSTOM_CONFIG_UNITS), TRUE);

/**
 * IF VALUE IN EEPROM IS DIFFERENT THAN THE LAST SAVED VALUE
 * IT MEANS THAT SOMETHING WENT WRONG SO IS NEEDED A RESTORE
 */
if(isset($units['e']) && ($eeprom['steps_per_unit']['e'] != round($units['e'],2))){
	
	$eeprom['steps_per_unit']['e'] = $units['e'];
	$jogFactory = new JogFactory();
	$jogFactory -> mdi('M92 E'.$units['e'].PHP_EOL.'M500'.PHP_EOL);
}

$units['e']       = $eeprom['steps_per_unit']['e']; //set steps per unit 
$customUnits['e'] = $eeprom['steps_per_unit']['e']; //set steps per unit

/**
 *  CHECK FOR STEP PER UNIT A mode
 */

if($system_info['hw'] < 4){
	$new_units_a = $units['a'];
	if($eeprom['steps_per_unit']['e'] > 2000){
		$new_units_a = 177.777778;
	}else{
		$new_units_a = 88.888889;
	}
	$units['a'] = $customUnits['a'] = $new_units_a;
}

$units['hardware']['id'] = $customUnits['hardware']['id'] =  $system_info['hw'];
//write
file_put_contents(CONFIG_UNITS,        json_encode($units));
file_put_contents(CUSTOM_CONFIG_UNITS, json_encode($customUnits));

/**
 * CLEAN SESSIONS 
 */
shell_exec('rm -rvf '.TEMP_PATH.'sess_*');
?>
