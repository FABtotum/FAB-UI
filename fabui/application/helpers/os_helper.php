<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('installed_plugins')) {

	function exist_process($pid) {

		$cmd = 'sudo ps ' . $pid;

		exec($cmd, $output, $result);

		// check the number of lines that were returned
		if (count($output) >= 2) {

			// the process is still alive
			return true;
		}

		// the process is dead
		return false;

	}

}

/**
 *
 * Search a string in a file using GREP
 * return TRUE if the string is present
 *
 */
function search($string, $file) {

	$_command = 'grep ' . $string . ' ' . $file;

	$_output = shell_exec($_command);

	return strlen($_output) > 0 ? true : false;

}

/**
 *
 * SCAN WIFI NETWORKS
 *
 */
function scan_wlan() {

	$_wlan_list = array();

	$_scan_result = shell_exec("sudo iwlist wlan0 scan");

	$_wlan_device = array();

	$_scan_result = explode("\n", $_scan_result);

	$device = $cell = "";

	foreach ($_scan_result as $zeile) {

		if (substr($zeile, 0, 1) != ' ') {
			$device = substr($zeile, 0, strpos($zeile, ' '));
		} else {

			$zeile = trim($zeile);

			if (substr($zeile, 0, 5) == 'Cell ') {
				$cell = (int)substr($zeile, 5, 2);
				$_wlan_device[$device][$cell] = array();
				$doppelp_pos = strpos($zeile, ':');
				$_wlan_device[$device][$cell]['address'] = trim(substr($zeile, $doppelp_pos + 1));
			} elseif (substr($zeile, 0, 8) == 'Quality=') {
				$first_eq_pos = strpos($zeile, '=');
				$last_eq_pos = strrpos($zeile, '=');
				$slash_pos = strpos($zeile, '/') - $first_eq_pos;
				$_wlan_device[$device][$cell]['quality'] = trim(substr($zeile, $first_eq_pos + 1, $slash_pos - 1));
				$_wlan_device[$device][$cell]['signal_level'] = str_replace('/100', '', trim(substr($zeile, $last_eq_pos + 1)));
			} else {
				$doppelp_pos = strpos($zeile, ':');
				$feld = trim(substr($zeile, 0, $doppelp_pos));
				if (!empty($_wlan_device[$device][$cell][strtolower($feld)]))
					$_wlan_device[$device][$cell][strtolower($feld)] .= "\n";
				// Leer- und "-Zeichen rausschmeissen - ESSID steht immer in ""
				/*
				echo $device.PHP_EOL;
				echo $cell.PHP_EOL;
				echo $feld.PHP_EOL;
				echo "--".PHP_EOL;
				*/
				
				@$_wlan_device[$device][$cell][strtolower($feld)] .= trim(str_replace('"', '', substr($zeile, $doppelp_pos + 1)));
			}

		}
	}
	
	

	if (isset($_wlan_device['wlan0'])) {

		foreach ($_wlan_device['wlan0'] as $wlan) {
			array_push($_wlan_list, $wlan);
		}

	}

	return $_wlan_list;

}

