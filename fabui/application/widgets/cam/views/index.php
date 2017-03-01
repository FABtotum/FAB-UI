<div class="row">

	<div class="col-sm-7 margin-bottom-10">

		<div class="row margin-bottom-10">
			<div class="col-sm-12">
				<img id="raspi_picture" class="img-responsive" src="<?php echo widget_url('cam').'media/image.php' ?>" />
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 margin-bottom-10">
				
				<div class="btn-group btn-group-justified">
					<a data-attribue-direction="up" href="javascript:void(0);" class="btn btn-default btn-sm directions "> Y <i class="fa fa-arrow-up"></i></a>
					<a href="javascript:void(0);" class="btn btn-default btn-sm" id="take_photo"><i class="fa fa-camera"></i> <span class="hidden-mobile hidden-tablet">Take a pic</span></a>
					<a data-attribue-direction="down" href="javascript:void(0);" class="btn btn-default btn-sm directions "> Y <i class="fa fa-arrow-down"></i></a>
				</div>
			</div>

			<div class="col-sm-4 ">
				<div class="btn-group btn-group-justified">
					<a  href="<?php echo widget_url('cam').'media/download.php' ?>" class="btn btn-default btn-sm"><i class="fa fa-download"></i> <span class="hidden-mobile hidden-tablet"> Download </span></a>
				</div>
			</div>
		</div>

	</div>

	<div class="col-sm-5">

		<ul id="widget-cam-tab" class="nav nav-tabs bordered">
			<li class="active">
				<a href="#tab-photo" data-toggle="tab"><i class="fa fa-camera"></i> Photo</a>
			</li>
			<li>
				<a href="#tab-settings" data-toggle="tab"><i class="fa fa-cogs"></i> Settings</a>
			</li>
		</ul>

		<div class="tab-content padding-10">
			<div class="tab-pane fade in active" id="tab-photo">

				<div class="row">

					<div class="col-sm-12">

						<div class="smart-form">
							<fieldset>
								<section class="col col-6">
									<label class="label">Type</label>
									<label class="select">
										<?php echo form_dropdown('encoding', $params['encoding'], $settings['encoding'], 'id="encoding"'); ?><i></i> </label>
								</section>
								<section class="col col-6">
									<label class="label">Size</label>
									<label class="select">
										<?php echo form_dropdown('size', $params['size'], $settings['width'].'-'.$settings['height'], 'id="size"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">ISO</label>
									<label class="select">
										<?php echo form_dropdown('iso', $params['iso'], $settings['iso'], 'id="iso"'); ?><i></i> </label>
								</section>
								<section class="col col-6">
									<label class="label">Quality %</label>
									<label class="select">
										<?php echo form_dropdown('quality', $params['quality'], $settings['quality'], 'id="quality"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Effect</label>
									<label class="select">
										<?php echo form_dropdown('imxfx', $params['imxfx'], $settings['imxfx'], 'id="imxfx" class="form-control"'); ?><i></i> </label>
								</section>
								
								<section class="col col-6">
									<label class="label">Flip</label>
									<label class="select">
										<?php echo form_dropdown('flip', $params['flip'], $settings['flip'], 'id="flip" class="form-control"'); ?><i></i> </label>
								</section>
								
							</fieldset>

						</div>

					</div>
				</div>

			</div>

			<div class="tab-pane fade in" id="tab-settings">

				<div class="row">
					<div class="col-sm-12">
						<div class="smart-form">
							<fieldset>
								<section class="col col-6">
									<label class="label">Brightness</label>
									<label class="select">
										<?php echo form_dropdown('brightness', $params['brightness'], $settings['brightness'], 'id="brightness"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Contrast</label>
									<label class="select">
										<?php echo form_dropdown('contrast', $params['contrast'], $settings['contrast'], 'id="contrast"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Sharpness</label>
									<label class="select">
										<?php echo form_dropdown('sharpness', $params['contrast'], $settings['sharpness'], 'id="sharpness"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Saturation</label>
									<label class="select">
										<?php echo form_dropdown('saturation', $params['contrast'], $settings['saturation'], 'id="saturation"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">AWB</label>
									<label class="select">
										<?php echo form_dropdown('awb', $params['awb'], $settings['awb'], 'id="awb"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">EV Comp.</label>
									<label class="select">
										<?php echo form_dropdown('ev_comp', $params['ev_comp'], $settings['ev'], 'id="ev_comp"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Exposure</label>
									<label class="select">
										<?php echo form_dropdown('exposure', $params['exposure'], $settings['exposure'], 'id="exposure"'); ?><i></i> </label>
								</section>

								<section class="col col-6">
									<label class="label">Rotation</label>
									<label class="select">
										<?php echo form_dropdown('rotation', $params['rotation'], $settings['rotation'], 'id="rotation"'); ?><i></i> </label>
								</section>
								
								<section class="col col-6">
									<label class="label">Metering</label>
									<label class="select">
										<?php echo form_dropdown('metering', $params['metering'], $settings['metering'], 'id="metering"'); ?><i></i> </label>
								</section>

							</fieldset>

						</div>
					</div>
				</div>

			</div>

		</div>
		
		<p>
			<button type="button" class="btn btn-default btn-block set-default">Default All</button>
		</p>

	</div>

</div>