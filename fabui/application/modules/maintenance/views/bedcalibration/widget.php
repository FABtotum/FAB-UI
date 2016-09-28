<div class="row">
	<div class="col-sm-12">
		<div class="row margin-top-10 padding-10 step-1">
			<div class="col-sm-6 text-center">
				<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance') ?>assets/img/bedcalibration/1.png" />
			</div>
			<div class="col-sm-6 text-center">
				<h1><span class="badge bg-color-blue txt-color-white">1</span></h1>
				<h2> This wizard will help you level the bed by setting
				<br>
				the calibration screws of the bed to the correct height.
				<br>
				Use the provided hex key and make sure the screws are almost halfway in.
				<br>
				Click "Start" when ready. </h2>
				<hr class="simple">
				<p class="text-center">
					<a href="javascript:void(0);" class="btn btn-primary btn-default  do-calibration "> Start</a>
				</p>
			</div>
		</div>

		<div class="row margin-top-10 padding-10 step-2" style="display:none;">		
			<div class="col-sm-6 text-center margin-bottom-10">
				<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance') ?>assets/img/bedcalibration/2.png" />
			</div>
			<div class="col-sm-6 ">
				<h1 class="text-center" ><span class="badge bg-color-blue txt-color-white">2</span></h1>
				<div class="result-response"></div>
				<hr class="simple">
				<p class="text-center margin-top-10">
					<a href="javascript:void(0);" class="btn btn-primary btn-default  do-calibration "> Calibrate again</a>
				</p>
			</div>
		</div>
	</div>
</div>