<?php

require_once '/var/www/lib/pi_camera/data/params.php';

$iso_default= '400';
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
				<fieldset>
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
					
					<section class="col col-6">
						<label class="label">Slices</label>
						<label class="input">
							<input name="pg-slices" id="pg-slices" value="60" type="number" maxlength="10">
						</label>
					</section>
					
				</fieldset>
			</div>

		</div>
	</div>
</div>

