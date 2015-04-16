<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

$net      = $_POST['net'];
$password = $_POST['password'];

$response_items = array();

if($net !=  ''){
	
	/** CHECK IF IS WIFI CONNCECTION */
    $_temp    = explode('-', $net);
	$_is_wifi = $_temp[0] == 'wifi' ? true : false;
	
	if($_is_wifi){
		
		$net = str_replace('wifi-', '', $net);
		shell_exec("sudo python ".PYTHON_PATH."connection_setup.py -n".$net." -p".$password);
		
		shell_exec("sudo ifdown wlan0");
		sleep(3);
		shell_exec("sudo ifup wlan0");


		$wlan = wlan();
		$wlan_ip = isset($wlan['ip']) ? $wlan['ip'] : '';
		
		/** UPDATE WIFI DB  */
		/** LOAD DB */
		$db = new Database();
		/** UPDATE TASK */
		$_data_update = array();
		$_data_update['value'] = json_encode(array('ssid' => $net, 'password' => $password, 'ip' =>$wlan_ip));
		$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'wifi', 'sign' => '='), $_data_update);
		$db->close();
		
		$response_items['wlan_ip'] = $wlan_ip;

	}
	
	
}

echo json_encode($response_items);


?>