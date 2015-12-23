<div class="step-pane" id="step2">
	<br />
	<div class="row">
		<div class="col-sm-12">
			<h1 class="text-center text-primary" ><span class="badge bg-color-blue txt-color-white" style="vertical-align: middle;">2</span><strong> Head</strong></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="well">
				<div class="row">
					<div class="col-sm-6">
						<div class="smart-form">
							<fieldset style="background: none !important;">
								<section>
									<label class="label">Please select which head you want to install</label>
									<label class="select">
										<?php echo form_dropdown('heads', $heads_options, '', 'class="input-lg" id="heads"'); ?><i></i> 
									</label>
								</section>
							</fieldset>
						</div>
					</div>
					<div class="col-sm-6 text-center">
						<img id="head_img" style="width: 50%; display: inline; cursor:default;" class="img-responsive" src="<?php echo module_url('maintenance') . 'assets/img/head/head_shape.png'; ?>">
					</div>
				</div>
			</div>
			<p class="pull-right">
				<a href="javascript:void(0);" class="btn btn-sm btn-primary btn-prev "><i class="fa fa-arrow-left"></i> Prev</a>
				<a class="btn btn-sm btn-success btn-next" style="margin-left: 5px;">Next <i class="fa fa-arrow-right"></i></a>
			</p>
		</div>
	</div>
</div>
