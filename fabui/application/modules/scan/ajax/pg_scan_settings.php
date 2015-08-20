<?php

require_once '/var/www/lib/pi_camera/data/params.php';

$iso_default = '400';
$size_default = '1920-1080';
?>


<div class="row">
	<div class="col-sm-6">
		<h6 class="text-primary">Set scan parameters</h6>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="well well-light">

			<div class="smart-form">
				<header>Cam & Scan Settings</header>
				<fieldset>
					<div class="row">
						<section class="col col-6">
							<label class="label">ISO</label><label class="select">
								<select name="pg-iso" id="pg-iso">
									
									<?php foreach($params['iso'] as $key => $value): ?>
										
										<?php $selected = $key == $iso_default ? 'selected="selected"' : ''; ?>
										
										<option <?php echo $selected ?>  value="<?php echo $key ?>"><?php echo $value; ?></option>
									<?php endforeach; ?>
									
								</select> <i></i> 
							</label>
						</section>
						
						<section class="col col-6">
							<label class="label">Size</label><label class="select">
								<select name="pg-size" id="pg-size">
									<?php foreach($params['size'] as $key => $value): ?>
										<?php $selected = $key == $size_default ? 'selected="selected"' : ''; ?>
										<option <?php echo $selected ?> value="<?php echo $key ?>"><?php echo $value; ?></option>
									<?php endforeach; ?>
								</select> <i></i> 
							</label>
						</section>
					
					</div>
					
					<div class="row">
						<section class="col col-6">
							<label class="label">Slices</label>
							<label class="input">
								<input name="pg-slices" id="pg-slices" value="60" type="number" maxlength="10">
							</label>
						</section>
					</div>
					
				</fieldset>	
				<header>Desktop Server</header>
				<fieldset>
					<div class="row">
						
						<section class="col col-6">
							<label class="label">IP Address:</label>
							<label class="input">
								<input  name="pc-host-address" id="pc-host-address" value="<?php echo $_SERVER["REMOTE_ADDR"] ?>" type="text">
							</label>
							<div class="margin-top-10"><p><strong>Before proceeding start desktop server on your pc and check connection. If you don't have the desktop server you can download it <a target="_blank" href="/utilities/FabtotumDesktopServer.jar">here</a></strong></p></div>
						</section>
						
						<section class="col col-3">
							<label class="label">Port:</label>
							<label class="input">
								<input name="pc-host-port" id="pc-host-port" value="9898" type="text">
							</label>
						</section>
						
						<section class="col col-3">
							
								<label class="label text-center">Test connection</label>
								<a href="javascript:check_connection();" id="connection_test_button" class="btn btn-sm btn-primary btn-block" >Check Connection</a>
								
						</section>
						
					</div>
					
				</fieldset>

				
				
			</div>

		</div>
	</div>
</div>

