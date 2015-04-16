<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';

/** SAVE DATA FROM POST */
$_id_task                       = $_POST['task_id'];
$_scan_monitor_file             = $_POST['scan_monitor_file'];
$_pprocess_monitor_file         = $_POST['pprocess_monitor_file'];
$_scan_array_estimated_time     = $_POST['scan_array_estimated_time'];
$_scan_array_progress_steps     = $_POST['scan_array_progress_steps'];
$_pprocess_array_estimated_time = $_POST['pprocess_array_estimated_time'];
$_pprocess_array_progress_steps = $_POST['pprocess_array_progress_steps'];
$_scan_stats_file               = $_POST['scan_stats_file'];
$_pprocess_stats_file           = $_POST['pprocess_stats_file'];


/** LOAD DATA FROM MONITOR JSON */
$_scan_monitor_data     = json_decode(file_get_contents($_scan_monitor_file), TRUE);
$_pprocess_monitor_data = json_decode(file_get_contents($_pprocess_monitor_file), TRUE);


/** UPDATE SCAN STATS ATTRIBUTES VALUES */
$_scan_stats_attributes['estimated_time'] = $_scan_array_estimated_time;
$_scan_stats_attributes['progress_steps'] = $_scan_array_progress_steps;

/** UPDATE STATS FILE JSON */
file_put_contents($_scan_stats_file, json_encode($_scan_stats_attributes), FILE_USE_INCLUDE_PATH);

/** UPDATE PPROCESS STATS ATTRIBUTES VALUES */
$_pprocess_stats_attributes['estimated_time'] = $_pprocess_array_estimated_time;
$_pprocess_stats_attributes['progress_steps'] = $_pprocess_array_progress_steps;

/** UPDATE STATS FILE JSON */
file_put_contents($_pprocess_stats_file, json_encode($_pprocess_stats_attributes), FILE_USE_INCLUDE_PATH);

?>