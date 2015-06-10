<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** CREATE LOG FILES */
$_time = $_POST['time'];
$_type = isset($_POST['type']) ? $_POST['type'] : '';



$_destination_trace    = TEMP_PATH.'macro_trace';
$_destination_response = TEMP_PATH.'macro_response';

write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);


/** IF IS ADDITIVE $_raise_bed */
if($_type == 'additive'){
	
	
	$_engage_feeder = isset($_POST['engage_feeder']) && $_POST['engage_feeder'] == 1 ? true : false;
	
	$_raise_bed_macro = $_engage_feeder == true ? 'raise_bed_no_g27' : 'raise_bed';
	
	
	$_raise_bed = 'sudo python '.PYTHON_PATH.'gmacro.py '.$_raise_bed_macro.' /var/www/temp/macro_trace  /var/www/temp/macro_response > /dev/null';
	$_output_command = shell_exec ( $_raise_bed );
	/** SLEEP 1 SEC */
	sleep(1);
	
}else{
	
	
	$_4th_axis_mmode = 'sudo python '.PYTHON_PATH.'gmacro.py 4th_axis_mode /var/www/temp/macro_trace  /var/www/temp/macro_response';
	$_output_command = shell_exec ( $_4th_axis_mmode );
	
}




/** WAIT JUST 1 SECOND */
sleep(1);

/** EXEC COMMAND */
$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py check_pre_print '.$_destination_trace.' '.$_destination_response.' > /dev/null';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));

/** WAIT JUST 1 SECOND */
sleep(1);

$_response = file_get_contents($_destination_response);
$_trace    = file_get_contents($_destination_trace);
$_trace    = str_replace(PHP_EOL, '<br>', $_trace);

//unlink($_destination_response);
//unlink($_destination_trace);



/** RESPONSE */
//$_response_items['check_trace']        = $_destination_trace;
//$_response_items['check_response']     = $_destination_response;
$_response_items['command']            = $_command;
//$_response_items['pid']                = $_pid;
$_response_items['url_check_response'] = host_name().'temp/response_'.$_time.'.log';
$_response_items['response']           = str_replace(PHP_EOL, '', $_response) == 'true' ? true : false;
//$_response_items['real_response']      = $_response;

$_response_items['trace']              = $_trace;
$_response_items['status']             = $_response_items['response']  == true ? 200 : 500;
//$_response_items['status']             = 200;

/** WAIT JUST 1 SECOND */
sleep(1);
header('Content-Type: application/json');
echo minify(json_encode($_response_items));

?>