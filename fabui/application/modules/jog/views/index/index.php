<!-- TITLE 
<div class="row">
	<div class="col-xs-4 col-sm-4">
		<h1 class="page-title txt-color-blueDark"><i class="icon-fab-jog fab-fw"></i> Jog</h1>
	</div>
	<div class="col-xs-8 col-sm-8 text-right">
		<a id="reset-controller" class="btn btn-info " href="javascript:void(0)"  title="Reset Controller">Reset controller</a>
	</div>
</div>
-->
<!-- TEMPERATURES -->
<div class="row">
	<!-- EXTRUDER TEMP -->
	<?php if($max_temp > 0): ?>
	<div class="col-sm-6">
		<div class="well">
			
			<div class="row">
				<div class="col-sm-12">
					<h5><a rel="tooltip" data-placement="right" data-original-title="Get Nozzle temperature" class="btn btn-default refresh-temperature" href="javascript:void(0);"><i class="fab-lg fab-fw icon-fab-term txt-color-red"></i> Extruder</a><span id="ext-degrees" class="label label-info pull-right"></span>&nbsp;<span id="ext-actual-degrees" class="label label-danger pull-right margin-right-5"></span></h5>
					<div id="act-ext-temp" class="noUiSlider"></div>
					<div id="ext-target-temp" class="noUiSlider extruder-range"></div>
					<p class="font-md">&nbsp;</p>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- BED TEMP -->
	<div class="col-sm-<?php echo $max_temp == 0 ? '12': '6'; ?>">
		<div class="well">
			<h5><a rel="tooltip" data-placement="right" data-original-title="Get Bed temperature" class="btn btn-default refresh-temperature" href="javascript:void(0);"><i class="fab-lg fab-fw icon-fab-term txt-color-red"></i>Bed</a><span id="bed-degrees" class="label label-info pull-right"></span><span id="bed-actual-degrees" class="label label-danger pull-right margin-right-5"></span></h5>
			<div id="act-bed-temp" class="noUiSlider"></div>
			<div id="bed-target-temp" class="noUiSlider bed-range"></div>
			<p class="font-md">
				&nbsp;
			</p>
		</div>
	</div>
</div>

