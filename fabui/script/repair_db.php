<?php 
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';

/** LOAD DB */
$db = new Database();
$query = "update sys_tasks set sys_tasks.status = 'stopped' where status ='running' or status is null or status = '' ";
$db->query($query);
$db->close();

?>