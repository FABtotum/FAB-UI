<?php
/** CHECK IF MENU HAVE TO BE FREEZED */
require_once '/var/www/fabui/script/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';


$_internet = is_internet_avaiable();

/** CHECK FOR UPDATES */
$_updates['number'] = 0;

if($_internet){
	
	
    
    $myfab_update  = myfab_get_local_version() < myfab_get_remote_version();    	
   	$marlin_update = marlin_get_local_version() < marlin_get_remote_version();
    
	
	
    if($myfab_update){
      $_updates['number']++;  
    }
    
    if($marlin_update){
      $_updates['number']++;  
    } 
}



$_tasks['items']  = array();

/** LOAD DB */
$db = new Database();
/** GET ALL RUNNING TASKS */
$_tasks_rows = $db->query('select * from sys_tasks where status="running"');
/** CLOSE DB CONNECTION */
$_tasks_number = $db->get_num_rows();  
$db->close();


$_tasks['number'] = $_tasks_number;
if($_tasks_rows){

	$_tasks['number'] = $_tasks_number;
	
	if($_tasks_number >  1){
	    foreach($_tasks_rows as $_t){
	        $_tasks['items'][] = $_t;
	    }   
	}

	if($_tasks_number == 1){
		
		$_tasks['items'][] = $_tasks_rows;
		
	}
		
}





$_response_items = array();

$_response_items['updates'] = $_updates;
$_response_items['tasks']   = $_tasks;
$_response_items['internet'] = $_internet;

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header('Content-Type: application/json');
echo minify(json_encode($_response_items)); 




















?>