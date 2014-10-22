<?php

include_once ('/var/www/fabui/ajax/config.php');
include_once ('/var/www/fabui/ajax/lib/utilities.php');



$directory = '/var/www/temp/';
    
$files = directory_map($directory);



$files_to_take[] = 'picture.jpg';
$files_to_take[] = 'fab_ui_safety.json';

foreach($files as $file){
	
	
	if(!in_array($file, $files_to_take)){
		
		echo "Delete ".$file.PHP_EOL;
		unlink($directory.$file);
		
	}
	
	
}



?>