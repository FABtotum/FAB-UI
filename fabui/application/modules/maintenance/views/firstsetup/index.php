<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-info animated fadeIn">
				 Before proceeding with the first setup wizard disable the <strong>door safety message</strong> by going to <i>Settings -> Hardware -> Safety</i>. Remember to click Save.
				<br>For your safety, make sure to enable it again once you completed the procedure
		</div>
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
						<!--
						<li data-target="#step2">
							<span class="badge">2</span> Head <span class="chevron"></span>
						</li>
						-->
						<li data-target="#step3">
							<span class="badge">2</span>Manual Bed Calibration<span class="chevron"></span>
						</li>
						<li data-target="#step4">
							<span class="badge">3</span>Nozzle Height Calibration<span class="chevron"></span>
						</li>
						<?php if($show_feeder): ?>
						<li data-target="#step5">
							<span class="badge">4</span>Engage Feeder<span class="chevron"></span>
						</li>
						<?php endif; ?>
						<li data-target="#step6">
							<span class="badge"><?php echo $show_feeder ? 5 : 4; ?></span>Finish<span class="chevron"></span>
						</li>
						
					</ul>
					
				</div>
				<div class="step-content">
					<form class="form-horizontal" id="fuelux-wizard" method="post">
						<?php echo $step1; ?>
						<?php //echo $step2; ?>
						<?php echo $step3; ?>
						<?php echo $step4; ?>
						<?php echo $step5; ?>
						<?php echo $step6; ?>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
			
			
		</div>
	</div>
</div>