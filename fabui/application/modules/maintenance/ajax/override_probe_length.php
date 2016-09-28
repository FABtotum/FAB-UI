<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/jog_factory.php';

$ini_array = parse_ini_file(SERIAL_INI);
$over = $_POST['over'];

//get actual value from eeprom
$eeprom = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_eeprom.py'), true);
//calc new value
$new_lenght =  abs($eeprom['probe_length']) - $over;

$jogFactory = new JogFactory();
$jogFactory -> mdi('M710 S'.abs($new_lenght).PHP_EOL.'M500'.PHP_EOL);

$_response_items['old_probe_lengt'] = $eeprom['probe_length'];
$_response_items['over']            = $over;
$_response_items['probe_length']    = $new_lenght;

echo json_encode($_response_items);
?>


