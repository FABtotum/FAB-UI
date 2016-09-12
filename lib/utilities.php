<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';

function site_url($url) {
	return SITE_URL . $url;
}

function base_url() {

}

function host_name() {
	return 'http://' . $_SERVER['HTTP_HOST'] . '/';
}

/** */
function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE) {
	if (!$fp = @fopen($path, $mode)) {
		return FALSE;
	}

	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);

	return TRUE;
}

/** */
function print_type($file_path) {
	return strtolower(trim(shell_exec('sudo python ' . PYTHON_PATH . 'check_manufacturing.py "' . $file_path . '"')));
}

/** */
function minify($string) {

	$buffer = $string;

	$search = array('/\n/', // replace end of line by a space
	'/\>[^\S ]+/s', // strip whitespaces after tags, except space
	'/[^\S ]+\</s', // strip whitespaces before tags, except space
	'/(\s)+/s'	// shorten multiple whitespace sequences
	);

	$replace = array(' ', '>', '<', '\\1');

	return preg_replace($search, $replace, $buffer);
}

/** */
function roundsize($size) {

	$i = 0;

	$iec = array("B", "Kb", "Mb", "Gb", "Tb");

	while (($size / 1024) > 1) {
		$size = $size / 1024;
		$i++;
	}
	return (round($size, 2) . " " . $iec[$i]);
}

/** */
function get_file_extension($filename) {
	$x = explode('.', $filename);
	return '.' . end($x);
}

/** */
function get_name($full_path) {

	$x = explode('/', $full_path);
	return end($x);

}

/** */
function set_filename($path, $filename) {

	if (!file_exists($path . $filename)) {
		return $filename;
	}

	$ext = get_file_extension($filename);

	$filename = str_replace($ext, '', $filename);

	$new_filename = '';
	for ($i = 1; $i < 100; $i++) {
		if (!file_exists($path . $filename . $i . $ext)) {
			$new_filename = $filename . $i . $ext;
			break;
		}
	}

	if ($new_filename == '') {

		return FALSE;
	} else {
		return $new_filename;
	}
}

/**
 *
 */
function myfab_get_remote_version() {
	$_remote_version = file_get_contents(MYFAB_REMOTE_VERSION_URL);
	return $_remote_version;
}

/**
 *
 * @return unknown
 */
function myfab_get_local_version() {

	/** LOAD DB */
	$db = new Database();

	/** GET TASK FROM DB */
	$_version = $db -> query('select sys_configuration.value from sys_configuration where sys_configuration.key="fabui_version"');
	return $_version[0]['value'];
}

/**
 *
 */
function marlin_get_local_version() {

	/** LOAD DB */
	$db = new Database();
	/** GET TASK FROM DB */
	$_version = $db -> query('select sys_configuration.value from sys_configuration where sys_configuration.key="fw_version"');
	$db -> close();
	return $_version[0]['value'];

}

/**
 *
 */
function marlin_get_remote_version() {
	$_remote_version = file_get_contents(MARLIN_REMOTE_VERSION_URL);
	return $_remote_version;
}

/**
 *
 */
function is_internet_avaiable() {
	
	
	$url='http://www.google.com/';
	$ch=curl_init();
	$timeout=2;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	
	$result=curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	return $info['http_code'] > 0;
	//return !$sock = @fsockopen('www.google.com', 80, $num, $error, 2) ? false : true;
}

/**
 *
 */
