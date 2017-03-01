<?php if(isset($valid_head) && !$valid_head): ?>
	
	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-danger animated fadeIn">
				<i class="fa-fw fa fa-warning"></i>
				<strong>Error!</strong> Installed head not valid. Please <a class="txt-color-white" style="text-decoration: underline" href="<?php echo site_url('maintenance/head') ?>"><strong>install</a></strong> a valid <?php echo $head_type ?> head!
			</div>
		</div>
	</div>
	
<?php endif; ?> 
<div class="row">
	<div class="col-sm-12">
		<div class="well fuelux">
			<div class="wizard">
				<ul class="steps">
					<li data-target="#step1" class="<?php echo $_running ? '' : 'active'; ?>">
						<span class="badge badge-info">
							1
						</span>
						Choose Object
						<span class="chevron">
						</span>
					</li>
					<li data-target="#step2" class="<?php echo $_running ? '' : ''; ?>">
						<span class="badge">
							2
						</span>
						Choose File
						<span class="chevron">
						</span>
					</li>
					<li data-target="#step4" class="<?php echo $_running ? '' : ''; ?>">
						<span class="badge">
							3
						</span>
						Get ready
						<span class="chevron">
						</span>
					</li>
					<li data-target="#step5" class="<?php echo $_running ? ' active' : ''; ?>">
						<span class="badge">
							4
						</span>
						<?php echo $label; ?>
						<span class="chevron">
						</span>
					</li>
					<li data-target="#step6" class="<?php echo $_running ? '' : ''; ?>">
						<span class="badge">
							5
						</span>
						Finish
						<span class="chevron">
						</span>
					</li>
				</ul>
			</div>
			<div class="step-content">
				<form class="form-horizontal" id="fuelux-wizard" method="post">
					<hr class="simple">
					<!-- STEP 1 -->
					<?php echo $_step_1 ?>
					<!-- STEP 2 -->
					<?php echo $_step_2 ?>
					<!-- STEP 3 -->
					<?php //echo $_step_3; ?>
					<!-- STEP 4 -->
					<?php echo $_step_4; ?>
					<!-- STEP 5 -->
					<?php echo $_step_5; ?>
					<!-- STEP 6 -->
					<?php echo $_step_6; ?>
				</form>
				<?php if(isset($valid_head) && !$valid_head): ?>
				<?php else: ?>
				<hr class="simple">
					<div id="wizard-buttons" class="actions text-align-right" style="<?php echo $_running == true ? 'display:none' : '';  ?>">
						<button id="btn-prev" type="button" class="btn btn-sm btn-primary btn-prev disabled"><i class="fa fa-arrow-left"></i>&nbsp;Prev</button>&nbsp;
						<button id="btn-next" type="button" class="btn btn-sm btn-success disabled" data-last="Finish">Next&nbsp;<i class="fa fa-arrow-right"></i></button>
					</div>
				<?php endif; ?>
				
			</div>
		</div>
	</div>
</div>
<!-- DEBUG MODAL -->
<div class="modal fade" id="debugModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-times-circle text-danger "></i> Ooops, something went wrong!</h4>
			</div>
				<div class="modal-body custom-scroll terms-body" id="debugModalBody">
					<p>Details:<p>
					<div id="modalDebugContent"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>