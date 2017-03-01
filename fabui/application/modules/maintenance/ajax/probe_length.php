<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/jog_factory.php';

//get actual value from eeprom
$eeprom = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_eeprom.py'), true);
$settings = json_decode(file_get_contents(CONFIG_UNITS), true);

$_response_items['probe_length'] = $eeprom['probe_length'];
$_response_items['z_max'] = $settings['zprobe']['zmax'];


echo json_encode($_response_items);

?>


