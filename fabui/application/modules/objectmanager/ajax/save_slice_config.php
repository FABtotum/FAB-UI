<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


//GET DATA FROM POST
$file        = $_POST['file'];
$file_content = $_POST['value'];



echo write_file($file, $file_content, 'w') ? 1 : 0;







?>