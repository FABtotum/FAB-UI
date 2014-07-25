<div class="row">
	<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-print"></i>
			Create
			<span class="object-title">
				<?php echo $_object_name ?>
			</span>
			<span class="file-title">
				<?php echo $_file_name; ?>
			</span>
		</h1>
	</div>
	<div class="col-sx-1 col-sm-1 col-md-1 col-lg-1">
		<h1 class="pull-right">
			<i id="status-icon" class="fa  fa-cog fa-spin txt-color-green hide">
			</i>
		</h1>
	</div>
</div>
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
                    <!--
					<li data-target="#step3" class="<?php echo $_running ? '' : ''; ?>">
						<span class="badge">
							3
						</span>
						Prepare print
						<span class="chevron">
						</span>
					</li>
                    -->
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
						Printing
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
				<hr class="simple">
				
					<div id="wizard-buttons" class="actions text-align-right" style="<?php echo $_running == true ? 'display:none' : '';  ?>">
						<button id="btn-prev" type="button" class="btn btn-sm btn-primary btn-prev disabled">
							<i class="fa fa-arrow-left">
							</i>
							Prev
						</button>
						<button id="btn-next" type="button" class="btn btn-sm btn-success disabled" data-last="Finish">
							Next
							<i class="fa fa-arrow-right">
							</i>
						</button>
					</div>	
			</div>
		</div>
	</div>
</div>