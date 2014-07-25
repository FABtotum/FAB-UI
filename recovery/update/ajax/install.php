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
	
	//rename("/var/www/update/temp/myfabtotum/", "/var/www/update/temp/myfabtotum_new/");
	$_command_rename = 'sudo mv /var/www/recovery/update/temp/myfabtotum/ /var/www/myfabtotum_new/';
	shell_exec($_command_rename);
	
	$_command_rename_old_myfab = 'sudo mv /var/www/myfabtotum/ /var/www/myfabtotum_old/';
	shell_exec($_command_rename_old_myfab);
	
	$_command_rename_new_myfab = 'sudo mv /var/www/myfabtotum_new/ /var/www/myfabtotum/';
	shell_exec($_command_rename_new_myfab);
    
    $_command_symbolic_link = 'ln -s /var/www/upload/ /var/www/myfabtotum/upload';
    shell_exec($_command_symbolic_link);
	
	
}



function install_marlin($file){
	
	$_command = 'sudo /usr/bin/avrdude -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0  \ -U flash:w:'.$file.':i > /var/www/recovery/update/temp/install.log';
	shell_exec($_command);
	
}




?>