<!-- JOG -->
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<div class="row">
				<!-- STEP, FEEDRATE -->
				<div class="col-sm-3">
					
					<div class="smart-form">
						<fieldset style="background: none !important">
							
							<div class="row">
								<section class="col col-6">
									<a rel="tooltip" data-placement="top" data-original-title="Tooltip Left" href="javascript:void(0);" data-action="off" type="button" class="btn btn-info btn-sm btn-block fan">Fan Off</a>
								</section>
								<section class="col col-6">
									<a rel="tooltip" href="javascript:void(0);" data-action="on" type="button" class="btn btn-info btn-sm btn-block fan">Fan On</a>
								</section>
							</div>
							
							<section>
								<label class="input">
									<input id="step" type="text" value="10"  />
								</label>
								<p class="note">XY Step (mm)</p>
							</section>
							<section>
								<label class="input">
									<input id="feedrate" type="text" value="1000" />
								</label>
								<p class="note">Feedrate</p>
							</section>
							<section>
								<label class="input">
									<input id="z-step" type="text" value="5" />
								</label>
								<p class="note">Z Step (mm)</p>
							</section>
						</fieldset>
					</div>
					
					<!--
					<div class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<div class="col-md-5 control-label">
									<strong>XY Step (mm)</strong>
								</div>
								<div class="col-md-7">
									<input  type="text" id="step" value="10">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-5 control-label">
									<strong>Feedrate</strong>
								</div>
								<div class="col-md-7">
									<input type="text" id="feedrate" value="1000">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-5 control-label">
									<strong>Z Step (mm)</strong>
								</div>
								<div class="col-md-7">
									<input  type="text" id="z-step" value="5">
								</div>
							</div>
						</fieldset>
					</div>
					-->
					<hr class="visible-xs">
				</div>
				
				

				<!-- JOG DIRECTIONS -->
				<div class="col-sm-6 text-center margin-bottom-20">
					<div class="btn-group-vertical">
						<a  href="javascript:void(0)" data-attribue-direction="up-left"  data-attribute-keyboard="103" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left fa-1x fa-rotate-45"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="left"      data-attribute-keyboard="100" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left "> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-down fa-rotate-45 "> </i> </a>
					</div>
					<div class="btn-group-vertical">
						<a href="javascript:void(0)" data-attribue-direction="up"   data-attribute-keyboard="104" class="btn btn-default btn-circle btn-xl jog directions btn-xl "> <i class="fa fa-arrow-up fa-1x"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-default btn-circle btn-xl jog zero_all "> <i class="fa fa-bullseye"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-down "> </i> </a>
					</div>
					<div class="btn-group-vertical">
						<a href="javascript:void(0)" data-attribue-direction="up-right"   data-attribute-keyboard="105" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-up fa-1x fa-rotate-45"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="right"      data-attribute-keyboard="102" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right fa-rotate-45"> </i> </a>
					</div>
					<div class="btn-group-vertical" style="margin-left: 10px;">
						<a rel="tooltip" data-placement="right" data-original-title="Move Z Up" href="javascript:void(0)"  class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zdown"> <i class="fa fa-angle-double-up"> </i>&nbsp;Z </a>
						<hr/>
						<a rel="tooltip" data-placement="right" data-original-title="Move Z Down" href="javascript:void(0)" class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zup"> <i class="fa fa-angle-double-down"> </i>&nbsp; Z </a>
					</div>
					
				</div>
				<!-- AXIS MODE, EXTRUDER MODE -->
				<div class="col-sm-3">

					<div class="tab-content">

						<ul id="internal-tab-1" class="nav nav-tabs tabs-pull-right">
							<li class="active">
								<a href="#modeb" data-toggle="tab" data-mode="e" class="extruder-mode"><i class="fab-lg fab-fw icon-fab-e fab-2x"></i></a>
							</li>
							<li>
								<a href="#modea" data-toggle="tab" data-mode="a" class="extruder-mode"><i class="fab-lg fab-fw icon-fab-a fab-2x"></i></a>
							</li>
							<li class="pull-left">
								<span>Mode:</span> <span class="mode">Extruder</span>
							</li>
						</ul>
						<div class="tab-content padding-10">
							<!-- A MODE -->
							<div class="tab-pane fade in " id="modea">
								<div class="knobs-demo  text-center" id="mode-a">
									<input value="1" class="knob" data-width="200" data-cursor="true" data-step="0.5" data-min="1" data-max="360" data-thickness=".3" data-fgColor="#A0CFEC" data-displayInput="true">
								</div>
							</div>
							<!-- B MODE -->
							<div class="tab-pane fade in active" id="modeb">
								<div class="row">
									<div class="smart-form" >
										<fieldset style="background: none !important">
											
											<div class="row">
												<section class="col col-3" >
													<button data-action="-" type="button" class="btn btn-info btn-sm btn-block extruder-e-action">
														<i class="fa fa-minus"></i>
													</button>
												</section>
												<section class="col col-6 text-center">
													<label class="input">
														<input id="extruder-e-value" type="text" style="text-align: center;" value="10" />
													</label>
													<p class="note">Step (mm)</p>
												</section>
												<section class="col col-3" >
													<button data-action="+" type="button" class="btn btn-info btn-sm btn-block extruder-e-action">
														<i class="fa fa-plus"></i>
													</button>
												</section>
											</div>
											<div class="row">
												<section class="col col-3 text-center">
												</section>
												<section class="col col-6 text-center">
													<label class="input">
														<input id="extruder-feedrate" type="text" style="text-align: center;" value="300" />
													</label>
													<p class="note">Feedrate</p>
												</section>
												<section class="col col-3 text-center">
												</section>
											</div>
										</fieldset>
									</div>
								</div>
							</div>

						</div>

					</div>
					
				</div>
			</div>
		</div>

	</div>
</div>

<!-- CONSOLE, MDI -->
<div class="row">
	<div class="col-sm-6">
		<div class="well">
			<div class="row">
				
				<div class="col-sm-12 ">
					
					
					<div class="btn-group btn-group-justified">
						<a title="Switch off motors"  href="javascript:void(0);" class="btn  btn-info motors-off">Motor Off</a>
						<a title="Read config" id="eeprom" href="javascript:void(0);" class="btn btn-info">Read Config</a>
						<a title="Home all axis" id="home-all-axis" data-macro="true" href="javascript:void(0);" class="btn btn-info">Home All Axis</a>
						<a title="Zero All" id="zero-all" href="javascript:void(0);" class="btn btn-info zero_all">Zero All</a>
						<a title="Get current position" id="position" href="javascript:void(0);" class="btn btn-info">Position</a>
						<a title="Auto Bed Leveling" id="bed-align" data-macro="true" href="javascript:void(0);" class="btn btn-info">ABL</a>
						<a title="GCode Help" id="gcode-manual" data-toggle="modal" href="<?php echo site_url("jog/manual") ?>" data-target="#manula-modal" href="javascript:void(0);" class="btn btn-default">Help</a>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<!-- CHAT TEXTAREA -->
					<div class="textarea-div">
						<div class="typearea">
							<textarea rows="10" placeholder="MDI: write commands" id="mdi" class="custom-scroll"></textarea>
						</div>

					</div>
					<!-- CHAT REPLY/SEND -->
					<span class="textarea-controls">
						<button id="run" class="btn btn-sm btn-info pull-right">
							Run
						</button> </span>

				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="well">
			<div class="row">
				<div class="col-sm-12">
					<ul class="list-button pull-right">
						<li>
							<a rel="tooltip" data-placement="left" data-original-title="Clear console" id="clear-console" href="javascript:void(0)" class="btn btn-info btn-xs"> <i class="fa fa-eraser"></i> Clear </a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<pre class="console"></pre>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- HELP MODAL -->
<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="manula-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content"></div>
	</div>
</div>