<!-- TITLE -->
<div class="row">
	<div class="col-xs-4 col-sm-4">
		<h1 class="page-title txt-color-blueDark"><i class="icon-fab-jog fab-fw"></i> Jog</h1>
	</div>
	<div class="col-xs-8 col-sm-8 text-right">
		<a id="reset-controller" class="btn btn-info " href="javascript:void(0)"  title="Reset Controller">Reset controller</a>
	</div>
</div>

<!-- TEMPERATURES -->
<div class="row">
	<!-- EXTRUDER TEMP -->
	<div class="col-sm-6">
		<div class="well">
			<h5><a class="btn btn-default refresh-temperature" href="javascript:void(0);"><i class="fab-lg fab-fw icon-fab-term txt-color-red"></i> Extruder</a><span id="ext-degrees" class="label label-info pull-right"></span>&nbsp;<span id="ext-actual-degrees" class="label label-danger pull-right margin-right-5"></span></h5>
			<div id="act-ext-temp" class="noUiSlider"></div>
			<div id="ext-target-temp" class="noUiSlider extruder-range"></div>
			<p class="font-md">
				&nbsp;
			</p>
		</div>
	</div>

	<!-- BED TEMP -->
	<div class="col-sm-6">
		<div class="well">
			<h5><a class="btn btn-default refresh-temperature" href="javascript:void(0);"><i class="fab-lg fab-fw icon-fab-term txt-color-red"></i>Bed</a><span id="bed-degrees" class="label label-info pull-right"></span><span id="bed-actual-degrees" class="label label-danger pull-right margin-right-5"></span></h5>
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
					<div class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<div class="col-md-5 control-label">
									<strong>ZY Step (mm)</strong>
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
				</div>

				<!-- JOG DIRECTIONS -->
				<div class="col-sm-6 text-center">
					<div class="btn-group-vertical">
						<a href="javascript:void(0)" data-attribue-direction="up-left" data-attribute-keyboard="103" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="fa fa-arrow-left fa-1x fa-rotate-45"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="left" data-attribute-keyboard="100" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <span class="glyphicon glyphicon-arrow-left "> </span> </a>
						<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="fa fa-arrow-down fa-rotate-45 "> </i> </a>
					</div>
					<div class="btn-group-vertical">
						<a href="javascript:void(0)" data-attribue-direction="up" data-attribute-keyboard="104" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="fa fa-arrow-up fa-1x"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-default btn-lg btn-circle btn-xl directions rotondo"> <i class="fa fa-bullseye"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="glyphicon glyphicon-arrow-down "> </i> </a>
					</div>
					<div class="btn-group-vertical">
						<a href="javascript:void(0)" data-attribue-direction="up-right" data-attribute-keyboard="105" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="fa fa-arrow-up fa-1x fa-rotate-45"> </i> </a>
						<a href="javascript:void(0)" data-attribue-direction="right" data-attribute-keyboard="102" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <span class="glyphicon glyphicon-arrow-right"> </span> </a>
						<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo"> <i class="fa fa-arrow-right fa-rotate-45"> </i> </a>
					</div>
					<div class="btn-group-vertical" style="margin-left: 10px;">
						<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zdown"> <i class="fa fa-angle-double-up"> </i>&nbsp;Z </a>
						<hr />
						<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zup"> <i class="fa fa-angle-double-down"> </i>&nbsp; Z </a>
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
								<span>Mode:</span><span class="mode">Extruder</span>
							</li>
						</ul>
						<div class="tab-content padding-10">
							<!-- A MODE -->
							<div class="tab-pane fade in " id="modea">
								<div class="knobs-demo  text-center" id="mode-a">
									<input class="knob" data-width="200" data-cursor="true" data-step="0.5" data-min="1" data-max="360" data-thickness=".3" data-fgColor="#A0CFEC" data-displayInput="true">
								</div>
							</div>
							<!-- B MODE -->
							<div class="tab-pane fade in active" id="modeb">
								<div class="row">
									<div class="smart-form" >
										<fieldset style="background: none !important">
											<div class="row">
												<section class="col col-3 text-center">

												</section>
												<section class="col col-6 text-center">
													<label><strong>Step (mm) </strong></label>
												</section>
												<section class="col col-3 text-center">

												</section>
											</div>
											<div class="row">
												<section class="col col-3" >
													<button data-action="-" type="button" class="btn btn-info btn-sm btn-block extruder-e-action">
														<i class="fa fa-minus"></i>
													</button>
												</section>
												<section class="col col-6" >
													<label class="input">
														<input id="extruder-e-value" type="text" style="text-align: center;" value="10" />
													</label>
												</section>
												<section class="col col-3" >
													<button data-action="+" type="button" class="btn btn-info btn-sm btn-block extruder-e-action">
														<i class="fa fa-plus"></i>
													</button>
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
				<div class="col-sm-3"></div>
				<div class="col-sm-9 ">
					<ul class="list-button pull-right">
						<li>
							<a class="btn btn-info btn-xs" id="home-all-axis" data-macro="true"  href="javascript:void(0)"  title="Home all axis">Home All axis</a>
						</li>
						<li>
							<a class="btn btn-info btn-xs" id="zero-all" href="javascript:void(0)"  title="Zero All">Zero All</a>
						</li>
						<li>
							<a class="btn btn-info btn-xs" id="position" href="javascript:void(0)"  title="Position">Position</a>
						</li>
						<li>
							<a class="btn btn-info btn-xs" id="bed-align" data-macro="true" href="javascript:void(0)" class="macro" title="Auto Bed Leveling">ABL</a>
						</li>

						<li>
							<a id="gcode-manual" data-toggle="modal" href="<?php echo site_url("jog/manual") ?>" data-target="#manula-modal" class="btn btn-default btn-xs " href="javascript:void(0);"><i class="fa fa-support"></i> Help</a>
						</li>

					</ul>
				</div>
			</div>
			<!--
			<div class="row">
			<div class="col-sm-12">
			<textarea class="form-control" id="mdi"></textarea>
			<button style="margin-top:5px;" id="run" type="button" class="btn btn-info btn-sm btn-block">
			Run
			</button>
			</div>
			</div>
			-->

			<div class="row">
				<div class="col-sm-12">
					<!-- CHAT TEXTAREA -->
					<div class="textarea-div">

						<div class="typearea">
							<textarea rows="8" placeholder="Write command" id="mdi" class="custom-scroll"></textarea>
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
							<a id="clear-console" href="javascript:void(0)" class="btn btn-info btn-xs"> <i class="fa fa-eraser"></i> Clear </a>
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