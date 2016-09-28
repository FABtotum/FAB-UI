<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

$action = $_POST['action'];

$end_command = ' & echo $!';

if($action == 'pre_unload'){
	$end_command = '';
}

$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m '.$action.'_spool > /dev/null '.$end_command;
shell_exec($command);
$_response_items = array();

$_response_items['command']      = $command;
$_response_items['uri_trace']    = '/temp/macro_trace';
$_response_items['uri_response'] = '/temp/macro_response';
$_response_items['response'] = file_get_contents(TEMP_PATH.'macro_response');
/** WAIT JUST 1 SECOND */
sleep(1);
header('Content-Type: application/json');
echo minify(json_encode($_response_items));

?>