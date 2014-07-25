<div class="tab-pane active fade in" id="tab2">
	<div class="row margin-top-10">
		<div class="col-md-12">
			<div class="well no-border">
				<form class="form-horizontal" action="<?php echo site_url('settings/create') ?>" method="post" id="create-form">
                    <fieldset>
                        <legend>Slic3r</legend>
                            <div class="form-group">
                                <label class="col-md-1 control-label">Preset</label>
                                <div class="col-md-11">
                                    <select class="form-control presets" >
                                        <option></option>
                                        <?php foreach($_slicer_presets as $_preset): ?>
                                        <option value="<?php echo str_replace('/var/www/', '/', $_preset['file']);  ?>"><?php echo $_preset['name'].' - '.$_preset['description']; ?></option>    
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-1"></label>
                                <div class="col-md-11">
                                    <div id="editor0" class="well" style="height: 200px;"></div>
                                </div>
                            </div>
                            
                    </fieldset>
					<fieldset>
						<legend>
							Default G-code
						</legend>
						<div class="form-group">
							<label class="col-md-1 control-label">
								Start G-code
							</label>
							<div class="col-md-11">
                                <div id="editor1" class="well" style="height: 200px; display: none;"><?php echo $_start_gcode ?></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-1 control-label">
								End G-code
							</label>
							<div class="col-md-11">
								<div id="editor2"  class="well" style="height: 200px; display: none;"><?php echo $_end_gcode ?></div>
							</div>
						</div>
					</fieldset>
					<div class="form-actions">
						<button class="btn btn-primary" id="submit">
							<i class="fa fa-save">
							</i>
							Submit
						</button>
					</div>
                    
                    <input type="hidden" name="start_gcode" id="start_gcode" />
                    <input type="hidden" name="end_gcode"   id="end_gcode" />
                    
				</form>
			</div>
		</div>
	</div>
</div>