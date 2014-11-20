<div class="step-pane" id="step2">
	<br />
	<div class="row" id="step2-1">
		<div class="col-sm-12">
			<h1 class="text-center text-primary" ><span class="badge bg-color-blue txt-color-white" style="vertical-align: middle;">2</span> <strong>Bed Calibration</strong></h1>
		</div>
		<div class="col-sm-12">
			<div class="well">
				<div class="row">
					<div class="col-sm-6 text-center">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo base_url() ?>application/modules/settings/assets/img/maintenance/bed-calibration/1.png" />
					</div>
					<div class="col-sm-6 text-center">
						<!--<h1><span class="badge bg-color-blue txt-color-white">1</span></h1>-->
						<h2> This manual procedure will help you level the bed by setting
						<br />
						the calibration screws of the bed to the correct height.
						<br>
						Use the provided hex key and make sure the screws are almost halfway in.
						<br />
						Click "<strong>Start</strong>" when ready </h2>
						
						
						<a href="javascript:void(0);" class="btn btn-sm btn-primary btn-prev "><i class="fa fa-arrow-left"></i> Prev</a>
						<a href="javascript:void(0);" class="btn btn-sm btn-success do-calibration" style="margin-left: 5px;"> Start</a>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row margin-top-10" style="display:none;" id="step2-2">

		<div class="col-sm-12">
			<div class="well">

				<div class="row">
					<div class="col-sm-6 text-center margin-bottom-10">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo base_url() ?>application/modules/settings/assets/img/maintenance/bed-calibration/2.png" />
					</div>
					<div class="col-sm-6 text-center">
						
						<h4 class="text-center">Screw or unscrew following the indication given for each point.
						<br>
						Green points are optimally leveled.
						<br>
						Always follow the order without skipping any point (even green ones) and repeat until all the points are green.Arrows show the direction (CW or CCW) as seen from above.</h4>
						<div class="todo margin-top-10"></div>
						<hr>
						
							<a href="javascript:void(0);" class="btn btn-sm btn-primary btn-prev "><i class="fa fa-arrow-left"></i> Prev</a>
							<a href="javascript:void(0);" class="btn btn-primary btn-warning do-calibration" style="margin-left: 5px;"> Calibrate again</a>
							<a href="javascript:void(0);" class="btn btn-sm btn-success bed-leveling-next btn-next" style="margin-left: 5px;"> Next <i class="fa fa-arrow-right"></i></a>
						
					</div>
				</div>

			</div>
		</div>
	</div>
	
</div>
