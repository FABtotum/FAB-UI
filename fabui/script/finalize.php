<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';
//require_once '/var/www/lib/log4php/Logger.php';

// INCLUDE MAIL CLASS (CI)
require_once FABUI_PATH.'system/libraries/Email.php'; 


/* INIT LOG **/
//Logger::configure(FABUI_PATH.'config/log_fabui_config.xml');
//$log = Logger::getLogger('finalize');
//$log->info('=====================================================');

/** GET ARGS FROM COMMAND LINE */
$_task_id       = $argv[1];
$_type          = $argv[2];
$_status        = isset($argv[3]) && $argv[3] != '' ? $argv[3] : 'performed';
//$_g_pusher_type = isset($argv[4]) && $argv[4] != '' ? $argv[4] : 'fast';


/*
echo "FINALIZE".PHP_EOL;
echo $_task_id.PHP_EOL;
echo $_type.PHP_EOL;
echo $_status.PHP_EOL;
*/

switch($_type){

	case 'print':
		finalize_print($_task_id, $_status);
		break;
	case 'slice':
		finalize_slice($_task_id, $_status);
		break;
	case 'mesh':
		finalize_mesh($_task_id, $_status);
		break;
	case 'self_test':
		finalize_self_test($_task_id, $_status);
		break;
	case 'update_fw':
		finalize_update_fw($_task_id, $_status);
		break;
	case 'scan_r':
	case 'scan_p':
	case 'scan_s':
	case 'scan_pg':
	case 'scan':
		finalize_scan($_task_id, $_type, $_status);
		break;		
	default:
		finalize_general($_task_id, $_type, $_status);
		
}


//$log->info('=====================================================');

/** UPDATE TASK ON DB 
 * 
 * @param $tid: TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 ***/
