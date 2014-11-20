<?php

$interfaces = file_get_contents('/etc/network/interfaces');



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

foreach($temp as $line){
	
	if (strpos(ltrim($line), 'address') !== false) {
		$address = str_replace('"', '', str_replace('address', '', strstr(ltrim($line), 'address')));
	}
	
}


$temp_address = explode('.', $address);




if(isset($_POST['end']) && $_POST['end'] != ""){
	
	$end = $_POST['end'];
	
	
	$new_configuration =  'auto lo'.PHP_EOL;
	$new_configuration .= 'iface lo inet loopback'.PHP_EOL.PHP_EOL;
	$new_configuration .= 'allow-hotplug eth0'.PHP_EOL;
	$new_configuration .= '    auto eth0'.PHP_EOL;
	$new_configuration .= '    iface eth0 inet static'.PHP_EOL;
	$new_configuration .= '    address 169.254.1.'.$end.PHP_EOL;
	$new_configuration .= '    netmask 255.255.0.0'.PHP_EOL.PHP_EOL;
	$new_configuration .= 'allow-hotplug wlan0'.PHP_EOL;
	$new_configuration .= '    auto wlan0'.PHP_EOL;
	$new_configuration .= '    iface wlan0 inet dhcp'.PHP_EOL;
	$new_configuration .= '    wpa-ssid "'.$wlan_ssid.'"'.PHP_EOL;
	$new_configuration .= '    wpa-psk "'.$wlan_password.'"'.PHP_EOL;
	
	
	
	$backup_command = 'sudo cp /etc/network/interfaces /etc/network/interfaces.sav';
	shell_exec($backup_command);
	
	
	shell_exec('sudo chmod 666 /etc/network/interfaces');
	
	
	file_put_contents('/etc/network/interfaces', $new_configuration);
	
	shell_exec('sudo chmod 644 /etc/network/interfaces');
	
	shell_exec('sudo /etc/init.d/networking restart');
	
	
}

?>
<html>
	<head></head>
	<body>
		<div>
			<h2>Set ETH static IP Address</h2>
			<form action="" method="POST">
				<label>169.254.1.</label><input type="number" name="end" value="<?php echo end($temp_address); ?>" min="1">
				<input type="submit" value="Save">
			</form>
		</div>
	</body>
</html>