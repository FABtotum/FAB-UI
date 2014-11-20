<?php
@session_start();
require_once '/var/www/fabui/ajax/config.php';



//DELETE WIZARD FILE



if(file_exists(WIZARD_FILE)){
	
	shell_exec('sudo rm -rf '.WIZARD_FILE);
	
	$_SESSION['wizard_completed'] = true;
	
}


echo true;

?>