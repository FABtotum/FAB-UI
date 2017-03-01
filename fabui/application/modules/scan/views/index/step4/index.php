<div class="step-pane <?php echo  ($_task && $_task_attributes['step'] == 4) || ($_task &&  $_task_attributes['step'] == 0) ? 'active': '' ?> " id="step4">

	<ul id="myTab1" class="nav nav-tabs bordered">
		<li class="active">
			<a href="#live-feeds" data-toggle="tab" aria-expanded="true"> <i class="fa fa-fw fa-lg fa-bar-chart"></i> Live Feeds</a>
		</li>

		<li class="pull-right">
			<a href="javascript:void(0);" data-action="stop" id="stop-button" class="stop txt-color-red"> <i class="fa fa-fw fa-lg fa-times-circle"></i> Cancel Scan</a>
		</li>

	</ul>

	<div id="myTabContent1" class="tab-content padding-10">

		<div class="tab-pane fade active in" id="live-feeds">

			<div class="row padding-10">

				<div class="col-sm-8 stats-well">
					<!-- PROGRESS -->
					<p>
						Scan <span class="pull-right progress-status font-md"></span>
					</p>
					<div class="progress progress-sm progress-striped active">
						<div id="lines-progress" class="progress-bar bg-color-blue"></div>

					</div>
					
					<p class="pprocess">
						Post-processing <span class="pull-right pprocess-progress-status font-md"></span>
					</p>
					
					<div class="progress progress-sm progress-striped active pprocess">
				        <div id="pprocess-lines-progress" class="progress-bar bg-color-redLight"></div>
				    </div>

					<hr class="simple" />
					<p>
						Elapsed Time <span class="pull-right"> <span class="elapsed-time"></span> </span>
					</p>
					<p>
						Time left <span class="pull-right"> <span class="estimated-time-left"></span> </span>
					</p>

				</div>
				
				<div class="col-sm-4 stats-well">					
					<p>Scan Mode: <span class="stats-scan-mode-name pull-right"></span></p>
					<hr>
					<p class="scan">quality: <span class="stats-scan-quality-name pull-right"></span></p>
					<p class="scan">slices: <span class="stats-scan-quality-slices pull-right"></span></p>
					<p class="scan">resolution: <span class="stats-scan-quality-resolution pull-right"></span></p>
					<p class="scan">iso: <span class="stats-scan-quality-iso pull-right"></span></p>
					<p class="pprocess">quality <span class="pprocess-quality-name"></span> </p>
					
					
				</div>

			</div>

		</div>

	</div>

</div>