function update_task($tid, $status){
	//global $log;
	
	//LOAD DB
	$db = new Database();
	
	$_data_update = array();
	$_data_update['status']      = $status;
	$_data_update['finish_date'] = 'now()';
	
	$db->update('sys_tasks', array('column' => 'id', 'value' => $tid, 'sign' => '='), $_data_update);
	$db->close();
	
	shell_exec('sudo php '.SCRIPT_PATH.'/notifications.php &');
	//$log->info('Task #'.$tid.' updated. New status: '.$status);
	
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE PRINT TASK 
 * 
 * @param $tid - TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/
function finalize_print($tid, $status){
	//global $log;
	
	//$log->info('Task #'.$tid.' print '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	
	$reset = false;
	
	//CHECK IF TASK WAS ALREARDY FINALIZED
	if($task['status'] == 'stopped' || $task['status'] == 'performed'){
		//$log->info('Task #'.$tid.' already finalized. Exit');
		return;
	}
	
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	
	$print_type = $attributes['print_type'];
	
	
	if($status == 'stopped' && $print_type == 'additive'){
		
		//IF % PROGRESS IS < 0.5 FOR SECURITY REASON I RESET THE BOARD CONTROLLER
		$monitor = json_decode(file_get_contents($attributes['monitor']), TRUE);
		$percent = $monitor['print']['stats']['percent'];
		
		
		if($percent < 0.2){
			
			/** FORCE RESET CONTROLLER */
			$_command = 'sudo python '.PYTHON_PATH.'force_reset.py';
			shell_exec($_command);
			$reset = true;
			//$log->info('Task #'.$tid.' reset controller');
		}
		
	}
	
	//GET TYPE OF FILE (ADDITIVE OR SUBTRACTIVE) FOR ENDING MACRO
	$file = $db->query('select * from sys_files where id='.$attributes['id_file']);
	
	
	//UPDATE TASK
	update_task($tid, $status);
	
	$_macro_end_print_response = TEMP_PATH.'macro_response';
	$_macro_end_print_trace    = TEMP_PATH.'macro_trace';
	
	/*
	if(($file['print_type'] == 'additive') && !$reset){
		echo 'sudo python '.PYTHON_PATH.'gmacro.py end_print_additive_safe_zone '.$_macro_end_print_trace.' '.$_macro_end_print_response.' > /dev/null &';
		shell_exec('sudo python '.PYTHON_PATH.'gmacro.py end_print_additive_safe_zone '.$_macro_end_print_trace.' '.$_macro_end_print_response.' > /dev/null &');
	}
	*/
	
	$end_macro =  $file['print_type'] == 'subtractive' ? 'end_print_subtractive' : 'end_print_additive';
		
	write_file($_macro_end_print_trace, '', 'w');
	chmod($_macro_end_print_trace, 0777);
	
	write_file($_macro_end_print_response, '', 'w');
	chmod($_macro_end_print_response, 0777);
	
	//EXEC END MACRO
	shell_exec('sudo python '.PYTHON_PATH.'gmacro.py '.$end_macro.' '.$_macro_end_print_trace.' '.$_macro_end_print_response.' > /dev/null &');
	

	//$log->info('Task #'.$tid.' end macro: '.$end_macro);
	
	sleep(2);
	
	shell_exec('sudo kill '.$attributes['pid']);

	// SEND MAIL
	if(isset($attributes['mail']) && $attributes['mail'] == 1 && $status == 'peformed'){
		
		
		$user = $db->query('select * from sys_user where id='.$task['user']);
		
		// CREATE IMAGE TO SEND
		
		write_file($attributes['folder'].'print.jpg', '', 'w');
		chmod($attributes['folder'].'print.jpg', 0777);
		
		// TAKE PICTURE
		shell_exec ( 'sudo raspistill -hf -vf -rot 90  --exposure off -awb sun -ISO 400 -w 768 -h 1024 -o '.$attributes['folder'].'print.jpg'.'  -t 0' );
		
		$email = new CI_Email();
		
		$config['mailtype'] = 'html';
		$email->initialize($config);

		$email->from('noreplay@fabtotum.com', 'Your Personal Fabricator - Fabtotum');
		$email->to($user['email']);  
		
		// ATTACH
		$email->attach($attributes['folder'].'print.jpg');

		$email->subject('Your print is finished');
		
		$message = 'Dear <strong>'.ucfirst($user['first_name']).'</strong>,<br>i want to inform you that the print is finished right now';
	
		$email->message($message);
		
		if ( ! $email->send()){
			//$log->error('Task #'.$tid.' mail sent to '.$user['email']); 
		}else{
			//$log->info('Task #'.$tid.' mail sent to '.$user['email']);
		}
						
	}
	
	$db->close();
	
	//WAIT FOR THE UI TO FINALIZE THE PROCESS
	//sleep(7);
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']);
	
	if($reset){
			
		sleep(2);
		include '/var/www/fabui/script/boot.php';
	} 
	
	//$log->info('Task #'.$tid.' end finalizing');
	
	
	
		
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE SLICE TASK 
 * 
 * @param $tid - TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/
function finalize_slice($tid, $status){
	//global $log;
	
	//$log->info('Task #'.$tid.' slice '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	
	//CHECK IF TASK WAS ALREARDY FINALIZED
	if($task['status'] == 'stopped' || $task['status'] == 'performed'){
		//$log->info('Task #'.$tid.' already finalized. Exit');
		exit;
	}
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	
	
	if($status == 'stopped'){ // IF STATUS IS STOPPED JUST KILL ALL PROCESSESS
		//KILL ALL PROCESSESS
		shell_exec('sudo kill '.$attributes['slicer_pid']);
		shell_exec('sudo kill '.$attributes['perl_pid']);
		//$log->info('Task #'.$tid.' kill process #'.$attributes['slicer_pid'].' #'.$attributes['perl_pid']);
		
	}else{
		
		
		//MOVE OUTPUT FILE TO OBJECT FOLDER
		$_id_object     = $attributes['id_object'];
	    $id_file        = $attributes['id_new_file'];
	    $_output        = $attributes['output'];
	    $_configuration = $attributes['configuration'];
		
		$_output_file_name          = get_name($_output);
	    $_output_extension          = get_file_extension($_output_file_name);
	    $_output_folder_destination = '/var/www/upload/'.str_replace('.', '', $_output_extension).'/';
	    $_output_file_name          = set_filename($_output_folder_destination, $_output_file_name);
		
		//MOVE TO FINALLY FOLDER
	    shell_exec('sudo cp '.$_output.' '.$_output_folder_destination.$_output_file_name);
		//$log->info('Task #'.$tid.' file moved in:'.$_output_folder_destination.$_output_file_name);
		
		//ADD PERMISSIONS
	    shell_exec('sudo chmod 746 '.$_output_folder_destination.$_output_file_name);
		
		//UPDATE FILE RECORD TO DB
		$data_file['file_name']   = $_output_file_name;
	    $data_file['file_path']   = $_output_folder_destination;
	    $data_file['full_path']   = $_output_folder_destination.$_output_file_name;
	    $data_file['raw_name']    = str_replace($_output_extension, '', $_output_file_name);
	    $data_file['orig_name']   = $_output_file_name;
	    $data_file['file_ext']    = $_output_extension;
	    $data_file['file_size']   = filesize($_output_folder_destination.$_output_file_name);
	    $data_file['print_type']  = print_type($_output_folder_destination.$_output_file_name);
	    $data_file['note']        = 'Sliced on '.date("F j, Y, g:i a");
		$data_file['insert_date'] = 'now()';
		$data_file['file_type']   = 'text/plain';
		
		$db->update('sys_files', array('column' => 'id', 'value' => $id_file, 'sign' => '='), $data_file);
		
		
		//ADD ASSOCIATION OBJECT->FILE
		$data['id_obj']  = $_id_object;
	    $data['id_file'] = $id_file;
	    
	    $id_ass = $db->insert('sys_obj_files', $data);
		
		/** LAUNCH GCODE ANALYZER */
		shell_exec('sudo php '.SCRIPT_PATH.'/gcode_analyzer.php '.$id_file.' > /dev/null & echo $!');
	}
	
	$db->close();
	
	//UPDATE TASK
	update_task($tid, $status);
	
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']); 
	//$log->info('Task #'.$tid.' end finalizing');
	
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE SELF TEST TASK 
 * 
 * @param $tid - TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/
function finalize_self_test($tid, $status){
	
	//global $log;
	
	//$log->info('Task #'.$tid.' self test '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	
	$db->close();
	
	//UPDATE TASK
	update_task($tid, $status);
	
	//SLEEP MORE TO LET THE UI REFRESH	
	//sleep(5);
	
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']); 
	//$log->info('Task #'.$tid.' end finalizing');
	
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE UPDATE FIRMWARE TASK 
 * 
 * @param $tid - TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/
function finalize_update_fw($tid, $status){
	
	//global $log;
	
	//$log->info('Task #'.$tid.' update FW '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	$db->close();
	
	//UPDATE TASK
	update_task($tid, $status);
	//START UP THE BOARD
    shell_exec('sudo python '.PYTHON_PATH.'gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log > /dev/null &');
    sleep(10);
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']); 
	//$log->info('Task #'.$tid.' end finalizing');
	
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE UPDATE FIRMWARE TASK 
 * 
 * @param $tid - TASK ID
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/

function finalize_mesh($tid, $status){
	
	//global $log;
	
	//$log->info('Task #'.$tid.' mesh '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	
	
	//MOVE OUTPUT FILE TO OBJECT FOLDER
    $_id_object     = $attributes['id_object'];
    $id_file        = $attributes['id_new_file'];
    $_output        = $attributes['output'];
    
    $_output_file_name          = get_name($_output);
    $_output_extension          = get_file_extension($_output);
    $_output_folder_destination = '/var/www/upload/'.str_replace('.', '', $_output_extension).'/';
    $_output_file_name          = set_filename($_output_folder_destination, $_output_file_name);
	
	// MOVE TO FINALLY FOLDER
    shell_exec('sudo cp '.$_output.' '.$_output_folder_destination.$_output_file_name);
    // ADD PERMISSIONS
    shell_exec('sudo chmod 746 '.$_output_folder_destination.$_output_file_name);
	
	
	// INSERT RECORD TO DB
    $data_file['file_name']   = $_output_file_name;
    $data_file['file_path']   = $_output_folder_destination;
    $data_file['full_path']   = $_output_folder_destination.$_output_file_name;
    $data_file['raw_name']    = str_replace($_output_extension, '', $_output_file_name);
	$data_file['client_name']    = str_replace($_output_extension, '', $_output_file_name);
    $data_file['orig_name']   = $_output_file_name;
    $data_file['file_ext']    = $_output_extension;
    $data_file['file_size']   = filesize($_output_folder_destination.$_output_file_name);
    $data_file['print_type']  = print_type($_output_folder_destination.$_output_file_name);
    $data_file['note']        = 'Reconstructed on '.date("F j, Y, g:i a");
	$data_file['insert_date'] = 'now()';
	$data_file['file_type']   = 'application/octet-stream';
    
    
    // ADD TASK RECORD TO DB 
    $db->update('sys_files', array('column' => 'id', 'value' => $id_file, 'sign' => '='), $data_file);
	
	// ADD ASSOCIATION OBJ FILE
    $data['id_obj']  = $_id_object;
    $data['id_file'] = $id_file;
    
    $id_ass = $db->insert('sys_obj_files', $data);
    
    $db->close();
	
	//UPDATE TASK
	update_task($tid, $status);
	sleep(10);
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']);
	//$log->info('Task #'.$tid.' end finalizing');
	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE GENERAL TASK 
 * 
 * @param $tid - TASK ID
 * @param $type - TASK TYPE)
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/

function finalize_general($tid,$type,$status){
	
	//global $log;
	
	//$log->info('Task #'.$tid.' '.$type.' '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	$db->close();
	
	//UPDATE TASK
	update_task($tid, $status);
	sleep(10);
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']);
	//$log->info('Task #'.$tid.' end finalizing');
	
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/** FINALIZE SCAN TASK 
 * 
 * @param $tid - TASK ID
 * @param $type - TASK TYPE
 * @param $status - TASK STATUS (STOPPED - PERFORMED)
 * 
 **/

 function finalize_scan($tid, $type, $status){
 	//global $log;
	
	//$log->info('Task #'.$tid.' '.$type.' '.$status);
	//$log->info('Task #'.$tid.' start finalizing');
	
	
	
	//LOAD DB
	$db = new Database();
	//GET TASK
	$task = $db->query('select * from sys_tasks where id='.$tid);
	
	//GET TASK ATTRIBUTES
	$attributes = json_decode($task['attributes'], TRUE);
	
	
	
	if($type == 'scan_r' || $type == 'scan_p' || $type=="scan_s" ){
		
		
		sleep(5);
		
		
		$id_obj = $attributes['id_obj'];
    
	    if($attributes['new'] == 'true'){
	        
	        // CREATE & ADD OBJ
	        $_obj_data['obj_name']        = $attributes['obj_name'] == '' ? 'No name object' : $attributes['obj_name'];
	        //$_obj_data['obj_name']        = 'scan_'.$_task_id.'_'.$_attributes['time'];
	        $_obj_data['obj_description'] = 'Object created from scanning  on '.date('l jS \of F Y h:i:s A');
	        $_obj_data['date_insert']     = 'now()';
			$_obj_data['user']            = $task['user'];
			 
	        $id_obj = $db->insert('sys_objects', $_obj_data);
	    }
		
		
		if(!isset($attributes['pprocess_file'])){
			$attributes['pprocess_file'] = 'cloud_'.$tid.'.asc';
		}
		
		// INSERT ASC FILE RECORD TO DB
	    $_data_file['file_name']   = $attributes['pprocess_file'];
		$_data_file['file_type']   = 'application/octet-stream';
		$_data_file['file_path']   = '/var/www/upload/asc/';
		$_data_file['full_path']   = '/var/www/upload/asc/'.$attributes['pprocess_file'];
		$_data_file['raw_name']    = str_replace('.asc', '', $attributes['pprocess_file']);
		$_data_file['orig_name']   = $attributes['pprocess_file'];
		$_data_file['client_name'] = $attributes['pprocess_file'];
		$_data_file['file_ext']    = '.asc';
		$_data_file['file_size']   = filesize($attributes['folder'].$attributes['pprocess_file']);
	    $_data_file['insert_date'] = 'now()';
	    $_data_file['note']        = 'Cloud data file obtained by scanning in '.ucfirst($attributes['mode_name']).' mode on '.date('l jS \of F Y h:i:s A');
		
		
		$id_file = $db->insert('sys_files', $_data_file);
    
	    /** MOVE ASC FILE TO UPLOAD/ASC */
		rename($attributes['folder'].$attributes['pprocess_file'], $_data_file['full_path']);
	    
	    
	    /** ASSOCIATE FILE TO OBJECT */
	    $_data_assoc['id_obj']  = $id_obj;
	    $_data_assoc['id_file'] = $id_file;
	    
	    $id_assoc = $db->insert('sys_obj_files', $_data_assoc);
	    
	    
	    /** UPDATE TASK */
	    $attributes['id_obj']  = $id_obj;
	    $attributes['id_file'] = $id_file;
	    
	    $_data_update['attributes'] = json_encode($attributes);
	    $db->update('sys_tasks', array('column' => 'id', 'value' => $tid, 'sign' => '='), $_data_update);
	    $db->close();
	
		
	}
		
	
	// EXEC MACRO END_SCAN
	
	$_time                 = time();
	$_destination_trace    = $attributes['folder'].'end_scan.trace';
	$_destination_response = $attributes['folder'].'end_scan.log';
	
	write_file($_destination_trace, '', 'w');
	chmod($_destination_trace, 0777);
	
	write_file($_destination_response, '', 'w');
	chmod($_destination_response, 0777);
	
	/** EXEC */      
	$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py end_scan '.$_destination_trace.' '.$_destination_response.'  ';
	$_output_command = shell_exec ( $_command );	

	//UPDATE TASK
	update_task($tid, $status);
	sleep(5);
	//REMOVE ALL TEMPORARY FILES
	shell_exec('sudo rm -rf '.$attributes['folder']);
	//$log->info('Task #'.$tid.' end finalizing');
	
	
 }


?>