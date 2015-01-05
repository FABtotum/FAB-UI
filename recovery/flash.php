<?php

include 'utilities.php';

$fw_file = '/var/www/build/Marlin.cpp.hex';
$exists = file_exists($fw_file);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['flash'] == 'local') {
		$command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $fw_file . ':i';
		$response_flash = shell_exec($command);
		sleep(10);
		$start_up = shell_exec('sudo python /var/www/fabui/python/gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log');

		if (strpos($response_flash, 'done with autoreset') !== false) {
			$alert['type'] = 'success';
			$alert['messsage'] = 'FABlin Firmware flashed correctly';
		} else {
			$alert['type'] = 'danger';
			$alert['messsage'] = 'Oops an error occured, try to flash again';
		}
	}

	if ($_POST['flash'] == 'remote') {

		if (is_internet_avaiable()) {

			$remote_version = marlin_get_remote_version();
			$source = "http://update.fabtotum.com/MARLIN/download/" . $remote_version . "/Marlin.cpp.hex";

			// download fw file

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $source);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			$data = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);

			$destination = "/var/www/build/Marlin.cpp.hex";
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);

			chmod($destination, 0777);

			$command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $destination . ':i';
			$response_flash = shell_exec($command);
			sleep(10);
			$start_up = shell_exec('sudo python /var/www/fabui/python/gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log');

			if (strpos($response_flash, 'done with autoreset') !== false) {
				$alert['type'] = 'success';
				$alert['messsage'] = 'FABlin Firmware downloaded and flashed correctly';
			} else {
				$alert['type'] = 'danger';
				$alert['messsage'] = 'Oops an error occured, try to flash again';
			}

		} else {
			$alert['type'] = 'danger';
			$alert['messsage'] = 'Oops no internet connection, plaese check your network configuration';
		}

	}

}

include 'header.php';
?>
	</head>
	<body>
		<header id="header">
			<div id="logo-group">
				<span id="logo"><img src="/assets/img/logo-0.png"></span>
			</div>
		</header>
		<div id="main" role="main">
			<div id="ribbon">
				<ol class="breadcrumb">
					<li><a href="/recovery/index.php">Recovery</a></li>
					<li>Flash FABlin firmware</li>
				</ol>
			</div>
			<div id="content">
				
				<?php if(isset($alert)): ?>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-<?php echo $alert['type'] ?> fade in">
								<?php echo $alert['messsage'] ?>
							</div>
						</div>
					</div>
					
				<?php endif; ?>
				
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<p>Firmware file: <?php echo $fw_file; ?></p>
							<p>Do you really want to flash firmware from local file ? </p>
							<form method="POST">
								<button name="flash" value="local" class="btn btn-primary" type="submit"><i class="fa"></i>Flash Local</button>
							</form>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<p>Do you want to download the latest version of the firmware and flash it? (Need internet connection)</p>
							<form method="POST">
								<button name="flash" value="remote" class="btn btn-primary" type="submit"><i class="fa"></i>Flash Remote</button>
							</form>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<?php
		include 'footer.php';
 ?>
	</body>
</html>