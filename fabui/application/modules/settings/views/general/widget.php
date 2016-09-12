
<style>.hardware-save{margin-right:10px;}</style>
<div class="tab-content padding-10">
	
	<!-- hardware -->
	<div class="tab-pane fade in active" id="hardware">
	
		
		<div class="row">
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
							<!-- 
							<section class="col col-3">
								<label class="label">Extruder steps per unit E mode</label>
								<label class="input">
									<input type="text" id="hw-feeder-extruder-steps-per-unit-e" name="hw-feeder-extruder-steps-per-unit-e" placeholder="" value="<?php echo $hw_feeder_extruder_steps_per_unit_e_mode; ?>">
								</label>
							</section>
							 -->
							<section class="col col-3">
								<label class="label">Extruder steps per unit A mode</label>
								<label class="input">
									<input type="text" id="hw-feeder-extruder-steps-per-unit-a" name="hw-feeder-extruder-steps-per-unit-a" placeholder="" value="<?php echo $hw_feeder_extruder_steps_per_unit_a_mode; ?>">
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
							<label class="textarea"> <textarea rows="8" name="custom_overrides" id="custom_overrides" style="text-transform: uppercase;"><?php echo $custom_overrides; ?></textarea> </label>
						</section>
						
							
					</fieldset>
					
				</div>
			</div>
		</div>
	
		
	</div>
	<!-- safety -->
	<div class="tab-pane fade in" id="safety">
		<div class="row">
			<div class="col-sm-12">
				<p>Enable / disable warnings</p>
				<div class="smart-form">
					<fieldset>
						<section>
							<label class="label">Door Safety Messages</label>
							<div class="inline-group">
								<label class="radio">
									<input type="radio" name="safety-door" value="1" <?php echo $_safety_door == '1' ? 'checked="checked"' : '' ?> >
									<i></i>Enable</label>
								<label class="radio">
									<input type="radio" name="safety-door" value="0" <?php echo $_safety_door == '0' ? 'checked="checked"' : '' ?>>
									<i></i>Disabled</label>
							</div>
						</section>
					</fieldset>
					
					<fieldset>
						<section>
							<label class="label">Machine Limits Collision warning</label>
							<div class="inline-group">
								<label class="radio">
									<input type="radio" name="collision-warning" value="1" <?php echo $_collision_warning == '1' ? 'checked="checked"' : '' ?> >
									<i></i>Enable</label>
								<label class="radio">
									<input type="radio" name="collision-warning" value="0" <?php echo $_collision_warning == '0' ? 'checked="checked"' : '' ?>>
									<i></i>Disabled</label>
							</div>
						</section>
					</fieldset>
					
				</div>
			</div>
		</div>
	</div>
	<!-- end safety -->
	<!-- homing -->
	<div class="tab-pane fade" id="homing">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<section>
							<label class="label">Default Homing Direction</label>
							<div class="inline-group">
								<label class="radio">
									<input type="radio" name="switch" value="0" <?php echo $_switch == '0' ? 'checked="checked"' : '' ?> >
									<i></i>Left</label>
								<label class="radio">
									<input type="radio" name="switch" value="1" <?php echo $_switch == '1' ? 'checked="checked"' : '' ?>>
									<i></i>Right</label>
							</div>
						</section>
					</fieldset>
					<fieldset>
						<section>
							<label class="label">Use the Z Touch Probe during homing</label>
							<div class="inline-group">
								<label class="radio">
									<input type="radio" name="zprobe" value="0" <?php echo $_zprobe == '0' ? 'checked="checked"' : '' ?> >
									<i></i>Enabled</label>
								<label class="radio">
									<input type="radio" name="zprobe" value="1" <?php echo $_zprobe == '1' ? 'checked="checked"' : '' ?>>
									<i></i>Disabled</label>
							</div>
						</section>
						<div class="row">
							<section class="col col-6">
								<label class="label">Z Max Home Pos (mm)</label>
								<label class="input">
									<input type="text" id="zmax-homing" value="<?php echo $_zmax; ?>">
								</label>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end homing -->
	<!-- customized-actions -->
	<div class="tab-pane fade" id="customized-actions">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<div class="row">
							<section class="col col-6">
								<label class="label">Both Y Endstops pressed</label>
								<label class="select"><?php echo form_dropdown('both-y-endstops', $options_customized_actions, $_both_y_endstops, 'id="both-y-endstops"'); ?> <i></i></label>
							</section>
						</div>
						<div class="row">
							<section class="col col-6">
								<label class="label">Both Z Endstops pressed</label>
								<label class="select"><?php echo form_dropdown('both-z-endstops', $options_customized_actions, $_both_z_endstops, 'id="both-z-endstops"'); ?> <i></i></label>
							</section>
						</div>
						<div class="row">
							<section class="col col-12">
								<p><strong>Note: you must restart the FABtotum to apply these changes</strong></p>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end customized-actions -->
	<!-- feeder -->
	<div class="tab-pane fade" id="feeder">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<?php if($show_feeder): ?>
						<div class="row">
							<section class="col col-6">
								<label class="label">Disengage Offset (mm)</label>
								<label class="input">
									<input type="text" id="feeder-disengage-offset" value="<?php echo $_feeder_disengage; ?>">
								</label>
							</section>
						</div>
						<?php endif; ?>
						<div class="row">
							<section class="col col-6">
								<label class="label">Extruder steps per unit E mode</label>
								<label class="input">
									<input type="text" id="feeder-extruder-steps-per-unit-e" value="<?php echo $_feeder_extruder_steps_per_unit_e_mode; ?>">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-6">
								<label class="label">Extruder steps per unit A mode</label>
								<label class="input">
									<input type="text" id="feeder-extruder-steps-per-unit-a" value="<?php echo $_feeder_extruder_steps_per_unit_a_mode; ?>">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-12">
								<p><strong>Note: you must restart the FABtotum to apply these changes</strong></p>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end feeder -->
	<!-- print -->
	<div class="tab-pane fade" id="print">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<div class="row">
							<section class="col col-6">
								<label class="label">Pre-heating extruder temperature</label>
								<label class="input">
									<input type="number" min="0" max="250" id="print-preheating-extruder" value="<?php echo $_print_preheating_extruder; ?>">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-6">
								<label class="label">Pre-heating bed temperature</label>
								<label class="input">
									<input type="number" min="0" max="100" id="print-preheating-bed" value="<?php echo $_print_preheating_bed; ?>">
								</label>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end print -->
	<!-- milling -->
	<div class="tab-pane fade" id="milling">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<div class="row">
							<section class="col col-6">
								<label class="label">Sacrificial Layer Thickness (mm)</label>
								<label class="input">
									<input type="text" id="milling-sacrificial-layer-offset" value="<?php echo $_milling_sacrificial_layer_offset; ?>">
								</label>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end milling -->
	<!-- api -->
	<div class="tab-pane fade" id="api">
		<div class="row">
			<div class="col-sm-12">
				<div class="smart-form">
					<fieldset>
						<div class="row">
							<section class="col col-6">
								<label class="label">File Upload API key</label>
								<label class="input">
									<input type="text" id="upload-api-key" value="<?php echo $_upload_api_key; ?>">
								</label>
							</section>
							<section class="col col-2">
								<label class="label">&nbsp;</label>
								<button style="padding: 6px 12px;     margin-top: -2px" id="gen-key-button" class="btn btn-primary btn-block" type="button">New Key</button>
							</section>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<!-- end api -->
	<!-- lighting -->
	<div class="tab-pane fade" id="lighting">
		<div class="row padding-10">
			<div class="col-sm-6">
				<div class="row margin-top-10">
					<div class="col-sm-12">
						<p>Standby</p>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-sm-12">
						<div class="nouislider standby-color standby-red" id="red"></div>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-sm-12">
						
						<div class="nouislider standby-color standby-blue" id="blue"></div>
					</div>
				</div>
				<div class="row margin-top-10">
					<div class="col-sm-12">
						<div class="nouislider standby-color standby-green" id="green"></div>
					</div>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="row margin-top-10">
					<div class="col-sm-12">
						<p>&nbsp;</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="result"></div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<!-- end lighting -->
	
</div>

<div class="widget-footer text-right">
	
	<!--<button value="save" type="button"  class="btn btn-primary hardware-save"><i class="fa fa-save"></i> Save</button> -->
	<button value="exec" type="button"  class="btn btn-primary hardware-save"><i class="fa fa-save"></i> Save & Exec</button>
	
	<button id="save-button" class="btn btn-primary" type="button" style="display:none;"><i class="fa fa-save"></i>&nbsp;Save</button>
</div>

