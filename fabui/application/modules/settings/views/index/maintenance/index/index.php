<div class="tab-pane active fade in">

	<div class="row margin-top-10">

		<div class="col-sm-3">

			<div class="well no-border text-center">

				<a href="<?php echo site_url('settings/maintenance/spool'); ?>" class="btn btn-default  btn-lg"><i class="fa fa-circle-o-notch"></i> Spool</a>
				<h5>Load and unload spool </h5>
				<small>(require manual intervention)</small>

			</div>

		</div>

		<div class="col-sm-3">
			<div class="well no-border text-center">

				<a href="<?php echo site_url('settings/maintenance/feeder'); ?>" class="btn btn-default btn-lg"><i class="fa fa-cog"></i> Feeder</a>
				<h5>Engage feeder</h5>
				<small>(require manual intervention)</small>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="well no-border text-center">

				<a href="<?php echo site_url('settings/maintenance/self-test'); ?>" class="btn btn-default btn-lg"><i class="fa fa fa-magic"></i> Self Test</a>
				<h5> On-board diagnostics</h5>
				<small>self-diagnostic and reporting capability</small>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="well no-border text-center">

				<a href="<?php echo site_url('settings/maintenance/probe-calibration'); ?>" class="btn btn-default btn-lg"><i class="fa fa-crosshairs"></i> Probe Calibration</a>
				<h5>Probe calibration description</h5>
				<small>(require manual intervention)</small>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-3">

			<div class="well no-border text-center">

				<a href="<?php echo site_url('settings/maintenance/bed-calibration'); ?>" class="btn btn-default  btn-lg"><i class="fa fa-arrows-h"></i> Bed Calibration</a>
				<h5>Assisted bed calibration </h5>
				<small>(require manual intervention)</small>

			</div>

		</div>
	</div>

</div>

