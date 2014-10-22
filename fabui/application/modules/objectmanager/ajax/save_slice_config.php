<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';


//GET DATA FROM POST
$file        = $_POST['file'];
$file_content = $_POST['value'];



echo write_file($file, $file_content, 'w') ? 1 : 0;







?>