<div class="row">
	<div class="smart-form">
		<header> Set the extended angle (open position)</header>
		<fieldset>
			<div class="row">
				<section class="col col-8">
					<label class="label">Change the value until you find the one that is perfectly vertical</label>
					<label class="input">
						<input id="extend_value" max="165" min="80" type="number" value="<?php echo $eeprom['servo_endstop']['e']?>">
					</label>
				</section>
				<section class="col col-2">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" data-action="open" class="btn btn-sm btn-default btn-block probe-action"><i class="fa fa-long-arrow-down"></i> Open probe</a>
				</section>
				 
				<section class="col col-2">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" data-action="open" class="btn btn-sm btn-default btn-block reset"><i class="fa fa-refresh"></i> Reset</a>
				</section>
				
			</div>
		</fieldset>
		<header> Set the retracted angle (closed position)</header>
		<fieldset>
			<div class="row">
				<section class="col col-8">
					<label class="label">Change the value until you find the one that is perfectly horizontal</label>
					<label class="input">
						<input id="retract_value" min="24" type="number" value="<?php echo $eeprom['servo_endstop']['r']?>">
					</label>
					
				</section>
				<section class="col col-2">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" data-action="close" class="btn btn-sm btn-default btn-block probe-action"><i class="fa fa-long-arrow-up"></i> Close probe</a>
				</section>
			 
				<section class="col col-2">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" data-action="close" class="btn btn-sm btn-default btn-block reset"><i class="fa fa-refresh"></i> Reset</a>
				</section>
				
			</div>
		</fieldset>
	</div>
</div>