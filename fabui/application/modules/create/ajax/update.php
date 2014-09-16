<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';
/** SAVE DATA FROM POST */
$_id_task        = $_POST['id_task'];
$_monitor_file   = $_POST['monitor_file'];
$_estimated_time = $_POST['estimated_time'];
$_progress_steps = $_POST['progress_steps'];
$_stopped        = intval($_POST['stopped']);
$_stats_file     = $_POST['stats_file'];
$_folder         = $_POST['folder'];


/** LOAD DATA FROM MONITOR JSON */
$_monitor_data = json_decode(file_get_contents($_monitor_file), TRUE);

/** UPDATE ATTRIBUTES VALUES */
$_attributes['estimated_time'] = $_estimated_time;
$_attributes['progress_steps'] = $_progress_steps;

/** UPDATE STATS FILE JSON */
file_put_contents($_stats_file, json_encode($_attributes), FILE_USE_INCLUDE_PATH);

/** GET IF PRINT IS COMPLETED */
$_completed = $_monitor_data['print']['completed'];

//if($_completed == 1 || $_stopped == 1){
if($_stopped == 1){
    
    $_data_update = array();
    //$_data_update['status'] = $_stopped == 1 ? 'stopped' : 'performed';
    $_data_update['status'] = 'stopped';
    $_data_update['finish_date'] = 'now()';
			 
    /** TODO: ADD END_GCODE */
    /** REMOVE ALL TEMPORARY FILES */
    //shell_exec('sudo rm -rf '.$_folder);
    /** LOAD DB */
    $db = new Database();
    /** UPDATE TASK TO DB */
    $db->update('sys_tasks', array('column' => 'id', 'value' => $_id_task, 'sign' => '='), $_data_update);
    /** CLOSE DB CONNECTION */
    $db->close();    
}
            
$_response_items['status'] = 200;
header('Content-Type: application/json');
echo minify(json_encode($_response_items));



?>