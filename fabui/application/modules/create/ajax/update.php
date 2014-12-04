<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';
/** SAVE DATA FROM POST */
//$_id_task        = $_POST['id_task'];
$_monitor_file   = $_POST['monitor_file'];
$_estimated_time = $_POST['estimated_time'];
$_progress_steps = $_POST['progress_steps'];
//$_stopped        = intval($_POST['stopped']);
$_stats_file     = $_POST['stats_file'];
//$_folder         = $_POST['folder'];


/** LOAD DATA FROM MONITOR JSON */
$_monitor_data = json_decode(file_get_contents($_monitor_file), TRUE);

/** UPDATE ATTRIBUTES VALUES */
$_attributes['estimated_time'] = $_estimated_time;
$_attributes['progress_steps'] = $_progress_steps;

/** UPDATE STATS FILE JSON */
file_put_contents($_stats_file, json_encode($_attributes, JSON_NUMERIC_CHECK), FILE_USE_INCLUDE_PATH);

/** GET IF PRINT IS COMPLETED */
//$_completed = $_monitor_data['print']['completed'];
            
$_response_items['status'] = 200;
header('Content-Type: application/json');
echo minify(json_encode($_response_items));



?>