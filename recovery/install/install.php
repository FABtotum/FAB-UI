<?php
session_start();
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0) {

	//=========== CONFIG FILES
	include_once ('/var/www/lib/config.php');
	include_once ('/var/www/lib/database.php');
	//include_once ('/var/www/lib/serial.php');
	include_once ('/var/www/lib/utilities.php');

	$_first_name   = $_POST['first_name'];
	$_last_name    = $_POST['last_name'];
	$_email        = $_POST['email'];
	$_password     = $_POST['password'];
	
	//inizialitizzo database
	$_command = 'sudo mysql -u ' . DB_USERNAME . ' -p' . DB_PASSWORD . ' -h ' . DB_HOSTNAME . '  < ' . SQL_INSTALL_DB;
	shell_exec($_command);

	/** LOAD DB */
	$db = new Database();
	/** ADD USER */

	$_settings['theme-skin'] = 'smart-style-0';
	$_settings['avatar'] = '';
	$_settings['token'] = '';
	$_settings['lock-screen'] = 0;
	$_settings['layout'] = '';
	$_settings['end-print-email'] = true;
	$_user_data['first_name'] = $_first_name;
	$_user_data['last_name']  = $_last_name;
	$_user_data['email']      = $_email;
	$_user_data['password']   = md5($_password);
	$_user_data['settings']   = json_encode($_settings);

	/** ADD TASK RECORD TO DB */
	$id_user = $db -> insert('sys_user', $_user_data);
	
	$sysInfo = json_decode(shell_exec('sudo python '.PYTHON_PATH.'sysinfo.py'), true);
	
	/** UPDATE FW VERSION ON DB */
	$_data_update = array();
	$_data_update['value'] = $sysInfo['fw']['version'];
	$db -> update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'fw_version', 'sign' => '='), $_data_update);
	
	/** CLEAN UPLOAD DIRECTORY */
	shell_exec('sudo rm -rvf '.UPLOAD_PATH.'*'); //clean upload folder
	
	/** CLEAN TEMP DIRECTORY */
	clean_temp();

	/** DELETE AUTOINSTALL FILE */
	if (file_exists('/var/www/AUTOINSTALL')) {
		shell_exec('sudo rm /var/www/AUTOINSTALL');
	}
	
	/** CLEAN SESSION */
	foreach ($_SESSION as $key => $value) { 
		unset($_SESSION[$key]);
	}

	// ==== SAMPLES FILES
	if(file_exists(RECOVERY_PATH . 'install/samples')) {
		if(!file_exists(UPLOAD_PATH.'gcode')){
			mkdir(UPLOAD_PATH.'gcode', 0777);
		}
		foreach (glob(RECOVERY_PATH.'install/samples/*.tar.gz') as $zip) {
			if(file_exists($zip)){
				$folder = str_replace('.tar.gz', '', $zip);
				$temp = explode("/", $folder);
				$folder_name = end($temp);
				if(!file_exists($folder)) shell_exec('mkdir '.$folder);
				shell_exec('sudo tar -zxvf '.$zip.' -C '.$folder);
				if(file_exists($folder.'/install.php')){
					require_once $folder.'/install.php';
				}else if(file_exists($folder.'/'.$folder_name.'/install.php')){
					require_once $folder.'/'.$folder_name.'/install.php';
				}
				shell_exec('sudo rm -rvf '.$folder);
			}
		}
	} elseif (file_exists(RECOVERY_PATH . 'install/file')) {   
		
		$query_files = 'INSERT INTO `sys_files` (`id`, `file_name`, `file_type`, `file_path`, `full_path`, `raw_name`, `orig_name`, `client_name`, `file_ext`, `file_size`, `print_type`, `is_image`, `image_width`, `image_height`, `image_type`, `image_size_str`, `insert_date`, `update_date`, `note`, `attributes`) VALUES ';
		$query_files .= "(1, 'Marvin_KeyChain_FABtotum.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Marvin_KeyChain_FABtotum.gcode', 'Marvin Key Chain FABtotum', 'Marvin_KeyChain_FABtotum.gcode', 'Marvin_KeyChain_FABtotum.gcode', '.gcode', 2176020, 'additive', 0, 0, 0, 0, '', now(), now(), 'Marvin sample', '{\"dimensions\": {\"x\" : \"109.444000244\", \"y\": \"116.483001709\", \"z\": \"50.0\"}, \"number_of_layers\" : 203, \"filament\": \"1276.94702148\", \"estimated_time\":\"0:25:07\" }'), ";
		$query_files .= "(2, 'bracelet.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/bracelet.gcode', 'Bracelet', 'bracelet.gcode', 'bracelet.gcode', '.gcode', 1467880, 'additive', 0, 0, 0, 0, '', now(),now(), 'Bracelet sample', '{\"dimensions\":{\"x\":\"101.062004089\",\"y\":\"101.062004089\",\"z\":\"9.80000019073\"},\"number_of_layers\":98,\"filament\":\"3229.01245117\",\"estimated_time\":\"1:11:07\"}');";	
		
		
		$db->query($query_files);
		
		$query_objects = 'INSERT INTO `sys_objects` (`id`, `user`, `obj_name`, `obj_description`, `date_insert`, `date_updated`, `private`) VALUES ';
		$query_objects .= "(1, 1, 'Samples', 'FABtotum samples', now(), NULL, 0); ";
		
		$db->query($query_objects);
		
		$query_obj_files = "INSERT INTO `sys_obj_files` (`id`, `id_obj`, `id_file`) VALUES ";
		$query_obj_files .= "(1, 1, 1),";
		$query_obj_files .= "(2, 1, 2);";
		
		$db->query($query_obj_files);

		//MOVE DEFAULT FILES TO FOLDERS
		 shell_exec('sudo cp /var/www/recovery/install/file/Marvin_KeyChain_FABtotum.gcode ' . UPLOAD_PATH . 'gcode/Marvin_KeyChain_FABtotum.gcode');
		 shell_exec('sudo cp /var/www/recovery/install/file/bracelet.gcode ' . UPLOAD_PATH . 'gcode/bracelet.gcode');
		 shell_exec('sudo chmod 777 ' . UPLOAD_PATH . 'gcode/Marvin_KeyChain_FABtotum.gcode');
		 shell_exec('sudo chmod 777 ' . UPLOAD_PATH . 'gcode/bracelet.gcode'); 
	}

	$db -> close();	

	$network = false;
	$response_items['installed'] = true;
	$response_items['network'] = $network;
	echo json_encode($response_items);

} else {
	echo "Access denied";
}
?>
