<?php
session_start();
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0){
	
    //=========== CONFIG FILES
    include_once ('/var/www/lib/config.php');
    include_once ('/var/www/lib/database.php');
	include_once ('/var/www/lib/serial.php');
    include_once ('/var/www/lib/utilities.php');
	
	
    $_first_name   = $_POST['first_name'];
    $_last_name    = $_POST['last_name'];
    $_email        = $_POST['email'];
    $_password     = $_POST['password'];
	$_net          = $_POST['net'];
	$_net_password = $_POST['net_password'];
	$_ip_address   = $_POST['ip_address'];
    
    /** CHECK IF IS WIFI CONNCECTION */
    $_temp    = explode('-', $_net);
	$_is_wifi = $_temp[0] == 'wifi' ? true : false;
	
	if($_is_wifi){
		
		$_net = str_replace('wifi-', '', $_net);
		shell_exec("sudo python ".PYTHON_PATH."connection_setup.py -n".$_net." -p".$_net_password);
		
		shell_exec("sudo ifdown wlan0");
		sleep(3);
		shell_exec("sudo ifup wlan0");
		
	}
    
	

    //inizialitizzo database
    $_command = 'sudo mysql -u '.DB_USERNAME.' -p'.DB_PASSWORD.' -h '.DB_HOSTNAME.'  < '.SQL_INSTALL_DB;
	
	
	
    shell_exec($_command);
	
	
    /** LOAD DB */
	$db = new Database();
	/** ADD USER */
	
	$_settings['theme-skin'] = 'smart-style-0';
	$_settings['avatar']     = '';
	$_settings['token']      = '';
	$_settings['lock-screen'] = 0;
	$_settings['layout']      = '';  
	
	$_user_data['first_name'] = $_first_name;
	$_user_data['last_name']  = $_last_name;
	$_user_data['email']      = $_email;
	$_user_data['password']   = md5($_password);
	$_user_data['settings']   = json_encode($_settings);
	
	/** ADD TASK RECORD TO DB */ 
	$id_user = $db->insert('sys_user', $_user_data);
	
	$wlan     = wlan();
	$wlan_ip = isset($wlan['ip']) ? $wlan['ip'] : '';
	/** UPDATE WIFI */
	$_data_update = array();
	$_data_update['value'] = json_encode(array('ssid' => $_net, 'password' => $_net_password, 'ip' =>$wlan_ip));
	$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'wifi', 'sign' => '='), $_data_update);
	
	
	
	
	//=========== SERIAL CLASS - GET FW VERSION
	$serial = new Serial;
	$serial->deviceSet(PORT_NAME);
	$serial->confBaudRate(BOUD_RATE);
	$serial->confParity("none");
	$serial->confCharacterLength(8);
	$serial->confStopBits(1);
	$serial->deviceOpen();
	
	//firmware version
	$serial->sendMessage("M765".PHP_EOL);
	$fw_version_reply = $serial->readPort();
	$fw_version = '';
	
	//hardware version
	$serial->sendMessage('M763'.PHP_EOL);
	$hw_id_reply = $serial->readPort();
	$hw_id = '';
	
	$serial->deviceClose();
	
		
	if(strpos($fw_version_reply, 'V') !== false){
		$fw_version = trim(str_replace('V', '', $fw_version_reply));
		$fw_version = trim(str_replace('ok', '', $fw_version));
	}
	
	
	$hw_id = trim(str_replace('ok', '', $hw_id_reply));
	
	
	/** UPDATE FW VERSION ON DB */
	$_data_update = array();
	$_data_update['value'] = $fw_version;
	$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'fw_version', 'sign' => '='), $_data_update);
	
	
	$db->close();
	
	
	/** GET UNITS */
	$configs = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);
	
	$configs['hardware']['id'] = $hw_id;
	
	file_put_contents(FABUI_PATH.'config/config.json', json_encode($configs));
	
	
	
	/** CLEAN UPLOAD DIRECTORY */
	$upload_folders = directory_map(UPLOAD_PATH);

	foreach($upload_folders as $key => $val){
		shell_exec('sudo rm -f '.UPLOAD_PATH.$key.'/*');
	}

	
	/** CLEAN TEMP DIRECTORY */
	clean_temp();

    /** DELETE AUTOINSTALL FILE */
    if(file_exists('/var/www/AUTOINSTALL')){
    	shell_exec('sudo rm /var/www/AUTOINSTALL');
    }
    
	
	
	/** UPLOAD SIMBOLIC LINK */
	if(!is_link(FABUI_PATH.'upload')){
		shell_exec('sudo ln -s '.UPLOAD_PATH.' '.FABUI_PATH.'upload');
	}
	
	
	
	
	
	/** MOVE DEFAULT FILES TO FOLDERS */
	shell_exec('sudo cp /var/www/recovery/install/file/Marvin_KeyChain_FABtotum.gcode '.UPLOAD_PATH.'gcode/Marvin_KeyChain_FABtotum.gcode');
	shell_exec('sudo cp /var/www/recovery/install/file/bracelet.gcode '.UPLOAD_PATH.'gcode/bracelet.gcode');
	
	shell_exec('sudo chmod 777 '.UPLOAD_PATH.'gcode/Marvin_KeyChain_FABtotum.gcode');
	shell_exec('sudo chmod 777 '.UPLOAD_PATH.'gcode/bracelet.gcode');
	
	
	/** CLEAN SESSION */
	foreach($_SESSION as $key => $value){
		unset($_SESSION[$key]);
	}
	
	
	
	//set ip ethernet static address
	$actual_network_configuration = networkConfiguration();
	
	$network = false;
	
	if($actual_network_configuration['eth'] != '169.254.1.'.$_ip_address){
		setEthIP($_ip_address);
		$network = true;
	}
	
	
	$response_items['installed'] = true;
	$response_items['network'] = $network;
	
	echo json_encode($response_items);
 
}else{
    
    echo "Access denied";
    
}


?>