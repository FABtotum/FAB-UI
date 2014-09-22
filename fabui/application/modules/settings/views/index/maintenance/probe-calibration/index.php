<div class="tab-pane active fade in">

    <div class="row margin-top-10" id="row-1">
    
        <div class="col-sm-12">

          <div class="well">
          	
          		
          		<div class="row">
          			
          			<div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('settings').'assets/img/maintenance/probe-calibration/nozzle.png' ?>" />
                    </div>
                    
                     <div class="col-sm-6 text-center">
                        <h2>Make sure nozzle is clean and then press OK to continue</h2>
                        <button id="probe-calibration-prepare" class="btn btn-default btn-lg">Ok</button>
                    </div>
          			
          		</div>
          	
          </div>

        </div>
        
    </div>
    
    
    <div class="row margin-top-10" id="row-2" style="display:none;">
    	<div class="col-sm-12">
    		<div class="well">
    			
    			<div class="row">
    				
    				
    				<div class="col-sm-6">
    					<p></p>
    				</div>
    				
    				<div class="col-sm-6">
    					<div class="row">
	    					<div class="smart-form">
	    						<fieldset style="background: none; !important">
	    							<div class="row">
	    								<section class="col col-3 text-center">
	    									<label>Z up</label>
	    								</section>
	    								<section class="col col-6 text-center">
	    									<label>Step</label>
	    								</section>
	    								<section class="col col-3 text-center">
	    									<label>Z down</label>
	    								</section>
	    							</div>
	    							<div class="row">
	    								<section class="col col-3">
	    									<button data-action="+" type="button" class="btn btn-default btn-sm btn-block z-action"><i class="fa fa-arrow-down"></i> </button>
	    								</section>
	    								<section class="col col-6">
	    									<label class="input"><input id="z-value" type="text" style="text-align: center;" value="0.1"></label>
	    								</section>
	    								<section class="col col-3">
	    									<button data-action="-" type="button" class="btn btn-default btn-sm btn-block z-action"><i class="fa fa-arrow-up"></i></button>
	    								</section>
	    							</div>
	    						</fieldset>
	    					</div>
    					</div>
    					<div class="row text-align-center">
    						<button id="probe-calibration-calibrate" class="btn btn-default btn-lg">Calibrate</button>
    					</div>
    				</div>
    			</div>
    			
    		</div>
    	</div>
    </div>
    
    <div class="row margin-top-10" id="row-3" style="display:none;">
    	<div class="col-sm-12">
    		
    		<div class="row">
    			<div class="col-sm-3">
    				<h4>Calibration result</h4>
    			</div>
    			<div class="col-sm-9">
    				<button id="calibrate-again" class="btn btn-default btn-lg pull-right">Calibrate again</button>
    			</div>
    		</div>
    		<div class="row margin-top-10">
    			<div class="col-sm-12">
    				<pre id="calibrate-trace"></pre>
    			</div>
    		</div>
    		
    			
    	</div>
    </div>


</div>
