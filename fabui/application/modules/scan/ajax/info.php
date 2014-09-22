<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';

$_task_id = $_POST['task_id'];

/** LOAD DB */
$db = new Database();

/** GET TASK FROM DB */
$_task = $db->query('select * from sys_tasks where id='.$_task_id);

//$_task = $_task[0];

header('Content-Type: application/json');
echo $_task['attributes'];
?>