<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

$over = $_POST['over'];

$settings = json_decode(file_get_contents(CONFIG_UNITS), true);

//get actual value from eeprom
$eeprom = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_eeprom.py'), true);
//calc new value
$new_length =  abs($eeprom['probe_length']) - $over;
$old_z_max = $settings['zprobe']['zmax'];
$settings['zprobe']['zmax'] = $settings['zprobe']['zmax'] + $over;

$r = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M710 S'.abs($new_length).'"'));
$r = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M500"'));

file_put_contents(CONFIG_UNITS, json_encode($settings));
/*
$jogFactory = new JogFactory();
$jogFactory -> mdi('M710 S'.abs($new_length).PHP_EOL.'M500'.PHP_EOL);
*/

$_response_items['old_probe_length'] = $eeprom['probe_length'];
$_response_items['old_z_max']       = $old_z_max;
$_response_items['over']            = $over;
$_response_items['probe_length']  = $new_length;
$_response_items['z_max']    =      $settings['zprobe']['zmax'];

echo json_encode($_response_items);
?>