function lan() {

	$_ethernet_result = shell_exec("sudo ifconfig eth0");

	$interfaces = array();

	foreach (preg_split("/\n\n/", $_ethernet_result) as $int) {

		preg_match("/^([A-z]*\d)\s+Link\s+encap:([A-z]*)\s+HWaddr\s+([A-z0-9:]*).*" . "inet addr:([0-9.]+).*Bcast:([0-9.]+).*Mask:([0-9.]+).*" . "MTU:([0-9.]+).*Metric:([0-9.]+).*" . "RX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*frame:([0-9.]+).*" . "TX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*carrier:([0-9.]+).*" . "RX bytes:([0-9.]+).*\((.*)\).*TX bytes:([0-9.]+).*\((.*)\)" . "/ims", $int, $regex);

		if (!empty($regex)) {

			$interface = array();

			$interface['name'] = trim($regex[1]);
			$interface['type'] = trim($regex[2]);
			$interface['mac'] = trim($regex[3]);
			$interface['ip'] = trim($regex[4]);
			$interface['broadcast'] = trim($regex[5]);
			$interface['netmask'] = trim($regex[6]);
			$interface['mtu'] = trim($regex[7]);
			$interface['metric'] = trim($regex[8]);

			$interface['rx']['packets'] = (int)$regex[9];
			$interface['rx']['errors'] = (int)$regex[10];
			$interface['rx']['dropped'] = (int)$regex[11];
			$interface['rx']['overruns'] = (int)$regex[12];
			$interface['rx']['frame'] = (int)$regex[13];
			$interface['rx']['bytes'] = (int)$regex[19];
			$interface['rx']['hbytes'] = (int)$regex[20];

			$interface['tx']['packets'] = (int)$regex[14];
			$interface['tx']['errors'] = (int)$regex[15];
			$interface['tx']['dropped'] = (int)$regex[16];
			$interface['tx']['overruns'] = (int)$regex[17];
			$interface['tx']['carrier'] = (int)$regex[18];
			$interface['tx']['bytes'] = (int)$regex[21];
			$interface['tx']['hbytes'] = (int)$regex[22];

			$interfaces[] = $interface;
		}
	}

	return count($interfaces) == 1 ? $interfaces[0] : $interfaces;
}

function wlan() {

	$_ethernet_result = shell_exec("sudo ifconfig wlan0");

	$interfaces = array();

	foreach (preg_split("/\n\n/", $_ethernet_result) as $int) {

		preg_match("/^([A-z]*\d)\s+Link\s+encap:([A-z]*)\s+HWaddr\s+([A-z0-9:]*).*" . "inet addr:([0-9.]+).*Bcast:([0-9.]+).*Mask:([0-9.]+).*" . "MTU:([0-9.]+).*Metric:([0-9.]+).*" . "RX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*frame:([0-9.]+).*" . "TX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*carrier:([0-9.]+).*" . "RX bytes:([0-9.]+).*\((.*)\).*TX bytes:([0-9.]+).*\((.*)\)" . "/ims", $int, $regex);

		if (!empty($regex)) {

			$interface = array();

			$interface['name'] = trim($regex[1]);
			$interface['type'] = trim($regex[2]);
			$interface['mac'] = trim($regex[3]);
			$interface['ip'] = trim($regex[4]);
			$interface['broadcast'] = trim($regex[5]);
			$interface['netmask'] = trim($regex[6]);
			$interface['mtu'] = trim($regex[7]);
			$interface['metric'] = trim($regex[8]);

			$interface['rx']['packets'] = (int)$regex[9];
			$interface['rx']['errors'] = (int)$regex[10];
			$interface['rx']['dropped'] = (int)$regex[11];
			$interface['rx']['overruns'] = (int)$regex[12];
			$interface['rx']['frame'] = (int)$regex[13];
			$interface['rx']['bytes'] = (int)$regex[19];
			$interface['rx']['hbytes'] = (int)$regex[20];

			$interface['tx']['packets'] = (int)$regex[14];
			$interface['tx']['errors'] = (int)$regex[15];
			$interface['tx']['dropped'] = (int)$regex[16];
			$interface['tx']['overruns'] = (int)$regex[17];
			$interface['tx']['carrier'] = (int)$regex[18];
			$interface['tx']['bytes'] = (int)$regex[21];
			$interface['tx']['hbytes'] = (int)$regex[22];

			$interfaces[] = $interface;
		}
	}

	return count($interfaces) == 1 ? $interfaces[0] : $interfaces;

}

/*
function current_wlan() {

	$network_interfaces = '/etc/network/interfaces';

	$wlan = array();

	if (search('allow-hotplug wlan0', $network_interfaces)) {

		$content = file_get_contents($network_interfaces);

		$wlan_string = strstr($content, 'allow-hotplug wlan0');

		$temp = explode(PHP_EOL, $wlan_string);

		foreach ($temp as $line) {

			if (strpos(ltrim($line), '-ssid') !== false) {
				$wlan['ssid'] = str_replace('"', '', str_replace('-ssid', '', strstr(ltrim($line), '-ssid')));
			}

			if (strpos(ltrim($line), '-psk') !== false) {
				$wlan['password'] = str_replace('"', '', str_replace('-psk', '', strstr(ltrim($line), '-psk')));
			}

		}

		return $wlan;

	}

	return false;

}
*/

