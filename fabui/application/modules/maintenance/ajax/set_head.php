<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/application/config/production/fabtotum.php';

$head        = $_POST['head'];
$pid         = $config['heads_pids'][$head];
$description = $config['heads_list'][$head];


if($pid != ''){
	$r = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "'.$pid.'-M500"'));
}
//set head id
$r = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M793 S'.$config['heads_fw_id'][$head].'-M500"'));
/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH . 'config/config.json'), TRUE);

$_units['hardware']['head']['type']        = $head;
$_units['hardware']['head']['description'] = $description;
$_units['hardware']['head']['max_temp']    = $config['heads_max_temp'][$head];
$_units['hardware']['head']['fw_id']       = $config['heads_fw_id'][$head];

file_put_contents(FABUI_PATH . 'config/config.json', json_encode($_units));

if (file_exists(FABUI_PATH . 'config/custom_config.json')) {
	
	$_custom_units = json_decode(file_get_contents(FABUI_PATH . 'config/custom_config.json'), TRUE);
	$_custom_units['hardware']['head']['type']        = $head;
	$_custom_units['hardware']['head']['description'] = $description;
	$_custom_units['hardware']['head']['max_temp']    = $config['heads_max_temp'][$head];
	$_custom_units['hardware']['head']['fw_id']       = $config['heads_fw_id'][$head];
	
	file_put_contents(FABUI_PATH . 'config/custom_config.json', json_encode($_custom_units));
}
//reset controller
shell_exec('sudo python '.PYTHON_PATH.'boot.py -R -d -f');

echo json_encode(array('head' => $head, 'pid' => $pid, 'description'=>$description));
?>