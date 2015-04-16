<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-fa-wrench"></i> Maintenance <span> > Self Test </span></h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			
			<div class="row">
				<div class="col-sm-12">
					<h5>The Self Test procedure will test all the machine functionalities. </h5>
					<h5>Temperature tests may fail if the head or the bed are not properly positioned</h5>
				</div>
			</div>

			<div class="row margin-top-10">

				<div class="col-sm-12">

					<a id="start" class="btn btn-default btn-primary" href="javascript:void(0);"> <i class="fa fa-magic"></i> Start test</a>

					<label style="margin-left:20px;" class="checkbox-inline">
						<input type="checkbox" class="checkbox" id="send-report" />
						<span>Send report to Fabtotum Remote Support </span> </label>

				</div>

			</div>

			<div class="row margin-top-10">

				<div class="col-md-12">
					<pre class="console"  style="height: 400px; display:none; overflow: auto;"><?php echo $running ? $trace_content : '' ?></pre>
				</div>

			</div>

		</div>
	</div>
</div>