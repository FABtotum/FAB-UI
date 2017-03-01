<?php

include_once ('config/config.php');

function check_install() {

	$_install = file_get_contents(INSTALL_FILE, FILE_USE_INCLUDE_PATH) == 1 ? true : false;

	if ($_install) {
		header("Location: /recovery/install");
	} else {
		header("Location: /fabui");
	}

}

function set_install($val) {

	file_put_contents(INSTALL_FILE, $val);

}

function exec_sql_file() {

	$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if (!$link) {
		die("MySQL Connection error");
	}

	$database_name = 'databasename';
	mysql_select_db(DB_DATABASE, $link) or die("Wrong MySQL Database");

	// read the sql file

	$f = fopen(SQL_INSTALL_DB, "r+");
	$sqlFile = fread($f, filesize(SQL_INSTALL_DB));

	$sqlArray = explode(';', $sqlFile);

	foreach ($sqlArray as $stmt) {
		if (strlen($stmt) > 3 && substr(ltrim($stmt), 0, 2) != '/*') {

			echo $stmt . '<br>';

			$result = mysql_query($stmt);
			if (!$result) {
				$sqlErrorCode = mysql_errno();
				$sqlErrorText = mysql_error();
				$sqlStmt = $stmt;
				break;
			}
		}
	}
	if ($sqlErrorCode == 0) {
		echo "Script is executed succesfully!";
	} else {
		echo "An error occured during installation!<br/>";
		echo "Error code: $sqlErrorCode<br/>";
		echo "Error text: $sqlErrorText<br/>";
		echo "Statement:<br/> $sqlStmt<br/>";
	}

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

/**
 * Set Wlan
 */
function setWifi($ssid, $password, $type = "WPA") {

	$networkConfiguration = networkConfiguration();
	setNetworkConfiguration($networkConfiguration['eth'], array('ssid' => $ssid, 'password' => $password, 'type' => $type));

	$response = shell_exec("sudo service networking reload");

	if (strpos($response, 'PING') !== false || strpos($response, 'errors') !== false) {
		return false;
	} else {
		return true;
	}

}

/**
 * Return network configuration - ETHERNET AND WLAN
 *
 */
function networkConfiguration() {

	$interfaces = file_get_contents('/etc/network/interfaces');

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

	

	$interfaces_file = '/etc/network/interfaces';

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
 * Set Ethernet static IP address
 */
function setEthIP($ip) {

	$ip = '169.254.1.' . $ip;
	$networkConfiguration = networkConfiguration();

	setNetworkConfiguration($ip, $networkConfiguration['wifi']);

	$response = shell_exec("sudo service networking reload");
}

function phpinfo_array() {
	ob_start();
	phpinfo();
	$info_arr = array();
	$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
	$cat = "General";
	foreach ($info_lines as $line) {
		// new cat?
		preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
		if (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
			$info_arr[$cat][$val[1]] = $val[2];
		} elseif (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
			$info_arr[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
		}
	}
	return $info_arr;
}

function is_internet_avaiable() {

	return !$sock = @fsockopen('www.google.com', 80, $num, $error, 5) ? false : true;
}



function marlin_get_remote_version(){
    

	$_remote_url = 'http://update.fabtotum.com/MARLIN/version.txt';
    
    $ch = curl_init($_remote_url);
	
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$_version = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
    
	return $_version;

}

?>