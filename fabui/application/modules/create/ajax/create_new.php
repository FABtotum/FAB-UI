<?php 
/**
 * 
 */
// start session
@session_start();
//import support files
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
//define porecessors
define("MULTI_CORE", true);
define("CONTROLLER", 'make');
define("RUNNING_STATUS", 'running');
$createTypes = array('print' => 'print', 'mill' => 'mill', 'laser' => 'laser');
//get user settings
$user = $_SESSION['user'];
/**
 * get params from post
 */
$objectID          = $_POST['object_id'];
$fileID            = $_POST['file'];
$createType        = $_POST['print_type']; //{additive / subtractive} 
$calibration       = $_POST['calibration']; //{classic homing / auto bed leveling}
$go_to_focus_point = $_POST['go_to_focus_point'] == 'true';
//load db class utility
$db = new Database();
//get all file info
$file = $db->query('select * from sys_files where id='.$fileID);
$file = $file[0];
/**
 *  PREAPARE PRINTER TO EXEC TASK
 */

switch($createType)
{
	case 'print':
		$temperatures = prepareAdditive($calibration, $file);
		break;
	case 'mill':
		prepareSubtractive();
		break;
	case 'laser':
		prepareLaser($go_to_focus_point);
		break;
}
/**
 * Init and start task
 */
//add new task to db
$newTask['user']       = $user['id'];
$newTask['controller'] = CONTROLLER;
$newTask['type']       = $createTypes[$createType];
$newTask['status']     = RUNNING_STATUS;
$newTask['id_object']  = $objectID;
$newTask['id_file']    = $fileID;
$newTask['attributes'] = '';
$newTask['start_date'] = 'now()';
$taskID                = $db->insert('sys_tasks', $newTask);
shell_exec('sudo php '.SCRIPT_PATH.'/notifications.php &');
//creating task files support
$timestamp      = time();
$taskFolder     = TASKS_PATH.$createType.'_'.$taskID.'_'.$timestamp.'/';
$monitorFile    = TEMP_PATH.'task_monitor.json';
$dataFile       = $taskFolder.$createType.'_'.$taskID.'_'.$timestamp.'.data';
$traceFile      = TEMP_PATH.'task_trace';
$debugFile      = TEMP_PATH.'task_debug';
$statsFile      = $taskFolder.$createType.'_'.$taskID.'_'.$timestamp.'_stats.json';
$attributesFile = $taskFolder.$createType.'_'.$taskID.'_'.$timestamp.'_attributes.json';
$uriMonitor     = '/temp/task_monitor.json';
$uriTrace       = '/temp/task_trace';
mkdir($taskFolder, 0777);
write_file($monitorFile,    '', 'w+');
write_file($dataFile,       '', 'w+');
write_file($traceFile,      '', 'w+');
write_file($debugFile,      '', 'w+');
write_file($statsFile,      '', 'w+');
write_file($attributesFile, '', 'w+');
//update attributes  on db record
$dataUpdate['attributes']= $attributesFile;
$db->update('sys_tasks', array('column' => 'id', 'value' => $taskID, 'sign' => '='), $dataUpdate);
$db->close();
/**
 * STARTING TASK
 */
switch($createType){
	case 'print':
		$command = 'sudo python '.PYTHON_PATH.'gpusher_fast_multiproc.py "'.$file['full_path'].'" '.$dataFile.' '.$taskID.' --ext_temp '.intval($temperatures['extruder']).' --bed_temp '.intval($temperatures['bed']).' > /dev/null & echo $!';
		break;
	case 'mill':
		$command = 'sudo python '.PYTHON_PATH.'g_mill.py "'.$file['full_path'].'" '.$dataFile.' '.$taskID.'  > /dev/null & echo $!';
		break;
	case 'laser':
		$command = 'sudo python '.PYTHON_PATH.'g_laser.py "'.$file['full_path'].'" '.$dataFile.' '.$taskID.'  > /dev/null & echo $!';
		break;
}
$outputCommand  = shell_exec ( $command );
sleep(2);
$jsonStatus     = file_get_contents($monitorFile, FILE_USE_INCLUDE_PATH);
$status         = json_encode($jsonStatus);
$status_decoded = json_decode($jsonStatus, true);

while($jsonStatus == ''){
	$jsonStatus = file_get_contents($monitorFile, FILE_USE_INCLUDE_PATH);
	$status = json_encode($jsonStatus);
	$status_decoded = json_decode($jsonStatus, true);
}

