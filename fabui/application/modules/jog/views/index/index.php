<div class="row">
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
            <i class="icon-fab-jog fab-fw"></i>&nbsp;Jog&nbsp;&nbsp;<span class="status"></span>
		</h1>
	</div>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-right margin-top-10">
		<div class="onoffswitch-container">
			<span class="onoffswitch-title">Motors</span> 
			<span class="onoffswitch">
				<input type="checkbox" class="onoffswitch-checkbox" id="motors">
				<label class="onoffswitch-label" for="motors"> 
					<span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
					<span class="onoffswitch-switch"></span>
				</label> 
			</span> 
		</div>
		<!--
        <div class="onoffswitch-container">
			<span class="onoffswitch-title">Coordinates</span> 
			<span class="onoffswitch">
				<input type="checkbox" class="onoffswitch-checkbox" id="coordinates">
				<label class="onoffswitch-label" for="coordinates"> 
					<span class="onoffswitch-inner" data-swchon-text="REL" data-swchoff-text="ABS"></span> 
					<span class="onoffswitch-switch"></span>
				</label> 
			</span> 
		</div>
		-->
        <div class="onoffswitch-container">
			<span class="onoffswitch-title">Light</span> 
			<span class="onoffswitch">
				<input type="checkbox" class="onoffswitch-checkbox" id="lights">
				<label class="onoffswitch-label" for="lights"> 
					<span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
					<span class="onoffswitch-switch"></span>
				</label> 
			</span> 
		</div>
	</div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="well ">
            	
            	<div class="btn-group btn-group-justified" >
	                <a id="home-all-axis"    href="javascript:void(0)" class="btn btn-primary " title="Home all axis">Home All axis</a>
					<a id="zero-all"         href="javascript:void(0)" class="btn btn-primary " title="Zero All">Zero All</a>
					<a id="position"         href="javascript:void(0)" class="btn btn-primary " title="Position">Position</a>
					<a id="get-temp-ext"     href="javascript:void(0)" class="btn btn-primary " title="Temperature">Temperature</a>
	                <a id="bed-align"        href="javascript:void(0)" class="btn btn-primary  macro" title="Auto Bed Leveling">ABL</a>
	                <a id="reset-controller" href="javascript:void(0)" class="btn btn-primary " title="Reset Controller">Reset controller</a>
                </div>
			
        </div>
    </div>
   
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="well text-center">
        	<h4>Extruder <i class="fab-lg fab-fw icon-fab-term"></i></h4>
            <h5>actual: <span id="ext-actual-degrees"><?php echo $_ext_temp ?></span>&deg; - target:&nbsp;<span class="" id="ext-degrees"><?php echo $_ext_temp ?></span>&deg; C </h5>
            <div id="ext-temp" class="noUiSlider"></div>
            

        </div>
    </div>
    <div class="col-sm-6">
        <div class="well text-center">
        	<h4>Bed <i class="fab-lg fab-fw icon-fab-term"></i></h4>
            <h5>actual: <span id="bed-actual-degrees"><?php echo $_bed_temp ?></span>&deg; - target:&nbsp; <span id="bed-degrees"><?php echo $_bed_temp ?></span>&deg; C</h5>
            <div id="bed-temp" class="noUiSlider"></div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="">
			
			
			
			<div class="row">
       
				<div class="col-sm-8">
					<div class="well text-center" style="">
						
						<div class="row">
							<div class="col-sm-12">
								<div class="smart-form" style="background: none; margin-top: -30px">
									<fieldset style="background: none;">
										<div class="row">
											<section class="col col-4">
												<label class="label text-center">Step (mm)</label>
												<label class="input-sx">
													<input class="text-center" type="text" id="step" value="10">
												</label>
											</section>
											<section class="col col-4">
												<label class="label text-center">Feedrate</label>
												<label class="input-sx">
													<input class="text-center" type="text" id="feedrate" value="1000">
												</label>
											</section>
											<section class="col col-4">
												<label class="label text-center">Z Step (mm)</label>
												<label class="input-sx"> 
													<input class="text-center" type="text" id="z-step" value="5">
												</label>
											</section>
											
										</div>

									</fieldset>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
						
								<div class="btn-group-vertical">
									<a href="javascript:void(0)" data-attribue-direction="up-left" data-attribute-keyboard="103" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="fa fa-arrow-left fa-1x fa-rotate-45">
										</i>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="left" data-attribute-keyboard="100" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<span class="glyphicon glyphicon-arrow-left ">
										</span>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="fa fa-arrow-down fa-rotate-45 ">
										</i>
									</a>
								</div>
								<div class="btn-group-vertical">
									<a href="javascript:void(0)" data-attribue-direction="up" data-attribute-keyboard="104" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="fa fa-arrow-up fa-1x">
										</i>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-default btn-lg btn-circle btn-xl directions rotondo">
										<i class="fa fa-bullseye">
										</i>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="glyphicon glyphicon-arrow-down ">
										</i>
									</a>
								</div>
								<div class="btn-group-vertical">
									<a href="javascript:void(0)" data-attribue-direction="up-right" data-attribute-keyboard="105" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="fa fa-arrow-up fa-1x fa-rotate-45">
										</i>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="right" data-attribute-keyboard="102" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<span class="glyphicon glyphicon-arrow-right">
										</span>
									</a>
									<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
										<i class="fa fa-arrow-right fa-rotate-45">
										</i>
									</a>
								</div>
		                        
		                        
		                        <div class="btn-group-vertical" style="margin-left: 10px;">
		                        	<!--
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zdown">
										<i class="fa fa-angle-double-up"></i>&nbsp;10
									</a>
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zdown">
										<i class="fa fa-angle-double-up">
										</i>&nbsp;5
									</a>
									-->
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zdown">
										<i class="fa fa-angle-double-up">
										</i>&nbsp;Z
									</a>
		                            <hr />
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zup">
										<i class="fa fa-angle-double-down">
										</i>&nbsp; Z
									</a>
									<!--
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zup">
										<i class="fa fa-angle-double-down">
										</i>&nbsp;5
									</a>
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zup">
										<i class="fa fa-angle-double-down">
										</i>&nbsp;10
									</a>
									-->
								</div>
							</div>
						</div>
					</div>
				</div>
			
				
				
				<div class="col-sm-4 margin-bottom-10">
                            <ul id="myTab1" class="nav nav-tabs tabs-pull-right bordered">
                                <li><a href="#modeb" data-toggle="tab" data-mode="e" class="extruder-mode"> <i class="fab-lg fab-fw icon-fab-e fab-2x"></i></a></li>
                                <li class="active"><a href="#modea" data-toggle="tab" data-mode="a" class="extruder-mode">  <i class="fab-lg fab-fw icon-fab-a fab-2x"></i></a></li>
                            </ul>
                            
                            <div id="myTabContent1" class="tab-content padding-10" style="background: #fbfbfb; height: 225px;">
                            
                                <div class="tab-pane fade in active" id="modea">
                                    <div class="knobs-demo mode-container text-center" id="mode-a">
        								<input class="knob" data-width="200" data-cursor="true" data-step="0.5" data-min="1" data-max="360" data-thickness=".3" data-fgColor="#A0CFEC" data-displayInput="true">
        							</div>
                                </div>
                                
                                <div class="tab-pane fade in" id="modeb">
                                     <div id="mode-e" class="mode-container" style="margin-top: 30px;">
                                        <div class="smart-form">
                                            <fieldset>
                                              
                                              
                                              	<div class="row">
                                              		<section class="col col-3"></section>
                                              		<section class="col col-6">
                                              			<label class="label text-center">Step (mm)</label>
                                              		</section>
                                              		<section class="col col-3"></section>
                                              	</div>
                                                <div class="row">
                                                    <section class="col col-3">
                                                        <button data-action="-" type="button" class="btn btn-default btn-sm btn-block extruder-e-action">
            												<i class="fa fa-minus"></i> 
            											</button>
                                                    </section>
                                                    <section class="col col-6">
                                                        <label class="input">
                											<input id="extruder-e-value" type="text" style="text-align: center;" value="10" />
                										</label>
                                                    </section>
                                                    <section class="col col-3">
                                                         <button data-action="+" type="button" class="btn btn-default btn-sm btn-block extruder-e-action">
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
<div class="row">
    <div class="col-sm-12">
        <div class="well">      
            <div class="row" style="margin-left:0px; margin-right: 0px;">
                <div class="col-sm-6 margin-bottom-10">
                    <p>MDI 
                    	<a id="gcode-manual" data-toggle="modal" href="<?php echo site_url("jog/manual") ?>" data-target="#manula-modal" class="pull-right" href="javascript:void(0);">Help</a>
                    	<span class="pull-right"> | </span>
                    	<a id="clear-mdi" class="pull-right" href="javascript:void(0);">Clear</a>
                    </p>
                    <textarea class="form-control" id="mdi" style="height: 200px; text-transform: uppercase; resize: none"></textarea>
                    
                    <button id="run" type="button" class="btn btn-primary btn-sm btn-block">Run</button>
                </div>
                <div class="col-sm-6">
                    <p>Console <a id="clear-console" class="pull-right" href="javascript:void(0);">Clear</a></p>
                    
                    <pre id="console" style="height: 200px; overflow: auto !important;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="manula-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    </div>
  </div>
</div>


