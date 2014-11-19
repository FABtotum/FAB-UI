<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-fa-wrench"></i> Maintenance <span> > Spool </span></h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			
			
			<div class="row">
		<div class="col-sm-12">
			<div class="fuelux">
				<div class="wizard">
					<ul class="steps">
						<li data-target="#step1" class="active">
							<span class="badge badge-info">1</span>Start<span class="chevron"></span>
						</li>
						<li data-target="#step2">
							<span class="badge">2</span>Manual Bed Calibration<span class="chevron"></span>
						</li>
						<li data-target="#step3">
							<span class="badge">3</span>Probe lenght Calibration<span class="chevron"></span>
						</li>
						<li data-target="#step4">
							<span class="badge">4</span>Engage Feeder<span class="chevron"></span>
						</li>
						<li data-target="#step5">
							<span class="badge">5</span>Finish<span class="chevron"></span>
						</li>
						
					</ul>
					<!--
					<div class="actions">
						<button type="button" class="btn btn-sm btn-primary btn-prev">
							<i class="fa fa-arrow-left"></i>Prev
						</button>
						<button type="button" class="btn btn-sm btn-success btn-next" data-last="Finish">
							Next<i class="fa fa-arrow-right"></i>
						</button>
					</div>
					-->

				</div>
				<div class="step-content">
					<form class="form-horizontal" id="fuelux-wizard" method="post">
						
						<?php echo $step1; ?>
						<?php echo $step2; ?>
						<?php echo $step3; ?>
						<?php echo $step4; ?>
						<?php echo $step5; ?>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
			
			
		</div>
	</div>
</div>