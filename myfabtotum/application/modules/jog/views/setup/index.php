<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-gear fa-fw ">
			</i>
			Setup
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('jog')?>" class="btn btn-default"> <i class="fa fa-gamepad"></i> Jog
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<div class="smart-form">
				<fieldset style="background: transparent;">
					<section>
						<label class="label">
							Unit
						</label>
						<label class="select">
							<select name="unit" id="unit">
								<?php $selected= $_unit=='inch' ? 'selected' : ''; ?>
									<option <?php echo $selected ?>
										value="inch">inches
									</option>
									<?php $selected=$_unit=='mm' ? 'selected' : ''; ?>
										<option <?php echo $selected ?>
											value="mm">millimiters
										</option>
							</select>
							<i>
							</i>
						</label>
					</section>
					<section>
						<label class="label">
							Step
						</label>
						<label class="input">
							<input type="text" value="<?php echo $_step ?>" name="step" id="step">
						</label>
					</section>
					<section>
						<label class="label">
							Feedrate
						</label>
						<label class="input">
							<input type="text" value="<?php echo $_feedrate ?>" name="feedrate" id="feedrate">
						</label>
					</section>
					<hr class="simple">
					<section>
						<button id="save-conf" type="button" class="btn btn-default btn-lg btn-block">
							<i class="fa fa-save">
							</i>
							Save
						</button>
					</section>
				</fieldset>
			</div>
		</div>
	</div>
</div>