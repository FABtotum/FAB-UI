<?php
/** GET DATA FROM POST */
$task_id = $_POST['task_id'];
$scan_file_monitor    = $_POST['scan_monitor_file'];
$process_file_monitor = $_POST['pprocess_monitor_file'];
$isprobing            = $_POST['isprobing'];
$isprobing            = $isprobing == 'true' ? true : false;

/** load scan monitor file */
$_status_scan     = json_decode(file_get_contents($scan_file_monitor), TRUE);

$_response_items['scan']     = $_status_scan;


/** only if is not a probing scan */
if(!$isprobing){
                
    /** load pprocess monitor file */
    $_status_pprocess = json_decode(file_get_contents($process_file_monitor), TRUE);
    $_response_items['pprocess'] = $_status_pprocess;
                
}


/** monitor response */
header('Content-Type: application/json');
echo json_encode($_response_items); 


?>