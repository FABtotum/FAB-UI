<?php
/** CHECK IF MENU HAVE TO BE FREEZED */
require_once '/var/www/myfabtotum/script/config.php';
require_once '/var/www/myfabtotum/ajax/lib/database.php';
require_once '/var/www/myfabtotum/ajax/lib/utilities.php';


/** LOAD DB */
$db = new Database();
/** GET ALL RUNNING TASKS */
$_tasks = $db->query('select * from sys_tasks where status="running"');
/** CLOSE DB CONNECTION */
$db->close();

$_tasks_number = count($_tasks);
$_type         = '';


if($_tasks_number > 0){
    
    $_type = $_tasks[0]['controller'];
    $_type = $_type == 'print' ?  'create' : $_type;
    
    
}

$_response_items['tasks_number'] = $_tasks_number;  
$_response_items['type']         = $_type;

     
header('Content-Type: application/json');
echo minify(json_encode($_response_items)); 




















?>