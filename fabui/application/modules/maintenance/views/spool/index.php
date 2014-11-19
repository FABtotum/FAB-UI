<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-fa-wrench"></i> Maintenance <span> > Spool - Load and unload spool</span> </h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">

			<div class="row margin-top-10 choice">

				<div class="col-sm-6">
					<div class="well">
						<h3 class="text-center text-primary">Load Filament</h3>
						<h5 class="text-center">Automatically load the filament into the machine</h5>
						<h2 class="text-center"><a data-action='load' href="javascript:void(0);" class="btn btn-default btn-primary btn-circle choice-button"><i class="fa fa-chevron-down"></i></a></h2>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="well">
						<h3 class="text-center text-primary">Unload Filament</h3>
						<h5 class="text-center">Automatically unload the filament from the machine</h5>
						<h2 class="text-center"><a data-action='unload' href="javascript:void(0);" class="btn btn-default btn-primary btn-circle  choice-button"><i class="fa fa-chevron-down"></i></a></h2>
					</div>
				</div>
			</div>

			<div class="row margin-top-10 re-choice" style="display: none;">

				<div class="col-sm-12">
					<h2 class="text-center"><a data-action='unload' href="javascript:void(0);" class="btn btn-default btn-primary btn-circle   re-choice-button"><i class="fa fa-chevron-up"></i></a></h2>
				</div>

			</div>

			<div class="row margin-top load-choice" style="display: none;">

				<div class="col-sm-6">

					<div class="well">

						<div class="row">

							<div class="col-sm-6 text-center">
								<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance').'assets/img/spool/open-cover.png' ?>" />
							</div>

							<div class="col-sm-6 text-center">
								<h1><span class="badge bg-color-blue txt-color-white">1</span></h1>
								<h1 class="text-primary">Open the right cover panel</h1>
								<h2>Side covers are attached to main body magnetically. Pull the left side panel confidently in order to gain access to spool space inside</h2>
							</div>

						</div>

					</div>

				</div>

				<div class="col-sm-6">

					<div class="well">

						<div class="row">

							<div class="col-sm-6 text-center">
								<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance').'assets/img/spool/insert-filament.png' ?>" />
							</div>

							<div class="col-sm-6 text-center">
								<h1><span class="badge bg-color-blue txt-color-white">2</span></h1>
								<h1 class="text-primary">Insert the filament</h1>
								<h2>Side covers are attached to main body magnetically. Pull the left side panel confidently in order to gain access to spool space inside</h2>
							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row margin-top unload-choice" style="display: none;">

				<div class="col-sm-6">

					<div class="well">

						<div class="row">

							<div class="col-sm-6 text-center">
								<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance').'assets/img/spool/open-cover.png' ?>" />
							</div>

							<div class="col-sm-6 text-center">
								<h1><span class="badge bg-color-blue txt-color-white">1</span></h1>
								<h1 class="text-primary">Open the right cover panel</h1>
								<h2>Side covers are attached to main body magnetically. Pull the left side panel confidently in order to gain access to spool space inside</h2>
							</div>

						</div>

					</div>

				</div>

				<div class="col-sm-6">

					<div class="well">

						<div class="row">

							<div class="col-sm-6 text-center">
								<img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('maintenance').'assets/img/spool/unload-filament.png' ?>" />
							</div>

							<div class="col-sm-6 text-center">
								<h1><span class="badge bg-color-blue txt-color-white">2</span></h1>
								<h1 class="text-primary">Unload filament</h1>
								<h2>Press start and assist the filament by gently pulling it out of the tube until the procedure has been completed</h2>
							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row margin-top-10 start" style="display: none;">

				<div class="col-sm-12">
					<h2 class="text-center"><a  href="javascript:void(0);" class="btn btn-default btn-primary   start-button">Start</a></h2>
				</div>

			</div>

			<div class="row margin-top title" style="display: none;">

				<div class="col-sm-12">
					<h2 class="text-center"></h2>
				</div>

			</div>

			<div class="row margin-top-10 trace">

				<div class="col-md-12">
					<pre id="console" style="height:300px;display:none;overflow:auto"></pre>
				</div>

			</div>

		</div>
	</div>
</div>