/**
 * Return network configuration - ETHERNET AND WLAN
 * 
 */
function networkConfiguration() {
	
	
	$CI =& get_instance();
	$CI->config->load('fabtotum', TRUE);
	
	$interfaces = file_get_contents($CI->config->item('fabtotum_network_interfaces', 'fabtotum'));

	$wlan_section = strstr($interfaces, 'allow-hotplug wlan0');

	$temp = explode(PHP_EOL, $wlan_section);

	$wlan_ssid = '';
	$wlan_password = '';

	foreach ($temp as $line) {

		if (strpos(ltrim($line), '-ssid') !== false) {
			$wlan_ssid = trim(str_replace('"', '', str_replace('-ssid', '', strstr(ltrim($line), '-ssid'))));
		}

		if (strpos(ltrim($line), '-psk') !== false) {
			$wlan_password = trim(str_replace('"', '', str_replace('-psk', '', strstr(ltrim($line), '-psk'))));
		}
	}

	$interfaces = str_replace($wlan_section, '', $interfaces);

	$eth_section = strstr($interfaces, 'allow-hotplug eth0');

	$temp = explode(PHP_EOL, $eth_section);

	$address = '';

	foreach ($temp as $line) {

		if (strpos(ltrim($line), 'address') !== false) {
			$address = str_replace('"', '', str_replace('address', '', strstr(ltrim($line), 'address')));
		}

	}
	
	return array('eth' => trim($address), 'wifi'=>array('ssid'=>trim($wlan_ssid), 'password'=>trim($wlan_password)));

}



/**
 * Set Network Configuration
 */
function setNetworkConfiguration($eth, $wifi){
	
	
	
	$CI =& get_instance();
	$CI->config->load('fabtotum', TRUE);
	
	
	$interfaces_file = $CI->config->item('fabtotum_network_interfaces', 'fabtotum');
	
	$new_configuration =  'auto lo'.PHP_EOL;
	$new_configuration .= 'iface lo inet loopback'.PHP_EOL.PHP_EOL;
	$new_configuration .= 'allow-hotplug eth0'.PHP_EOL;
	$new_configuration .= '    auto eth0'.PHP_EOL;
	$new_configuration .= '    iface eth0 inet static'.PHP_EOL;
	$new_configuration .= '    address '.$eth.PHP_EOL;
	$new_configuration .= '    netmask 255.255.0.0'.PHP_EOL.PHP_EOL;
	$new_configuration .= 'allow-hotplug wlan0'.PHP_EOL;
	$new_configuration .= '    auto wlan0'.PHP_EOL;
	$new_configuration .= '    iface wlan0 inet dhcp'.PHP_EOL;
	$new_configuration .= '    wpa-ssid "'.$wifi['ssid'].'"'.PHP_EOL;
	$new_configuration .= '    wpa-psk "'.$wifi['password'].'"'.PHP_EOL;
	
	
	$backup_command = 'sudo cp /etc/network/interfaces '.$interfaces_file.'.sav';
	shell_exec($backup_command);
	
	shell_exec('sudo chmod 666 '.$interfaces_file);
	
	file_put_contents($interfaces_file, $new_configuration);
	
	shell_exec('sudo chmod 644 '.$interfaces_file);
	
	shell_exec('sudo /etc/init.d/networking restart');
	
	
}


/**
 * Set Ethernet static IP address
 */
function setEthIP($ip){
	
	$ip = '169.254.1.'.$ip;	 	
	$networkConfiguration = networkConfiguration();
	setNetworkConfiguration($ip, $networkConfiguration['wifi']);
}



/**
 * Set Wlan 
 */
function setWifi($ssid, $password){
	
	$networkConfiguration = networkConfiguration();
	setNetworkConfiguration($networkConfiguration['eth'], array('ssid' => $ssid, 'password'=>$password));
	
	shell_exec("sudo ifdown wlan0");
	sleep(3);
	shell_exec("sudo ifup wlan0"); 
	
}
