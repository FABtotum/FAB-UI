<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';


//GET DATA FROM POST


$file        = $_POST['file'];
$name        = $_POST['name'];
$description = $_POST['description'];


//LOAD DB

$db      = new Database();
$query   = 'select value from sys_configuration where sys_configuration.key = "slicer_presets" ';
$presets = $db->query($query);


$presets = json_decode($presets['value'], TRUE);

$presets[] = array('name' => $name, 'file'=>$file, 'description'=>$description);

$data_update['value'] = json_encode($presets);

$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'slicer_presets', 'sign' => '='), $data_update);
$db->close();

$html = '';

foreach($presets as $config){
	
	$html .= '<option value="'.$config['file'].'">'.$config['name'].' - '.$config['description'].'</option>';
		
}


echo $html;


?>