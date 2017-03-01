<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/create_factory.php';
/** GET DATA FROM POST */
$action    = $_POST['action'];
$value     = $_POST['value'];
$pid       = $_POST['pid'];
$data_file = $_POST['data_file'];
$id_task   = $_POST['id_task']; 
$progress  = $_POST['progress'];
$attributes_file = $_POST["attributes_file"];
$type =  $_POST['type'];

$data["action"]          = $action;
$data["value"]           = $value;
$data["pid"]             = $pid;
$data["data_file"]       = $data_file;
$data["id_task"]         = $id_task;
$data["progress"]        = $progress;
$data['function']        = "operation";
$data['attributes_file'] = $attributes_file;
$data['type'] = $type;

$CreateFactory = new CreateFactory($data);
echo $CreateFactory->run();

?>