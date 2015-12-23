<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/create_factory.php';
/** GET DATA FROM POST */
$_estimated_time = isset($_POST['estimated_time']) ? $_POST['estimated_time'] : array();
$_progress_steps = isset($_POST['progress_steps']) ? $_POST['progress_steps'] : array();
$_stats_file = isset($_POST['stats_file']) ? $_POST['stats_file'] : "";

$data["estimated_time"] = $_estimated_time;
$data["progress_steps"] = $_progress_steps;
$data["stats_file"]     = $_stats_file;
$data['function']       = "update";

$CreateFactory = new CreateFactory($data);
echo $CreateFactory -> run();
?>