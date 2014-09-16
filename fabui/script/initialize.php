<?php
require_once '/var/www/fabui/script/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

/** INITIALIZE  */


/** WAIT UNTIL MYSQL SERVER START */
while(strpos(shell_exec('sudo  /etc/init.d/mysql status'), 'Server version') === false ){
	sleep(1);
}


/** LOAD DB */
$db = new Database();

/** GET RUNNING TASKS FROM DB  */ 
$_tasks = $db->query('select * from sys_tasks where status = "running" or status is null');

foreach($_tasks as $_task){
	
	$_data_update['status'] = 'removed';
    $db->update('sys_tasks', array('column' => 'id', 'value' => $_task['id'], 'sign' => '='), $_data_update);
}

$db->close();

?>




