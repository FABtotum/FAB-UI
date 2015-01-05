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
							<label class="col-md-1 control-label">
								Door
							</label>
							<div class="col-md-11">
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="safety-door" value="1" <?php echo $_safety_door == '1' ? 'checked="checked"' : '' ?>>
										<span>Enable</span>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" name="safety-door" value="0" <?php echo $_safety_door == '0' ? 'checked="checked"' : '' ?>>
										<span>Disable</span>
									</label>
								</div>
								
								
							</div>
						</div>
					</fieldset>
					
					<fieldset>
						<legend>
							Lighting
						</legend>
						<div class="form-group">
							<label class="col-md-1 control-label">
								Standby
							</label>
							<div class="col-md-4">
								<div class="nouislider standby-color standby-red" id="red"></div>
								<br />
								<div class="nouislider standby-color standby-green" id="green"></div>
								<br />
								<div class="nouislider standby-color standby-blue" id="blue"></div>
								<br />
							</div>
							<div class="col-md-3">
								<div class="result" style="height: 100px; border: 1px solid #fff; box-shadow: 0 0 10px; background-color: rgb(<?php echo $_standby_color['r'] ?>, <?php echo $_standby_color['g'] ?>, <?php echo $_standby_color['b'] ?>); color: rgb(<?php echo $_standby_color['r'] ?>, <?php echo $_standby_color['g'] ?>, <?php echo $_standby_color['b'] ?>);">
								</div>
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