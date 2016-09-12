<?php
/**
 * read values from eeprom 
 * and save to config files
 * 
 * update corrupted record from db
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';
require_once '/var/www/lib/database.php';

//read values from eeprom
$eeprom = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_eeprom.py'), true);

//save values to config files
$units = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
$customUnits = json_decode(file_get_contents(CUSTOM_CONFIG_UNITS), TRUE);

$units['e']       = $eeprom['steps_per_unit']['e']; //set steps per unit 
$customUnits['e'] = $eeprom['steps_per_unit']['e']; //set steps per unit

//write
file_put_contents(CONFIG_UNITS, json_encode($units));
file_put_contents(CUSTOM_CONFIG_UNITS, json_encode($customUnits));

sleep(5);

/** GET RUNNING TASKS FROM DB  */
$db = new Database();
$query = 'update sys_tasks set status = "removed" where status = "running" or status is null';
$db->query($query);
$db->close();
?>