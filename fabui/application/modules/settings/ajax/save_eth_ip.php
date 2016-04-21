<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/helpers/os_helper.php';

$ip= $_POST['ip'];

echo setEthIP($ip);

