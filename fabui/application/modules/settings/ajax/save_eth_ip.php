<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/helpers/os_helper.php';

$last_ip_num = $_POST['ip_num'];

echo setEthIP($last_ip_num);

