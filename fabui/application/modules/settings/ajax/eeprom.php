<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

$action = $_POST['action'];






if($action == 'save'){
	
	$id = $_POST['id'];
	$name = $_POST['name'];
	$description = $_POST['description'];
	$eeprom = $_POST['eeprom'];
	
	
	
	print_r($eeprom);
	
	
}










/*

$action = $_POST['action'];

$eeprom = $_POST['eeprom'];

if ($action == 'save') {

	if (!file_exists(FABUI_PATH . 'config/eeprom_custom.json')) {
		write_file(FABUI_PATH . 'config/eeprom_custom.json', '', 'w');

	}

	file_put_contents(FABUI_PATH . 'config/eeprom_custom.json', json_encode($eeprom));
	$message = 'EEPROM saved';
}



if($action == 'restore'){
	
	
	if (!file_exists(FABUI_PATH . 'config/eeprom_custom.json')) {
		write_file(FABUI_PATH . 'config/eeprom_custom.json', '', 'w');
	}
	
	$eeprom_default = json_decode(file_get_contents(FABUI_PATH . 'config/eeprom_default.json'), TRUE);
	
	file_put_contents(FABUI_PATH . 'config/eeprom_custom.json', json_encode($eeprom_default));
	
	
	$default_html = '';
	$count = 0;
	
	foreach($eeprom_default as $line){
	
		$default_html .= '<section class="eeprom_comamnd">';
		$default_html .= '<label class="label comment_'.$count.'">'.$line['comment'].'</label>';
		$default_html .= '<label class="input"><input type="text" name="command_'.$count.'" id="command_'.$count.'" value="'.$line['command'].'"></label>';
		$default_html .= '</section>';	
		
	}
	
	$response['html'] = $default_html;
	$message = 'Overrides restored';
	
}

include '/var/www/fabui/script/boot.php';

sleep(1);


$response['response'] = true;
$response['message'] = $message;

echo json_encode($response);
?>