<ul id="myTab1" class="nav nav-tabs bordered">
	<li class="active">
		<a href="#live-feeds" data-toggle="tab" aria-expanded="true"> <i class="fa fa-fw fa-lg fa-bar-chart"></i> Live Feeds</a>
	</li>
	<li class="disabled controls-tab">
		<a href="#controls" data-toggle=""  aria-expanded="false"><i class="fa fa-fw fa-lg fa-sliders"></i> Controls</a>
	</li>
	
	<li class="pull-right">
		<a href="javascript:void(0);" data-action="stop" id="stop-button" class="stop txt-color-red"> <i class="fa fa-fw fa-lg fa-times-circle"></i> Cancel Print</a>
	</li>
	
</ul>
<div id="myTabContent1" class="tab-content padding-10">

	<div class="tab-pane fade active in" id="live-feeds">

		<div class="row padding-10">
			
			

			<div class="col-sm-4 stats-well">
				
				<!-- PROGRESS -->
				<p>Progress <span class="pull-right progress-status font-md"></span></p>
				<div class="progress progress-sm progress-striped active">
					<div id="lines-progress" class="progress-bar bg-color-blue"></div>
					
				</div>
				
				<!-- LAYERS -->
				<p class="additive-print">Layer <span class="pull-right"><span class="layer-actual font-md"><?php echo $layer_actual; ?></span> / <span class="layer-total"><?php echo $layer_total; ?></span> <span class="layer-percent"></span> </span></p>
				<div class="progress progress-xs progress-striped active additive-print">
					<div class="progress-bar  bg-color-blue progress-layer"></div>
				</div>
				
				<p>Speed <span class="pull-right"><span class="label-velocity font-md"><?php echo $_velocity == '' ? 100 : $_velocity ?></span> / 500%</span> </p>
				<div class="progress progress-xs">
					<div class="progress-bar  bg-color-blue speed-progress" style="width: <?php echo $_velocity == '' ? ((100/500)*100) : (($_velocity/500)*100) ?>% "></div>
				</div>
				
				
				<p class="additive-print">Flow rate<span class="pull-right"> <span class="label-flow-rate font-md"><?php echo $flow_rate == '' ? 100 : $flow_rate; ?></span> / <span>500 %</span>  </span></p>
				<div class="progress progress-xs additive-print">
					<div class="progress-bar  bg-color-blue flow-rate-progress" style="width:<?php echo $flow_rate == '' ? ((100/500)*100) : (($flow_rate/500)*100) ?>% "></div>
				</div>
				
				<p class="additive-print">Fan <span class="pull-right"> <span class="label-fan font-md"><?php echo $fan == '' ? ((0/255)*100) : (($fan/255)*100) ?></span>  </span></p>
				<div class="progress progress-xs additive-print">
					<div class="progress-xs">
						<div class="progress-bar bg-color-blue fan-progress" style="width: <?php echo $fan == '' ? ((0/255)*100) : (($fan/255)*100) ?>%"></div>
					</div>
				</div>
				
				
				<p class="subtractive-print">RPM <span class="pull-right"><span class="label-rpm font-md"><?php echo $_rpm == '' ? 6000 : $_rpm ?></span><span> / 14000</span></span></p>
				<div class="progress progress-xs subtractive-print">
					<div class="progress-bar  bg-color-blue rpm-progress" style="width: <?php echo $_rpm == '' ? ((6000/14000)*100) : (($_rpm/14000)*100) ?>% "></div>
				</div>
				
				<hr class="simple">
				<p>Elapsed Time <span class="pull-right"> <span class="elapsed-time"></span> </span> </p>
				<p>Time left <span class="pull-right"> <span class="estimated-time-left"></span> </span> </p>
				
				
			</div>
			
			<div class="col-sm-4 additive-print">
				<h5 class="text-center"><i class="fab-lg fab-fw icon-fab-term "></i> Nozzle (<span class="nozzle-temperature"></span>)</h5>
				<div id="nozzle-chart" class="chart"> </div>
			</div>
			
			<div class="col-sm-4 additive-print">
				<h5 class="text-center"><i class="fab-lg fab-fw icon-fab-term "></i> Bed (<span class="bed-temperature"></span>)</h5>
				<div id="bed-chart" class="chart"> </div>
			</div>
			
			
		</div>
		
		<!-- TEMP GRAPHS -->
		<div class="row padding-10">
			<div class="col-sm-12">
				<pre class="console" id="ace-editor" style="height: 250px;"></pre>
			</div>
			
		</div>
		
		<!-- CONSOLE -->
		<div class="row">
			
		</div>
	</div>
	
	<!-- CONTROLS -->
	<div class="tab-pane fade" id="controls">
		
		
		<div class="row">	
			<div class="col-sm-12">
				<div class="well">
					<div class="row">	
						<div class="col-sm-2 additive-print">
							<a href="javascript:void(0);" class="btn btn-default btn-block controls" data-action="zup" title="Change Z height: + 0.1mm" rel="tooltip"><i class="fa fa-angle-double-down"></i>&nbsp;Z</a>
						</div>
						<div class="col-sm-2 additive-print">
							<a href="javascript:void(0);" class="btn btn-default btn-block controls" data-action="zdown" title="Change Z height: - 0.1mm" rel="tooltip"><i class="fa fa-angle-double-up"></i>&nbsp;Z</a>
						</div>		
					</div>
				</div>
			</div>
		</div>
		
		<div class="row additive-print">	
			<div class="col-sm-6">
				<div class="well">
					<span class="text"> <i class="fab-lg fab-fw icon-fab-term "></i> Nozzle
						<span class="pull-right">
							<label id="label-temp1-target" class="label label-info pull-right"><?php echo intval($_temperature_target).'&deg;' ?></label>
							<label id="label-temp1" class="label label-danger pull-right margin-right-5"><?php echo intval($_temperature) . '&deg;'; ?></label>  
						</span>
					</span>
					<div id="act-ext-temp" class="noUiSlider margin-top-10"></div>
					<div id="temp1" data-action="temp1" class="sliders extruder-range margin-bottom-10"></div>
					<div class="margin-top-40"></div>
				</div>
			</div>
			
			
			<div class="col-sm-6">
				<div class="well">
					<span class="text"> <i class="fab-lg fab-fw icon-fab-term "></i> Bed
						<span class="pull-right">
							<label id="label-temp2-target" class="label label-info pull-right"><?php echo intval($_temperature_target).'&deg;' ?></label>
							<label id="label-temp2" class="label label-danger pull-right margin-right-5"><?php echo intval($_temperature) . '&deg;'; ?></label>  
						</span>
					</span>
					<div id="act-bed-temp" class="noUiSlider margin-top-10"></div>
					<div id="temp2" data-action="temp2" class="sliders bed-range margin-bottom-10"></div>
					<div class="margin-top-40"></div> 
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-4 speed-well">
				<div class="well">
					<span class="text">Speed
						<span class="pull-right">
							<label  class="label label-warning label-velocity"><?php echo $_velocity == '' ? 100 : $_velocity ?>%</label>
						</span>
					</span>
					<div class="margin-top-10"></div>
					<div id="velocity" data-action="velocity" class="sliders speed-range margin-bottom-10"></div>
					<div class="margin-top-40"></div>
					<div class="margin-top-10"></div>
				</div>
			</div>
			
			<div class="col-sm-4 additive-print">
				<div class="well">
					<span>Flow rate
						<span class="pull-right">
							<label class="label label-warning label-flow-rate"><?php echo $flow_rate == '' ? 100 : $flow_rate;   ?>%</label>
						</span>
					</span>
					<div class="margin-top-10"></div>
					<div id="flow-rate" data-action="flow-rate" class="sliders flow-rate-range margin-bottom-10"></div>
					<div class="margin-top-40"></div>
				</div>
			</div>
			
			<div class="col-sm-4 additive-print">
				<div class="well">
					<span>Fan
						<span class="pull-right">
							<label  class="label label-warning label-fan"></label>
						</span>
					</span>
					<div class="margin-top-10"></div>
					<div id="fan" data-action="fan" class="sliders fan-range margin-bottom-10 bg-color-teal" ></div>
					<div class="margin-top-40"></div>
				</div>
			</div>
			
			<div class="col-sm-6 rpm-well subtractive-print">
				<div class="well">
					<span>RPM
						<span class="pull-right">
							<label class="label label-warning label-rpm pull-right"><?php echo $_rpm == '' ? 100 : $_rpm ?></label>
						</span>	
					</span>
					<div class="margin-top-10"></div>
					<div id="rpm" data-action="rpm" class="sliders rpm-range margin-bottom-10"></div>
					<div class="margin-top-40"></div>
				</div>
			</div>
			
		</div>
		
	</div>

</div>