function is_usb_inserted() {
	return file_exists(USB_SYSTEM_FILE);
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

function is_comment($line) {

	$line = trim($line);
	return $line[0] == ';' ? true : false;

}

function normalize_line($line) {

	$temp = explode(';', $line);
	return trim($temp[0]) . PHP_EOL;

}

/**
 * STOLEN FROM CI
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE) {
	if ($fp = @opendir($source_dir)) {
		$filedata = array();
		$new_depth = $directory_depth - 1;
		$source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		while (FALSE !== ($file = readdir($fp))) {
			// Remove '.', '..', and hidden files [optional]
			if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
				continue;
			}

			if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
				$filedata[$file] = directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
			} else {
				$filedata[] = $file;
			}
		}

		closedir($fp);
		return $filedata;
	}

	return FALSE;
}

function clean_temp() {

	$files = directory_map(TEMP_PATH);

	$files_to_take[] = 'picture.jpg';
	$files_to_take[] = 'fab_ui_safety.json';
	$files_to_take[] = 'instagram_feed.json';
	$files_to_take[] = 'instagram_hash.json';
	$files_to_take[] = 'blog.xml';
	$files_to_take[] = 'twitter.json';
	$files_to_take[] = 'faq.json';
	$files_to_take[] = 'macro_response';
	$files_to_take[] = 'macro_trace';
	$files_to_take[] = 'macro_status.json';
	$files_to_take[] = 'git_latest_release.json';
	$files_to_take[] = 'git_releases.json';

	foreach ($files as $file) {

		if (!in_array($file, $files_to_take)) {
			unlink(TEMP_PATH . $file);
		}

	}

}

/**
 * GET PIDs process by command
 * @param $string
 * @return array
 */
function get_pids($search) {

	if (!is_array($search)) {
		$search = array($search);
	}

	$shell_response = shell_exec("sudo ps -ef | grep -e " . implode(' -e ', $search)." | grep -v grep | awk '{print $2}'");

	return explode(PHP_EOL, trim($shell_response));
}

/**
 *  KILL process by PID
 * @param $int
 * @return void
 */
function kill_process($pid) {
	
	if(!is_array($pid)){
		$pid = array($pid);
	}
	
	shell_exec('sudo kill -s9 '.implode(' ', $pid));
}

/**
 * 
 * Kill all preocessess 
 */
function kill_process_by_name($search) {
	
	if (!is_array($search)) {
		$search = array($search);
	}
	shell_exec("sudo kill -s9 `ps -ef | grep -e " . implode(' -e ', $search) . " | grep -v grep | awk '{print $2}'` ");
}

/**
 * Return network configuration - ETHERNET AND WLAN
 *
 */
function networkConfiguration() {

	$interfaces = file_get_contents(NETWORK_INTERFACES);

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

	$interfaces_file = NETWORK_INTERFACES;

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

/**
 * Set Ethernet static IP address
 */
function setEthIP($ip) {

	$ip = '169.254.1.' . $ip;
	setEthernet($ip);
}

function setEthernet($ip) {

	$response = shell_exec('sh /var/www/fabui/script/bash/set_ethernet.sh "' . $ip . '" ');
	return $reponse;

}

/** CHECK IF A URL EXISTS */
function url_exist($url) {
	$headers = get_headers($url);
	return stripos($headers[0], "200 OK") ? true : false;
}

/**
 *
 *
 *
 */ 
function extract_zip($source, $destination){
	
	$zip = new ZipArchive;
	
	$res = $zip->open($source);
	
	if ($res === TRUE) {
	
		$zip->extractTo($destination);
		$zip->close();
		return true;
	} else {
		return false;
	}
	
}


function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

function create_default_config($except = ''){
		$dafault_config = array(
			'color'         => array('r'=>255, 'g'=>255, 'b'=>255),
			'safety'        => array('door'=>0, 'collision-warning'=>1),
			'switch'        => 0,
			'feeder'        => array('disengage-offset'=> 2, 'show' => true),
			'print'         => array('pre-heating' => array('extruder' => 150, 'bed' => 50)),
			'milling'       => array('layer-offset' => 12),
			//'e'             => 3048.1593,
			'a'             => 177.777778,
			'bothy'         => 'None',
			'bothz'         => 'None',
			'api'           => array('keys' => array()),
			'zprobe'        => array('disbale'=>0, 'zmax'=>206),
			'settings_type' => 'default',
			'hardware'      => array('head' => array('type' => 'hybrid', 'description'=>'Hybrid Head', 'max_temp'=>230))
		);
		
		write_file(FABUI_PATH . 'config/config.json', json_encode($dafault_config), 'w+');
		shell_exec('sudo chmod 777 '.FABUI_PATH . 'config/config.json');
		shell_exec('sudo chown www-data:www-data '.FABUI_PATH . 'config/config.json');	
     }


?>