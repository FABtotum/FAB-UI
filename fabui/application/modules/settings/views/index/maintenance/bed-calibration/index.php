<div class="tab-pane active fade in">
	
	<div class="row margin-top-10 step-1">
		
		<div class="col-sm-12 text-center margin-bottom-10">
			
			<div class="row">
					<div class="col-sm-6 text-center">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo base_url() ?>application/modules/settings/assets/img/maintenance/bed-calibration/1.png" />
					</div>
					<div class="col-sm-6 ">
						<h1><span class="badge">1</span></h1>
						<h2>
							This wizard will help you level the bed by setting <br>the calibration screws of the bed to the correct height.
							<br>Use the provided hex key and make sure the screws are almost halfway in.
							<br>Click "Start" when ready
						</h2>
						<hr>
						<p class="text-center">
							<a href="javascript:void(0);" class="btn btn-default btn-lg do-calibration "> Start</a>
						</p>
					</div>
				</div>			
			
				
		</div>
		
	</div>
	
	
	
	<div class="row margin-top-10 step-2" style="display:none;">
		
		
		<div class="col-sm-12">
			<div class="well">

				<div class="row">
					<div class="col-sm-6 text-center margin-bottom-10">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo base_url() ?>application/modules/settings/assets/img/maintenance/bed-calibration/2.png" />
					</div>
					<div class="col-sm-6 ">
						<h1 class="text-center" ><span class="badge">2</span></h1>
						<h4 class="text-center">Screw or unscrew following the indication given for each point.<br>
					Green points are optimally leveled.<br>
					Always follow the order without skipping any point (even green ones) and repeat until all the points are green.Arrows show the direction (CW or CCW) as seen from above.</h4>
						<div class="todo margin-top-10"></div>
						<hr>
						<p class="text-center">
							<a href="javascript:void(0);" class="btn btn-default btn-lg do-calibration "> Calibrate again</a>
						</p>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>