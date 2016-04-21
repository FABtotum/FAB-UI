<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<div class="row margin-bottom-10">
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<p class="font-md">Make sure you removed the filament, milling bits and any other accessory on the head.<br>Se also <a href="<?php echo site_url('maintenance/spool') ?>">spool maintenance</a></p>
						</div>
					</div>	
					<div class="smart-form">
						<fieldset style="background: none !important;">
							<section>
								<label class="label font-md">Please select which head you want to install </label><label class="select"> <?php echo form_dropdown('heads', $heads_list, $head, 'class="input-lg" id="heads"'); ?> <i></i> </label>
							</section>
						</fieldset>
					</div>
					
					<div class="row" style="margin-top:-30px">
						<div class="col-sm-12">
							<div class="smart-form">
								<fieldset style="background: none !important;">
									<div id="description-container">
										<?php if($head != 'head_shape'): ?>
											<div class="jumbotron">
											<p class="margin-bottom-10 "><?php echo $heads_descriptions[$head]['desc'] ?></p>
											<?php if($heads_descriptions[$head]['more'] != ''): ?>
											<a style="padding: 6px 12px;" target="_blank" href="<?php echo $heads_descriptions[$head]['more']; ?>" class="btn btn-default ">More details</a>
											</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 text-center image-container">
					<a target="_blank" href="javascript:void(0);"><img id="head_img" style="width: 50%; display: inline; cursor:default;" class="img-responsive" src="<?php echo module_url('maintenance') . 'assets/img/head/'.$head.'.png?'.time(); ?>"></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="widget-footer">
	<span><i class="fa fa-warning"></i> Before clicking "Install", make sure the head is properly locked in place </span>
	<button class="btn btn-primary" style="margin-left:10px;" type="button" id="set-head">Install</button>
</div>