/**
 * updating db task info
 */
$taskAttributes['monitor']     = $monitorFile;
$taskAttributes['data']        = $dataFile;
$taskAttributes['trace']       = $traceFile;
$taskAttributes['debug'] 	   = $debugFile;
$taskAttributes['id_object']   = $objectID;
$taskAttributes['id_file']     = $fileID;
$taskAttributes['uri_monitor'] = $uriMonitor;
$taskAttributes['uri_trace']   = $uriTrace;
$taskAttributes['folder']      = $taskFolder;
$taskAttributes['stats']       = $statsFile;
$taskAttributes['speed']       = 0;
$taskAttributes['print_type']  = $createType;
$taskAttributes['z_override']  = 0;
$taskAttributes['mail']        = $user['end-print-email'];
$taskAttributes['pid']         = $status_decoded['pid'];
//write attributes also on attributes file
file_put_contents($attributesFile, json_encode($taskAttributes));
//prepare output
$outputResponse['response']        = true;
$outputResponse['status']          = $status;
$outputResponse['id_task']         = $taskID;
$outputResponse['monitor_file']    = $monitorFile;
$outputResponse['data_file']       = $dataFile;
$outputResponse['trace_file']      = $traceFile;
$outputResponse['command']         = $command;
$outputResponse['uri_monitor']     = $uriMonitor;
$outputResponse['uri_trace']       = $uriTrace;
$outputResponse['stats']           = $statsFile;
$outputResponse['folder']          = $taskFolder;
$outputResponse['attributes_file'] = $attributesFile;
echo output($outputResponse);

/**
 * 
 
function output($data)
{
	header('Content-Type: application/json');
	return minify(json_encode($data));
}
*/
/**
 * prepare additive task
 */
function prepareAdditive($calibration, $file)
{
	
	$macroResponse = TEMP_PATH.'macro_response';
	$macroTrace    = TEMP_PATH.'macro_trace';
	
	switch($calibration)
	{
		case 'homing':
			$macroName = 'home_all';
			$doMacro   = false;
			break;
		case 'abl': //auto bed leveling
			$macroName = 'auto_bed_leveling';
			$doMacro = true;
			break;
	}
	
	if($doMacro){
		$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m '.$macroName.' > /dev/null';
		$output = shell_exec($command);
		
		if(str_replace(PHP_EOL, '', file_get_contents($macroResponse)) != 'true')
		{
			$response['response'] = false;
			$response['trace']    = str_replace(PHP_EOL, '<br>',file_get_contents($macroTrace));
			output($response);
			exit();
		}
	}
	//read temperatures from gcode file to print
	$temperatures = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_temperatures.py -f "'.$file['full_path'].'" -n 500'), TRUE);
	//final macro
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m start_print -p1 '.intval($temperatures['extruder']).' -p2 '.intval($temperatures['bed']);
	$output = shell_exec($command);
	if(str_replace(PHP_EOL, '', file_get_contents($macroResponse)) != 'true')
	{
		$response['response'] = false;
		$response['trace']    = str_replace(PHP_EOL, '<br>',file_get_contents($macroTrace));
		output($response);
		exit();
	}
		
	return $temperatures;
	
}
/**
 * prepare subtractive task
 */
function prepareSubtractive()
{
	$macroResponse = TEMP_PATH.'macro_response';
	$macroTrace    = TEMP_PATH.'macro_trace';
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m start_subtractive_print';
	$output = shell_exec($command);
	if(str_replace(PHP_EOL, '', file_get_contents($macroResponse)) != 'true')
	{
		$response['response'] = false;
		$response['trace']    = str_replace(PHP_EOL, '<br>',file_get_contents($macroTrace));
		output($response);
		exit();
	}
}

/**
 *  Prepare laser task
 * */

function prepareLaser($goToFocus)
{
	$macroResponse = TEMP_PATH.'macro_response';
	$macroTrace    = TEMP_PATH.'macro_trace';
	$param1 = $goToFocus ? 1 : 0;
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m start_laser -p1 '.$param1;
	$output = shell_exec($command);
	if(str_replace(PHP_EOL, '', file_get_contents($macroResponse)) != 'true')
	{
		$response['response'] = false;
		$response['trace']    = str_replace(PHP_EOL, '<br>',file_get_contents($macroTrace));
		output($response);
		exit();
	}
}

?>