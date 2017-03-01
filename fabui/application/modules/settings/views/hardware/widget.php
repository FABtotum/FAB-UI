<div class="row ">
	<div class="col-sm-12">
		<div class="smart-form">
			<fieldset>
				<section>
					<label class="radio"><input type="radio" name="settings_type" value="default"><i></i>Use default settings</label>
					<label class="radio"><input type="radio" name="settings_type" value="custom"><i></i>Use custom settings</label>
				</section>
				<hr class="simple">
				
				<div class="row custom-settings">
					<section class="col col-6">
						<label class="label">Engage/Disengage Feeder option</label>
						<label class="select">
							<select name='show_feeder' id="show_feeder">
								<option value="yes">Yes</option>
								<option value="no" <?php echo !$show_feeder ? 'selected="selected"' : ''; ?>>No</option>
							</select> <i></i> </label>
					</section>
					
					
					<section class="col col-6">
						<label class="label">Invert X Endstop Logic</label>
						<label class="select">
							<select name='invert_x_endstop_logic' id="invert_x_endstop_logic">
								<option value="no" >No</option>
								<option value="yes" <?php echo $invert_x_endstop_logic ?'selected="selected"' : '';  ?>>Yes</option>
							</select> <i></i> </label>
						</label>
					</section>
					
				</div>
				
				<div class="row custom-settings">
					<section class="col col-3">
						<label class="label">Extruder steps per unit E mode</label>
						<label class="input">
							<input type="text" id="feeder-extruder-steps-per-unit-e" name="feeder-extruder-steps-per-unit-e" placeholder="" value="<?php echo $feeder_extruder_steps_per_unit_e_mode; ?>">
						</label>
					</section>
					<section class="col col-3">
						<label class="label">Extruder steps per unit A mode</label>
						<label class="input">
							<input type="text" id="feeder-extruder-steps-per-unit-a" name="feeder-extruder-steps-per-unit-a" placeholder="" value="<?php echo $feeder_extruder_steps_per_unit_a_mode; ?>">
						</label>
						
					</section>
				</div>
				
				<div class="row custom-settings">
					<section class="col col-6">
						<p >NOTE: If you change values for Extruder steps you have to restart the FABtotum so that can values take effect</p>
					</section>
				</div>
				
				<section class="custom-settings">
					<label class="label">Custom overrides</label>
					<label class="textarea"> <textarea rows="8" name="custom_overrides" id="custom_overrides"><?php echo $custom_overrides; ?></textarea> </label>
				</section>
				
					
			</fieldset>
			
			<footer>
				<button value="save" type="button"  class="btn btn-primary save"><i class="fa fa-save"></i> Save</button>
				<button value="exec" type="button"  class="btn btn-primary save"><i class="fa fa-save"></i> Save & Exec</button>
			</footer>
			
		</div>
	</div>
</div>