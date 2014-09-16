<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<div class="row">


				<div class="col-sm-3 col-xs-3">
					<div class="well-light well-sm text-center shortcut">
						<a href="<?php echo site_url("scan") ?>"><i
							class="fa fa-lg fa-fw fa-video-camera fa-4x"></i>
							<h1 class="hidden-xs">Scan</h1> </a>
					</div>

				</div>

				<div class="col-sm-3 col-xs-3">

					<div class="well-light well-sm text-center shortcut">

						<a href="<?php echo site_url("create") ?>"><i
							class="fa fa-lg fa-fw fa-flask fa-4x"></i>
							<h1 class="hidden-xs">Create</h1> </a>
					</div>


				</div>

				<div class="col-sm-3 col-xs-3">

					<div class="well-light well-sm text-center shortcut">

						<a href="<?php echo site_url("jog") ?>"><i
							class="fa fa-lg fa-fw fa-gamepad fa-4x"></i>
							<h1 class="hidden-xs">Jog</h1> </a>
					</div>


				</div>

				<div class="col-sm-3 col-xs-3">

					<div class="well-light well-sm text-center shortcut">

						<a href="<?php echo site_url("objectmanager") ?>"><i
							class="fa fa-lg fa-fw fa-puzzle-piece fa-4x"></i>
							<h1 class="hidden-xs">Object manager</h1> </a>
					</div>

				</div>


			</div>
		</div>
	</div>
</div>

<section id="widget-grid">
	<!-- row -->
	<div class="row">
		
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		
			<?php echo $widget_tasks; ?>

		</article>

        
        <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		
			<?php echo $widget_shortcut; ?>

		</article>
		
        
        
        
	</div>
	<!-- end row -->
</section>
