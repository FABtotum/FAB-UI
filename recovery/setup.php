<?php

include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(isset($_POST['action'])){
		$action = $_POST['action'];
		
		switch($action){
			case 'eeprom': // restore eprom
				$response = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m restore_eeprom'), true);
				shell_exec('sudo python '.PYTHON_PATH.'boot.py -R -s');
				$alert['type']     = 'success';
				$alert['messsage'] = 'EEPROM Parameters Restored';
				break;
		}
	}	
}

//E1524.00
//sleep(3);
$response = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c M503'), true);
$eeprom = $response['reply'];

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
	<script type="text/javascript">
		
		$(".btn").on('click', function(){
			openWait("Restoring EEPROM parameters", null, false);
		});
		
	</script>

</body>
</html>