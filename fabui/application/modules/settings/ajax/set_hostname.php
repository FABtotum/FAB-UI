<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/helpers/os_helper.php';

$hostname = trim($_POST['hostname']);
$description = trim($_POST['name']);

echo set_hostname($hostname, $description);