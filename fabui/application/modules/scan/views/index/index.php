<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-scan"></i> Scan

		</h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">

		<div class="well fuelux">

			<div class="wizard">
				<ul class="steps">
					<li data-target="#step1" class="<?php echo $_task && $_task_attributes['step'] == 1 ? 'active': '' ?> <?php echo  !$_task  ? 'active': '' ?>">
                        <span class="badge badge-info">1</span> Mode <span class="chevron"></span>
					</li>
					<li data-target="#step2" class="<?php echo  $_task && $_task_attributes['step'] == 2 ? 'active': '' ?>">
                        <span class="badge badge-info">2</span> Settings <span class="chevron"></span>
					</li>
					<li data-target="#step3" class="<?php echo  $_task && $_task_attributes['step'] == 3 ? 'active': '' ?>">
                    <span class="badge badge-info">3</span> Get ready <span class="chevron"></span>
					</li>
					<li data-target="#step4" class="<?php echo  $_task && $_task_attributes['step'] == 4 ? 'active': '' ?>">
                        <span class="badge badge-info">4</span> Scan <span class="chevron"></span></li>
					<li data-target="#step5" class="<?php echo  $_task && $_task_attributes['step'] == 5 ? 'active': '' ?>">
                        <span class="badge badge-info">5</span> End <span class="chevron"></span>
					</li>
                    <!--
					   <li data-target="#step6" class="<?php echo  $_task && $_task_attributes['step'] == 6 ? 'active': '' ?>">
                        <span class="badge badge-info">6</span> End <span class="chevron"></span>
					   </li>
                    -->
				</ul>
			</div>

			<div class="step-content">

				<form class="form-horizontal" id="fuelux-wizard" method="post">

					<hr class="simple" />

					<!-- STEPS -->
					<?php echo $_step_1; ?>

					<?php echo $_step_2; ?>

					<?php echo $_step_3; ?>

					<?php echo $_step_4; ?>

					<?php echo $_step_5; ?>

					<?php //echo $_step_6; ?>

					<hr class="simple" />

				</form>

				<!-- BUTTONS -->
				<div id="wizard-buttons" class="actions text-align-right">
					<button id="btn-prev" type="button" class="btn btn-sm btn-primary btn-prev "> <i class="fa fa-arrow-left"></i> Prev </button>&nbsp;
					<button id="btn-next" type="button" class="btn btn-sm btn-success" data-last="Finish"> Next <i class="fa fa-arrow-right"></i> </button>
				</div>

			</div>

		</div>


	</div>
</div>
<div id="loading-modal" class="mfp-hide white-popup-block">

	<p style="text-align:center;">Loading</p>

</div>
