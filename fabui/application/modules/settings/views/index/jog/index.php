<div class="tab-pane fade in active" id="tab1">
	<div class="row margin-top-10">
		<div class="col-md-12">
			<div class="well no-border">
				<form class="form-horizontal" action="<?php echo site_url('settings/jog') ?>" method="post">
					<fieldset>
						<div class="form-group"> 
							<label class="col-md-1 control-label">Unit</label>
							<div class="col-md-11">
                                <select class="form-control" name="unit" id="unit">
								<?php $selected= $_unit=='inch' ? 'selected' : ''; ?>
									<option <?php echo $selected ?>
										value="inch">inches
									</option>
									<?php $selected=$_unit=='mm' ? 'selected' : ''; ?>
										<option <?php echo $selected ?>
											value="mm">millimiters
										</option>
							     </select>
                            </div>
						</div>
                        
                        <div class="form-group">
                            
                            <label class="col-md-1 control-label">Step</label>
                            <div class="col-md-11">
                                <input class="form-control" value="<?php echo $_step ?>" name="step" id="step" />
                            </div>    
                            
                        </div>
                        
                        <div class="form-group">
                            
                            <label class="col-md-1 control-label">Feedrate</label>
                            <div class="col-md-11">
                                <input class="form-control" value="<?php echo $_feedrate ?>" name="feedrate" id="feedrate" />
                            </div>    
                        </div>   
					</fieldset>
                    
					<div class="form-actions">
						<button class="btn btn-primary" type="submit">
							<i class="fa fa-save"></i> Save
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>