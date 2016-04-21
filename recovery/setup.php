<?php

include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php';
include '/var/www/lib/serial.php';

define('TIME_TO_SLEEP', 0.5);

$ini_array = parse_ini_file(SERIAL_INI);

//init serial class
$serial = new Serial();
$serial->deviceSet($ini_array['port']);
$serial->confBaudRate($ini_array['baud']);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

$serial->serialflush();


$time_to_sleep_after_post = 0;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	

	if(isset($_POST['action'])){
		
		$action = $_POST['action'];
		
		
		
		switch($action){
			
			case 'eeprom': // restore eprom

				$time_to_sleep_after_post = 5;
				//;Steps per unit: 
				$serial->sendMessage('M92 X72.58 Y72.58 Z2133.33 E3048.16'.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M500'.PHP_EOL);
				//sleep(TIME_TO_SLEEP);
				$serial->sendMessage('M203 X550.00 Y550.00 Z15.00 E12.00 '.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M500'.PHP_EOL);
				//sleep(TIME_TO_SLEEP);
				$serial->sendMessage('M204 S4000.00 T150'.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M500'.PHP_EOL);
				//sleep(TIME_TO_SLEEP);
				$serial->sendMessage('M205 S0.00 T0.00 B20000 X25.00 Z0.40 E1.00'.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M500'.PHP_EOL);
				//sleep(TIME_TO_SLEEP);
				$serial->sendMessage('M206 X0.00 Y0.00 Z0.00'.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M500'.PHP_EOL);
				//sleep(TIME_TO_SLEEP);
				$serial->sendMessage('M301 P15.00 I5.00 D30.00'.PHP_EOL);
				$serial->serialflush();
				//sleep(TIME_TO_SLEEP);
				//$serial->sendMessage('M710 S36'.PHP_EOL);
				$serial->serialflush();
				$serial->sendMessage('M500'.PHP_EOL);
				sleep(TIME_TO_SLEEP);
				$serial->readPort();
				$serial->serialflush();
				
				
				$alert['type'] = 'success';
				$alert['messsage'] = 'EEPROM Parameters Restored';
				
				break;
			
		}
	}	
}


//read eprom
$serial->serialflush();
sleep($time_to_sleep_after_post);
$serial->sendMessage("M503".PHP_EOL);
sleep(TIME_TO_SLEEP);
$eeprom = $serial->readPort();
$serial->deviceClose();

$eeprom = str_replace("echo:   ", "", $eeprom);
$eeprom = str_replace("echo:  ", "", $eeprom);
$eeprom = str_replace("echo:", "", $eeprom);

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
				<li>
					<a href="/recovery/index.php">Recovery</a>
				</li>
				<li>
					Setup Tool
				</li>
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
					<pre><?php echo PHP_EOL.$eeprom; ?>
					</pre>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well">
						<form method="POST">
							<button name="action" value="eeprom" class="btn btn-primary" type="submit"><i class="fa"></i>Restore Eprom </button>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php
	include 'footer.php';
	?>

	<script src="/assets/js/notification/SmartNotification.min.js"></script>
	<script src="/assets/js/plugin/fuelux/wizard/wizard.min.js"></script>

	<script>
		var fabui = true;
		var setup_wizard = false;
		var number_tasks = 0;
		var number_updates = 0;
		var number_notifications = 0;

	</script>

	<script type="text/javascript">
		
		$(".btn").on('click', function(){
			openWait("Restoring EEPROM parameters");
		});
		
	</script>

</body>
</html>