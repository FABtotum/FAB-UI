<?php

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
	
	if(!isWlanUp()){
		wlanUp();
	}
	
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

				@$_wlan_device[$device][$cell][strtolower($feld)] .= trim(str_replace('"', '', substr($zeile, $doppelp_pos + 1)));
			}

		}
	}

	if (isset($_wlan_device['wlan0'])) {

		foreach ($_wlan_device['wlan0'] as $wlan) {

			$wlan['type'] = 'OPEN';

			if (isset($wlan['ie'])) {

				if (strpos(isset($wlan['ie']), 'WPA2') === false) {
					$wlan['type'] = 'WPA2';
				} else if (strpos(isset($wlan['ie']), 'WPA') === false) {
					$wlan['type'] = 'WPA';
				} else if (strpos(isset($wlan['ie']), 'WEP') === false) {
					$wlan['type'] = 'WEP';
				}

			}

			array_push($_wlan_list, $wlan);
		}

	}
	
	return $_wlan_list;

}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('scanWlan'))
{
	/**
	 * @param string $interface wlan interface name
	 * @return array list of discovered wifi's nets
	 */
	function scanWlan($interface = 'wlan0')
	{
		$result = shell_exec('sudo python /var/www/fabui/python/scan_wifi.py '.$interface);
		$nets = json_decode( $result, true);
		return $nets;
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('getFromRegEx'))
{
	/**
	 *
	 */
	function getFromRegEx($regEx, $string)
	{
		preg_match($regEx, $string, $tempResult);
		return isset($tempResult[1]) ? $tempResult[1] : '';
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('decodeWifiSignal'))
{
	/**
	 *
	 */
	function decodeWifiSignal($value)
	{
		if (strpos($value, 'dBm') !== false) {
    		$value = abs(intval(trim(str_replace('dBm', '', $value))));
    		if($value < 50){
    			return 100;
    		}elseif($value > 50 && $value < 60){
    			return 75;
    		}elseif($value > 60 && $value < 70){
    			return 50;
    		}else{
    			return 25;
    		}
		}else{
			return $value;
		}
	}
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

/**
 * Return network configuration - ETHERNET AND WLAN
 *
 */
function networkConfiguration() {
	
	
	if(function_exists('get_instance')){
		$CI =& get_instance();
		$CI -> config -> load('fabtotum', TRUE);
		$interfaces = file_get_contents($CI -> config -> item('fabtotum_network_interfaces', 'fabtotum'));
	}else{
		require_once '/var/www/lib/config.php';
		$interfaces = file_get_contents(INTERFACES_FILE);
	}
	
	$wlan_section = strstr($interfaces, 'allow-hotplug wlan0');

	$temp = explode(PHP_EOL, $wlan_section);

	$wlan_ssid = '';
	$wlan_password = '';

	$wifi_type = 'OPEN';

	foreach ($temp as $line) {

		if (strpos(ltrim($line), '-ssid') !== false) {
			$wlan_ssid = trim(str_replace('"', '', str_replace('-ssid', '', strstr(ltrim($line), '-ssid'))));
			$wifi_type = 'WPA2';
		}

		if (strpos(ltrim($line), '-psk') !== false) {
			$wlan_password = trim(str_replace('"', '', str_replace('-psk', '', strstr(ltrim($line), '-psk'))));
			$wifi_type = 'WPA2';
		}

		//======================================================================================================

		if (strpos(ltrim($line), '-essid') !== false) {
			$wlan_ssid = trim(str_replace('"', '', str_replace('-essid', '', strstr(ltrim($line), '-essid'))));
		}

		if (strpos(ltrim($line), '-key') !== false) {
			$wlan_password = trim(str_replace('"', '', str_replace('-key', '', strstr(ltrim($line), '-key'))));
			$wifi_type = 'WEP';
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

	return array('eth' => trim($address), 'wifi' => array('ssid' => trim($wlan_ssid), 'password' => trim($wlan_password), 'type' => $wifi_type));

}

/**
 * Set Network Configuration
 */
function setNetworkConfiguration($eth, $wifi) {

	if(function_exists('get_instance')){
		$CI =& get_instance();
		$CI -> config -> load('fabtotum', TRUE);
		$interfaces_file = $CI -> config -> item('fabtotum_network_interfaces', 'fabtotum');
	}else{
		require_once '/var/www/lib/config.php';
		$interfaces_file = INTERFACES_FILE;
	}
	

	
	
	$new_configuration = 'auto lo' . PHP_EOL;
	$new_configuration .= 'iface lo inet loopback' . PHP_EOL . PHP_EOL;
	$new_configuration .= 'allow-hotplug eth0' . PHP_EOL;
	$new_configuration .= '    auto eth0' . PHP_EOL;
	$new_configuration .= '    iface eth0 inet static' . PHP_EOL;
	$new_configuration .= '    address ' . $eth . PHP_EOL;
	$new_configuration .= '    netmask 255.255.0.0' . PHP_EOL . PHP_EOL;
	$new_configuration .= 'allow-hotplug wlan0' . PHP_EOL;
	$new_configuration .= '    auto wlan0' . PHP_EOL;
	$new_configuration .= '    iface wlan0 inet dhcp' . PHP_EOL;

	switch($wifi['type']) {

		case 'OPEN' :
			$new_configuration .= '    wireless-essid ' . $wifi['ssid'] . '' . PHP_EOL;
			$new_configuration .= '    wireless-mode managed' . PHP_EOL;
			break;
		case 'WEP' :
			$new_configuration .= '    wireless-essid ' . $wifi['ssid'] . '' . PHP_EOL;
			$new_configuration .= '    wireless-key ' . $wifi['password'] . '' . PHP_EOL;
			break;
		case 'WPA' :
		case 'WPA2' :
			$new_configuration .= '    wpa-ssid "' . $wifi['ssid'] . '"' . PHP_EOL;
			$new_configuration .= '    wpa-psk "' . $wifi['password'] . '"' . PHP_EOL;
			break;
	}

	$backup_command = 'sudo cp /etc/network/interfaces ' . $interfaces_file . '.sav';
	shell_exec($backup_command);

	shell_exec('sudo chmod 666 ' . $interfaces_file);

	file_put_contents($interfaces_file, $new_configuration);

	shell_exec('sudo chmod 644 ' . $interfaces_file); 

	//shell_exec('sudo /etc/init.d/networking restart');

}

/**
 * Set Ethernet static IP address
 */
function setEthIP($ip) {
	setEthernet($ip);
}


function setEthernet($ip){
	
	 $response = shell_exec('sudo sh /var/www/fabui/script/bash/set_ethernet.sh "'.$ip.'" ');
	 return $response;
	 
}


/**
 * Set Wlan
 */
function setWifi($ssid, $password, $type = "WPA") {
	
	shell_exec('sudo bash /var/www/fabui/script/bash/set_wifi.sh "'.$ssid.'" "'.$password.'"');
	$info = wlan_info();
	return $info['ssid'] == $ssid;	
}

/**
 * GET PIDs process by command
 * @param $string
 * @return array
 */
function get_pids($command) {

	$pids = array();

	$exec_response = shell_exec('sudo ps ax | grep ' . $command);

	$temp = explode(PHP_EOL, $exec_response);

	foreach ($temp as $line) {
		$t = explode(' ', trim($line));

		$pid = trim($t[0]);

		if ($pid != '') {
			array_push($pids, $t[0]);
		}
	}
	return $pids;
}

/**
 *  KILL process by PID
 * @param $int
 * @return void
 */
function kill_process($pid) {

	$command = 'sudo kill -9 ';

	if (is_array($pid)) {
		$command .= implode(" ", $pid);
	} else {
		$command .= $pid;
	}

	shell_exec($command);
}

/**
 *  Pretty baud
 * @param $int
 * @return string
 */
function pretty_baud($baud) {
	$baud = intval($baud);
	$ret = "unknown";
	if ($baud > 1000000) {
		$baud = $baud / 1000000;
		$ret = "$baud Mb/s";
	} else if ($baud > 1000) {
		$baud = $baud / 1000;
		$ret = "$baud Kb/s";
	} else {
		$ret = "$baud b/s";
	}
	return $ret;
}



/**
 * 
 * 
 */
 function wlan_info(){
 	
	exec('sudo ifconfig wlan0',$info);
	exec('sudo iwconfig wlan0',$info);
	
	$strWlan0 = implode(" ",$info);
	
	$strWlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
	preg_match('/HWaddr ([0-9a-f:]+)/i',$strWlan0,$result);
	$strHWAddress = isset($result[1]) ? $result[1] : '' ;
	preg_match('/inet addr:([0-9.]+)/i',$strWlan0,$result);
	$strIPAddress = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Mask:([0-9.]+)/i',$strWlan0,$result);
	$strNetMask = isset($result[1]) ? $result[1] : '' ;
	preg_match('/RX packets:(\d+)/',$strWlan0,$result);
	$strRxPackets = isset($result[1]) ? $result[1] : '' ;
	preg_match('/TX packets:(\d+)/',$strWlan0,$result);
	$strTxPackets = isset($result[1]) ? $result[1] : '' ;
	preg_match('/RX Bytes:(\d+ \(\d+.\d+ MiB\))/i',$strWlan0,$result);
	$strRxBytes = isset($result[1]) ? $result[1] : '';
	preg_match('/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strWlan0,$result);
	$strTxBytes = isset($result[1]) ? $result[1] : '' ;
	preg_match('/ESSID:\"((?:(?![\n\s]).)*)\"/i',$strWlan0,$result);
	$strSSID = isset($result[1]) ? str_replace('"','',$result[1]) : '';
	preg_match('/Access Point: ([0-9a-f:]+)/i',$strWlan0,$result);
	$strBSSID = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Bit Rate:([0-9]+.[0-9]+\s[a-z]+\/[a-z]+)/i',$strWlan0,$result);
	$strBitrate = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Link Quality=([0-9]+\/[0-9]+)/i',$strWlan0,$result);
	$strLinkQuality = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Signal Level=([0-9]+\/[0-9]+)/i',$strWlan0,$result);
	$strSignalLevel = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Power Management:([a-zA-Z]+)/i ',$strWlan0,$result);
	$powerManagement = isset($result[1]) ? $result[1] : '' ;
	preg_match('/Frequency:([0-9]+.[0-9]+\s[a-z]+)/i ',$strWlan0,$result);
	$frequency = isset($result[1]) ? $result[1] : '' ;
	preg_match('/IEEE ([0-9]+.[0-9]+[a-z]+)/i ',$strWlan0,$result);
	$ieee = isset($result[1]) ? $result[1] : '' ;
	
	return array(
		'mac_address' => $strHWAddress,
		'ip_address' => $strIPAddress,
		'subnet_mask' => $strNetMask,
		'received_packets' => $strRxPackets,
		'transferred_packets' => $strTxPackets,
		'received_bytes' => $strRxBytes,
		'transferred_bytes' => $strTxBytes,
		'ssid' => $strSSID,
		'ap_mac_address' => $strBSSID,
		'bitrate' => $strBitrate,
		'link_quality' => $strLinkQuality,
		'signal_level' => $strSignalLevel,
		'power_management' => $powerManagement,
		'frequency' => $frequency,
		'ieee' => $ieee
	);
	
 }

function eth_info(){
		
	exec('sudo ifconfig eth0', $info);
	$info = implode(" ",$info);
	$info = preg_replace('/\s\s+/', ' ', $info);
	
	preg_match('/inet addr:([0-9]+.[0-9]+.[0-9]+.[0-9]+)/i',$info,$result);
	$inet_address = isset($result[1]) ? $result[1] : '';
	
	preg_match('/Bcast:([0-9]+.[0-9]+.[0-9]+.[0-9]+)/i',$info,$result);
	$broadcast = isset($result[1]) ? $result[1] : '';
	
	preg_match('/HWaddr ([0-9a-f:]+)/i',$info,$result);
	$mac_address = isset($result[1]) ? $result[1] : '';
	
	preg_match('/RX Bytes:(\d+ \(\d+.\d+ MiB\))/i',$info,$result);
	$received_bytes = isset($result[1]) ? $result[1] : '';
	
	preg_match('/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$info,$result);
	$transferred_bytes = isset($result[1]) ? $result[1] : '';
		
	return array(
		'inet_address' => $inet_address,
		'broadcast' => $broadcast,
		'mac_address' => $mac_address,
		'received_bytes' => $received_bytes,
		'transferred_bytes' => $transferred_bytes
	);
}

function disconnectWifi($interface = 'wlan0'){
	
	shell_exec('sudo bash /var/www/fabui/script/bash/disconnect_wifi.sh '.$interface);
	$info = wlan_info();
	return $info['ssid'] == '';
}


function set_hostname($hostname, $description){
	return shell_exec('sudo bash /var/www/fabui/script/bash/set_hostname.sh "'.$hostname.'" "'.$description.'"');
}

function scan_wlan_networks(){
	
	exec('sudo wpa_cli scan',$return);
	sleep(2);
	exec('sudo wpa_cli scan_results',$return);
	
	for($shift = 0; $shift < 4; $shift++ ) {
			array_shift($return);
	}
	
	foreach($return as $network) {
			$arrNetwork = preg_split("/[\t]+/",$network);
		
			print_r($arrNetwork);
			
			//echo '<input type="button" value="Connect to This network" onClick="AddScanned(\''.$ssid.'\')" />' . $ssid . " on channel " . $channel . " with " . $signal . "(".ConvertToSecurity($security)." Security)<br />";

		}
	
	exit();
}
/*  */
function avahi_service_name(){
	if(file_exists('/etc/avahi/services/fabtotum.service')){
		$xml_service = simplexml_load_file('/etc/avahi/services/fabtotum.service','SimpleXMLElement', LIBXML_NOCDATA);
		return trim(str_replace('(%h)', '', $xml_service->name));
	}else{
		return 'Fabtotum Personal Fabricator';
	}
}
/**
 * 
*/
function isWlanUp()
{
	$ifconfigOutput = shell_exec('sudo ifconfig');
	$re = '/(wlan0|wlan1)/';
	return preg_match($re, $ifconfigOutput);
}
/**
 * 
 * 
 */
function wlanUp(){
	shell_exec('sudo ifup wlan0 --no-mappings --no-loopback --no-scripts --force --ignore-errors & > /dev/null');
}