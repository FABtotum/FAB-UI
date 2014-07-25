<div class="tab-pane fade in active" id="tab1">
	<div class="row margin-top-10">
		<div class="col-md-12">
			<div class="well no-border">
				<form class="form-horizontal" action="<?php echo site_url('settings') ?>" method="post">
					<fieldset>
						<legend>
							Theme
						</legend>
						<div class="form-group"> 
							<label class="col-md-1 control-label">
								Skin
							</label>
							<div class="col-md-11">
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" <?php echo $_theme_skin=='smart-style-0' ? 'checked="checked"' : '' ?>
										name="theme_skin" value="smart-style-0">
										<span>
											Default
										</span>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" <?php echo $_theme_skin=='smart-style-1' ? 'checked="checked"' : '' ?>
										name="theme_skin" value="smart-style-1">
										<span>
											Dark Elegance
										</span>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" <?php echo $_theme_skin=='smart-style-2' ? 'checked="checked"' : '' ?>
										name="theme_skin" value="smart-style-2">
										<span>
											Ultra Light
										</span>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" class="radiobox style-0" <?php echo $_theme_skin=='smart-style-3' ? 'checked="checked"' : '' ?>
										name="theme_skin" value="smart-style-3">
										<span>
											Google Skin
										</span>
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
								<div class="result" style="height: 100px; border: 1px solid #fff; box-shadow: 0 0 10px; background-color: rgb(<?php echo $_standby_color['red'] ?>, <?php echo $_standby_color['green'] ?>, <?php echo $_standby_color['blue'] ?>); color: rgb(<?php echo $_standby_color['red'] ?>, <?php echo $_standby_color['green'] ?>, <?php echo $_standby_color['blue'] ?>);">
								</div>
							</div>
							<input name="standby-color-red" id="standby-color-red" type="hidden" value="<?php echo $_standby_color['red'] != '' ? $_standby_color['red'] : 0 ?>" />
							<input name="standby-color-green" id="standby-color-green" type="hidden" value="<?php echo $_standby_color['green'] != '' ? $_standby_color['green'] : 0 ?>"/>
							<input name="standby-color-blue" id="standby-color-blue" type="hidden" value="<?php echo $_standby_color['blue'] != '' ? $_standby_color['blue'] : 0 ?>"/>
						</div>
                        
                        <div class="form-group">
                            <label class="col-md-1 control-label">Printing</label>
                            <div class="col-md-11">
                                <input type="text" id="hue-demo" class="form-control printing" data-control="hue" value="#ff6161" /> 
                            </div>
                        </div>
                        
					</fieldset>
					<div class="form-actions">
						<button class="btn btn-primary" type="submit">
							<i class="fa fa-save">
							</i>
							Submit
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>