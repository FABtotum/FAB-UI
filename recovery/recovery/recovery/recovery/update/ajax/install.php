<?php 
require_once("/var/www/recovery/update/inc/init.php");

$_file = $_POST['file'];
$_type = $_POST['type'];



switch($_type){


	case 'myfab':
		install_myfab();
		break;

	case 'marlin':
		install_marlin($_file);
		break;

}


echo json_encode(array("ok"));




function install_myfab(){
	
	//rename della nuova cartella
	
	//rename("/var/www/update/temp/fabui/", "/var/www/update/temp/fabui_new/");
	$_command_rename = 'sudo mv /var/www/recovery/update/temp/fabui/ /var/www/fabui_new/';
	shell_exec($_command_rename);
	
	$_command_rename_old_myfab = 'sudo mv /var/www/fabui/ /var/www/fabui_old/';
	shell_exec($_command_rename_old_myfab);
	
	$_command_rename_new_myfab = 'sudo mv /var/www/fabui_new/ /var/www/fabui/';
	shell_exec($_command_rename_new_myfab);
    
    $_command_symbolic_link = 'ln -s /var/www/upload/ /var/www/fabui/upload';
    shell_exec($_command_symbolic_link);
	
	
}



function install_marlin($file){
	
	$_command = 'sudo /usr/bin/avrdude -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0  \ -U flash:w:'.$file.':i > /var/www/recovery/update/temp/install.log';
	shell_exec($_command);
	
}




?>