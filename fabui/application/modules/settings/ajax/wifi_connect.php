<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/helpers/os_helper.php';



$essid    = $_POST['essid'];
$password = $_POST['password'];
$type     = $_POST['type'];
$action   = $_POST['action'];

if($action == 'connect'){
	echo json_encode(array('response' => setWifi($essid, $password, $type)));
}else{
	echo json_encode(array('response' => disconnectWifi()));
}


