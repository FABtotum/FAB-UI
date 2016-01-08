<?php


$macrofile = "/var/www/fabui/python/gmacro.py";
$data = file_get_contents($macrofile);

$data = explode('preset=="', $data);

$options = array();

for ($i = 1; $i <= count($data); $i++) {
	if(isset($data[$i])){
		$macroname = explode('":', $data[$i]);
		if($macroname[0]!='') array_push($options, $macroname[0]);
	}
	
}

if(isset($_GET['macro']) && $_GET['macro']!='' && in_array($_GET['macro'], $options)){
	
	
	$macro = $_GET['macro'];
	
	$time = time();
	
	$command = "sudo python /var/www/fabui/python/gmacro.py ".$macro." /var/www/temp/".$macro.$time.".trace /var/www/temp/".$macro.$time.".log";
	shell_exec($command);
	
	$trace = file_get_contents("/var/www/temp/".$macro.$time.".trace");
	
	shell_exec('sudo rm -rf '.'/var/www/temp/'.$macro.$time.'.trace');
	shell_exec('sudo rm -rf '.'/var/www/temp/'.$macro.$time.'.log');  
	
	
	
}

if(!isset($macro)) $macro = '';

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
					<li>Macro Simulator</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<div class="form-inline margin-bottom-10">
								<fieldset>
									<div class="form-group">
										<select class="form-control macro">
											<?php foreach($options as $key => $value): ?>
												<option <?php echo $value == $macro ? 'selected' : ''; ?> value="<?php echo $value ?>"><?php echo $value; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</fieldset>
							</div>
							
							<?php if(isset($trace) && $trace != ''): ?>
								<h6><?php echo $macro; ?></h6>
								<pre><?php echo $trace; ?></pre>
							<?php endif; ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		include 'footer.php';
 		?>
 		
 		<script type="text/javascript">
 			
 			$(function() {
 				
 				$('.macro').on('change', function(){
 					
 					document.location.href = 'macrosim.php?macro=' + $(this).val();
 				});
 				
 			});
 			
 		</script>
 		
	</body>
</html>

