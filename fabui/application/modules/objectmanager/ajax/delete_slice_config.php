<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

//GET DATA FROM POST
$file        = $_POST['file'];


//LOAD DB
$db      = new Database();
$query   = 'select value from sys_configuration where sys_configuration.key = "slicer_presets" ';
$presets = $db->query($query);


$presets = json_decode($presets['value'], TRUE);



$temp = array();

foreach($presets as $config){
	
	if($config['file'] != $file){
		
		$temp[] = $config;
		
	}
	
}


$presets = $temp;
unlink($file);



$data_update['value'] = json_encode($temp);

$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'slicer_presets', 'sign' => '='), $data_update);
$db->close();

$html = '';

foreach($presets as $config){
	
	$html .= '<option value="'.$config['file'].'">'.$config['name'].' - '.$config['description'].'</option>';		
}
echo $html;


?>