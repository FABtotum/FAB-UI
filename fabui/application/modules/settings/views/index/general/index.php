<div class="tab-pane fade in active" id="tab1">
	<div class="row margin-top-10">
		<div class="col-md-12">
			<div class="well no-border">
				<form class="form-horizontal" action="<?php echo site_url('settings') ?>" method="post">

					<fieldset>

						<legend>
							Safety - enable/disable warnings
						</legend>

						<div class="form-group">
							<label class="col-md-2 control-label"> Door </label>
							<div class="col-md-10">
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="safety-door" value="1" <?php echo $_safety_door == '1' ? 'checked="checked"' : '' ?>>
										<span>Enable</span> </label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="safety-door" value="0" <?php echo $_safety_door == '0' ? 'checked="checked"' : '' ?>>
										<span>Disable</span> </label>
								</div>

							</div>
						</div>
					</fieldset>
					<br>
					<fieldset>
						<legend>
							Homing preferences
						</legend>
						<div class="form-group">
							<label class="col-md-2 control-label">Switch</label>
							<div class="col-md-10">
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="switch" value="0" <?php echo $_switch == '0' ? 'checked="checked"' : '' ?>>
										<span>Left</span> </label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="switch" value="1" <?php echo $_switch == '1' ? 'checked="checked"' : '' ?>>
										<span>Right</span> </label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label">Z-Probe</label>
							<div class="col-md-2">
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="zprobe" value="0" <?php echo $_zprobe == '0' ? 'checked="checked"' : '' ?>>
										<span>Enable</span> </label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="zprobe" value="1" <?php echo $_zprobe == '1' ? 'checked="checked"' : '' ?>>
										<span>Disable</span> </label>
								</div>
							</div>
							<label class="col-md-2 control-label">Z Max Home Pos (mm)</label>
							<div class="col-md-6">
								<input class="form-control"  type="text" id="zmax-homing" value="<?php echo $_zmax; ?>">
							</div>
						</div>

					</fieldset>

					<fieldset>
						<legend>
							Customized input actions
						</legend>
						<div class="form-group">
							<label class="col-md-2 control-label">Both Y Endstops pressed</label>
							<div class="col-md-10">
								<select class="form-control" id="both-y-endstops">
									<option value="None" <?php echo $_both_y_endstops == 'None' ? 'selected="selected"' : '' ?> >None</option>
									<option value="Shutdown" <?php echo $_both_y_endstops == 'Shutdown' ? 'selected="selected"' : '' ?> >Shutdown</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Both Z Endstops pressed</label>
							<div class="col-md-10">
								<select class="form-control" id="both-z-endstops">
									<option value="None" <?php echo $_both_z_endstops == 'None' ? 'selected="selected"' : '' ?> >None</option>
									<option value="Shutdown" <?php echo $_both_z_endstops == 'Shutdown' ? 'selected="selected"' : '' ?> >Shutdown</option>
								</select>
							</div>
						</div>
						<div>
							<b>Warning: You have to restart the FABtotum so that some customized input actions take effect</b>
						</div>
					</fieldset>
					<br>
					<fieldset>
						<legend>
							Feeder
						</legend>
						<div class="form-group">
							<label class="col-md-2 control-label">Disengage Offset (mm)</label>
							<div class="col-md-10">
								<input class="form-control"  type="text" id="feeder-disengage-offset" value="<?php echo $_feeder_disengage; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label">Extruder steps per unit E mode</label>
							<div class="col-md-10">
								<input class="form-control"  type="text" id="feeder-extruder-steps-per-unit-e" value="<?php echo $_feeder_extruder_steps_per_unit_e_mode; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Extruder steps per unit A mode</label>
							<div class="col-md-10">
								<input class="form-control"  type="text" id="feeder-extruder-steps-per-unit-a" value="<?php echo $_feeder_extruder_steps_per_unit_a_mode; ?>">
							</div>
						</div>
						
						<div class="margin-bottom-10">
							<strong>NOTE: If you change values for Extruder steps you have to restart the FABtotum so that can values take effect</strong>
						</div>
						
					</fieldset>
					
					<fieldset>
						<legend>
 							Milling
						</legend>
 						<div class="form-group">
 							<label class="col-md-2 control-label">Sacrificial Layer Thickness (mm)</label>
 							<div class="col-md-10">
 								<input class="form-control"  type="text" id="milling-sacrificial-layer-offset" value="<?php echo $_milling_sacrificial_layer_offset; ?>">
 							</div>
 						</div>
				</fieldset>
					
					<fieldset>
						<legend>Api</legend>
						<div class="form-group">
							<label class="col-md-2 control-label">File Upload API key</label>
							<div class="col-md-9">
								<input class="form-control"  type="text" id="upload-api-key" value="<?php echo $_upload_api_key; ?>">
							</div>
							<button id="gen-key-button" class="btn btn-primary col-md-1" type="button">
							New key
							</button>
							
						</div>
				
					</fieldset>
					

					<fieldset>
						<legend>
							Lighting
						</legend>
						<div class="form-group">
							<label class="col-md-2 control-label"> Standby </label>
							<div class="col-md-4">
								<div class="nouislider standby-color standby-red" id="red"></div>
								<br />
								<div class="nouislider standby-color standby-green" id="green"></div>
								<br />
								<div class="nouislider standby-color standby-blue" id="blue"></div>
								<br />
							</div>
							<div class="col-md-3">
								<div class="result" style="height: 100px; border: 1px solid #fff; box-shadow: 0 0 10px; background-color: rgb(<?php echo $_standby_color['r'] ?>, <?php echo $_standby_color['g'] ?>, <?php echo $_standby_color['b'] ?>); color: rgb(<?php echo $_standby_color['r'] ?>, <?php echo $_standby_color['g'] ?>, <?php echo $_standby_color['b'] ?>);"></div>
							</div>
							<input name="standby-color-red"   id="standby-color-red"   type="hidden" value="<?php echo $_standby_color['r'] != '' ? $_standby_color['r'] : 0 ?>" />
							<input name="standby-color-green" id="standby-color-green" type="hidden" value="<?php echo $_standby_color['g'] != '' ? $_standby_color['g'] : 0 ?>"/>
							<input name="standby-color-blue"  id="standby-color-blue"  type="hidden" value="<?php echo $_standby_color['b'] != '' ? $_standby_color['b'] : 0 ?>"/>
						</div>

					</fieldset>
					<div class="form-actions">
						<button id="save-button" class="btn btn-primary" type="button">
							<i class="fa fa-save"></i>&nbsp;Save
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>