<?php

include '/var/www/lib/config.php';

$feed = 1000;
$raspi_still = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	include "php_serial.class.php";
	
	
	$value = isset($_POST['c']) && $_POST['c'] != '' ? $_POST['c'] : '';
	$feed  = isset($_POST['feed']) && $_POST['feed'] != '' ? $_POST['feed'] : $feed;
	
	
	if($value != ''){
		
		if($value == 'mdi'){ 
			$value = isset($_POST['mdi-code']) && $_POST['mdi-code'] != '' ? strtoupper($_POST['mdi-code']) : '';
		}
		
		
		if(strpos($value, 'G0') !== false){
			$value .= ' F'.$feed;
		}
		
		$ini_array = parse_ini_file(SERIAL_INI);
		
		$serial = new phpSerial;
		$serial->deviceSet($ini_array['port']);
		$serial->confBaudRate($ini_array['baud']);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->deviceOpen();
		$serial->sendMessage($value."\r\n");
		$reply = $serial->readPort();
		$serial->deviceClose();
	}
	
	
	if(isset($_POST['s']) && $_POST['s'] == 1){
		$raspi_still = true;
		
		
		exec('sudo raspistill -hf -w 512 -h 320 -o /var/www/temp/picture.jpg -t 1');
		$filename = "/var/www/temp/picture.jpg";
		$handle = fopen($filename, "rb");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		
	}
	
}

if(!isset($value)) $value = '';
if(!isset($reply)) $reply = '';

include 'header.php';
?>
	<style>
		.uppercase {
			text-transform: uppercase;
		}
	</style>
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
					Jog
				</li>
			</ol>
		</div>
		<div id="content">
			<div class="row">
				<div class="col-sm-12">
					<div class="well">
						<form method="POST">
							<fieldset>
								<div class="form-group">
									<label>Setup:</label>&nbsp;
									<button type="submit" class="btn btn-primary" name="s" value="1">Still</button>
									<button type="submit" class="btn btn-primary" name="c" value="G92 X0 Y0 Z0">Zero All</button>
									<button type="submit" class="btn btn-primary" name="c" value="G0 X0 Y0 Z0">Go To Zero</button>
									<button type="submit" class="btn btn-primary" name="c" value="M105">Temperature</button>
								</div>
							</fieldset>
							<fieldset>
								<div class="form-group">
									<label>Feedrate:</label>&nbsp;
									<input name="feed" class="form-control" placeholder="<?php echo $feed; ?>" type="number" value="<?php echo $feed; ?>">
								</div>
							</fieldset>
							<fieldset>
								<div class="form-group">
									<label>Motors:</label>&nbsp;
									<button type="submit"  class="btn btn-primary" name="c" value="M17">On</button>
									<button type="submit"  class="btn btn-primary" name="c" value="M18">Off</button>
								</div>
							</fieldset>
							<fieldset>
								<div class="form-group">
									<label>Coordinate:</label>&nbsp;
									<button type="submit" class="btn btn-primary" name="c" value="G91">Relative</button>
									<button type="submit" class="btn btn-primary" name="c" value="G90">Absolute</button>
								</div>
							</fieldset>
							<fieldset>
								<div class="form-group">
									<label>Jog</label>
									<table>
										<tr>
											<td></td>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 Y+10"><i class="fa fa-arrow-up"></i></button></td>
											<td width="25">&nbsp;</td>
											<td width="25">&nbsp;</td>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 Z-10">Z+</button></td>
										</tr>
										<tr>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 X-10"><i class="fa fa-arrow-left"></i></button></td>
											<td></td>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 X+10"><i class="fa fa-arrow-right"></i></button></td>
											<td width="25">&nbsp;</td>
											<td width="25">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td></td>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 Y-10"><i class="fa fa-arrow-down"></i></button></td>
											<td width="25">&nbsp;</td>
											<td width="25">&nbsp;</td>
											<td><button type="submit" class="btn btn-primary" name="c" value="G0 Z+10">Z-</button></td>
										</tr>
									</table>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary" name="c" value="G0 E-45"><i class="fa fa-arrow-left"></i> A</button>
									<button type="submit" class="btn btn-primary" name="c" value="G0 E+45"><i class="fa fa-arrow-right"></i> A</button>
								</div>
							</fieldset>
							<fieldset>
								<div class="form-group">
									<label>Mdi</label>
									<textarea name="mdi-code" class="form-control uppercase"><?php echo isset($value) ? $value : ''; ?></textarea>
									<button type="submit" class="btn btn-primary margin-top-10" name="c" value="mdi">Exec</button>
								</div>
							</fieldset>
						</form>
						
						<hr>
						Console:
						<pre><?php echo $value.': '.$reply; ?></pre>
					</div>
				</div>
			</div>
			<?php if($raspi_still) :?>
			<div class="row">
				<div class="col-sm-12">
					<div class="well">
						<img src="/temp/picture.jpg">
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	include 'footer.php';
	?>

	<script type="text/javascript"></script>

</body>
</html>

