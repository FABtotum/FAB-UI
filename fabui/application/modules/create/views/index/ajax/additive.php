<!-- FIRST STEP WITH INFO TYPE PRINT - ADDITIVE OR SUB -->    
<!-- SECOND RESULT OF MACRO -->
<!-- THIRD ASK FOR AUTO BED LEVELING -->
     <?php if($show_feeder): ?>
    <div id="row_0" class="row interstitial" >
    	
    	
    	<div class="col-sm-12" id="engaege_step1">
    		
    		<div class="well">
    			
    			<div class="row">
    				<div class="col-sm-6 text-center">
    					<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/close-panel.png" />
    				</div>
    				<div class="col-sm-6 text-center">
    					<h1>
    						<span class="badge bg-color-blue txt-color-white">1</span>
    					</h1>
    					<h2>Make sure the feeder is engaged</h2>
    					<h2>Close the cover</h2>
    					<h2>Click 'Engage' to engage the feeder or click the skip button to continue </h2>
    					
    				</div>
    			</div>
    			
    		</div>
    		
    	</div>
    	
    	
    	<div class="col-sm-12" id="engaege_step2" style="display:none;">
    		
    		<div class="well">
    			
    			<div class="row">
    				<div class="col-sm-6 text-center">
    					<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/feeder.png" />
    				</div>
    				<div class="col-sm-6 text-center">
    					<h1>
    						<span class="badge bg-color-blue txt-color-white">2</span>
    					</h1>
    					<h2>To engage the filament feeder push the small button under the building platform near the 4th axis chuck.</h2>
    					<h2>Apply a good amount of force when pushing</h2>    					
    					<h2>If extruder mode is already engaged press OK to continue</h2>

    				</div>
    			</div>
    			
    		</div>
    		
    	</div>
    	
    	
    </div>
    <?php endif; ?>
    
    <div id="row_1" class="row interstitial" style="<?php echo $show_feeder ? 'display: none;' : '' ?>">

        <div class="col-sm-6">
            
            <div class="well">
                
                <div class="row">
                    
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/additive/1.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge bg-color-blue txt-color-white"><?php echo $show_feeder ? 3 : 1 ?></span>
            			</h1>
                        
            			<h2>
            				Make sure that the working plane is clean and free to use
            			</h2>
                    
                    </div>
                
                </div>
            
                
            </div>
            
        </div>
        
        <div class="col-sm-6">
            <div class="well">
                
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/additive/2.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge bg-color-blue txt-color-white"><?php echo $show_feeder ? 4 : 2 ?></span>
            			</h1>
                        
            			<h2>
            				Close the cover
            			</h2>
                    
                    </div>
                    
                </div>
            
            </div>
        </div>
    
    </div>
    
    
    <div id="row_2" class="row interstitial" style="display: none;">

        <div class="col-sm-12">
            <div id="check_pre_print"  class="well">
                
                
                
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-center">
                        <h1 class="">Checking printer</h1>
                        <h2 class=""><i id="res-icon" class="fa fa-spinner"></i></h2>
                    </div>
                    <div class="col-sm-4"></div> 
                
                </div>
                
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 check_result  text-center" ></div>
                    <div class="col-sm-4"></div>
                </div>
                
            
            </div>
        
        </div>
    
    </div>
    
    
    <div id="row_3" class="row interstitial" style="display: none;">
    	
    	
    	
    	
    	<div class="col-sm-12">
    		
    		<div class="row margin-bottom-10">
    			<div class="col-sm-12 text-center"> 
    				<h2>Calibration</h2>
    				<h5>Print will start automatically in <span class="autostart-timer">20</span> seconds</h5>
    			</div>
    		</div>
    		
    		<div class="row">
    			
    			
    			<div class="col-sm-6">
			<div class="well">
				<div class="row">
					<div class="col-sm-6 text-center">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/homing.png" />
					</div>
					<div class="col-sm-6 text-center">
						 <div class="form-group">
						 	<div class="radio">
								<label>
									<input type="radio" checked="checked" class="radiobox choose-calibration"  name="calibration" value="homing">
									<span>Simple homing</span> 
								</label>
							</div>
						 </div>
						 <p>Quickly home all axis. Works well with a well calibrated working plane. (SUGGESTED)</p>
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="col-sm-6">
			<div class="well">
				<div class="row">
					<div class="col-sm-6 text-center">
						<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/abl.png" />
					</div>
					
					<div class="col-sm-6 text-center" >
						 <div class="form-group">
						 	<div class="radio">
								<label>
									<input type="radio" class="radiobox choose-calibration"  name="calibration" value="abl"> 
									<span>Auto bed leveling</span> 
								</label>
							</div>
						 </div>
						 <p>Probes the working plane to auto-correct movements to account for not leveled bed. </p>
					</div>
					
				</div>
				
			</div>
		</div>
    			
    		</div>
    		
    	</div>
    	

		
    </div>
    
    <!-- 
    <div id="row_3" class="row interstitial" style="display: none;">

        <div class="col-sm-6">
            <div class="well final-step">
                <h2>Calibration</h2>
                
                
                <div class="form-group">
											
					<div class="col-md-10">
						<div class="radio">
							<label>
								<input type="radio" class="radiobox choose-calibration" checked="checked" name="calibration" value="homing">
								<span>Simple homing</span> 
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" class="radiobox choose-calibration"  name="calibration" value="abl"> 
								<span>Auto bed leveling</span> 
							</label>
						</div>
						
					</div>
				</div>
                
                
                <div class="final-step-response"></div>
            </div>
        
        </div>
        
        
        <div class="col-sm-6">
        	<div class="well">
        		<img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/create/assets/img/homing.png" />
        	</div>
        </div>
    
    </div>
    -->
    
    
    
    
    <div class="row button-print-container margin-bottom-10">
        <div class="col-sm-12 text-center ">
        
            <a id="modal_link" data-action="<?php echo $action_button; ?>" href="javascript:void(0);" class="btn btn-primary btn-lg"><?php echo $label_button; ?></a>
            
            <?php if($show_feeder): ?>
            <a id="skip_engage"  href="javascript:void(0);" class="btn btn-primary btn-lg">Skip</a> 
            <?php endif; ?>
        </div>
    </div>
    
<script type="text/javascript">
	
	
	var re = /fabui\/make\/print\?obj=(\d+)\&file=(\d+)/;
	
	if ((m = re.exec(window.location.href)) !== null) {
		disable_button("#btn-next");
	}
	  
	
	
	
	$("#velocity-slider-container").removeClass('col-md-6 col-lg-6').addClass('col-md-4 col-lg-4');
	$("#ext-slider-container").show();
	$("#bed-slider-container").show();
	$("#rpm-slider-container").hide();
	$("#skip_engage").on('click', skip_engage);
	
	var autostart_timer = 20;
	
	function skip_engage(){
		
		$("#row_0").slideUp('slow', function(){
			
			
			$("#row_1").slideDown('slow', function(){
				$("#skip_engage").hide();
				
				$("#modal_link").html('Continue');
                $("#modal_link").attr('data-action', '');
				
				
			});	
			
			
			
		});
	}

    $('#modal_link').on('click', function(){
    
            
            var actual_row;
            var next_row;
            var action = $(this).attr('data-action');
            
            if(action == "exec"){
            	stopCountDown();
                print_object();
                return false; 
            }
            
            
            if(action == "check"){
                pre_print();
                return false; 
            }
            
            
            
            if(action == "feeder"){
            	engage_feeder();
            	return false;
            }
            
            $( ".interstitial" ).each(function( index ) {
                
                if($(this).is(":visible") ){
                    actual_row = parseInt($(this).attr('id').replace('row_', ''));
                } 
            });
            
            next_row = actual_row + 1;

            if ($("#row_" + next_row).length > 0){
                
                $("#row_" + actual_row).slideUp('slow', function(){
                
                });
                
               
                
                $("#row_" + next_row).slideDown('slow', function(){
                    
                    
                    switch(next_row){
                    	
                    	case 1:
                    		
                    		 $("#modal_link").html('Continue');
                    		break;
                        
                        case 2:
                            pre_print();
                            break;
                        
                        case 3:
                            $("#modal_link").html('Start');
                            $("#modal_link").attr('data-action', 'exec');
                            $("#skip").show();
                            startCountDown();
                            break;
                        
                    }
                });
                
                
            }
          
            
        });
        
        
        
        function pre_print(){
            
            jog_call("bed_temp", 40);
            jog_call("ext_temp", 150);
            
            if ( typeof (Storage) !== "undefined") {
            	localStorage.setItem("bed_temp_target", 40);
            	localStorage.setItem("nozzle_temp_target", 150)

				$("#top-bar-bed-target").html(40);
				$("#top-bar-nozzle-target").html(150);
            }
                        
            IS_MACRO_ON = true;
            
            openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Preparing print');
            
            $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
            $("#res-icon").addClass('fa-spinner fa-spin');
            $('#modal_link').addClass('disabled');
            
            var timestamp = new Date().getTime();
            
            ticker_url = '/temp/macro_trace';
                       
            $.ajax({
        		  url: ajax_endpoint + 'ajax/pre_print.php',
        		  dataType : 'json',
                  type: "POST", 
        		  async: true,
                  data : { file : file_selected.full_path, time : timestamp, type: 'additive', engage_feeder: isEngageFeeder }
        	}).done(function(response) {
                
                var status = response.status;
                
                if(status == 200){
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
                    $("#modal_link").html('Continue');
                    $("#modal_link").attr('data-action', '');
                    $('.check_result').html('');
                    $('#modal_link').trigger('click');
                    
                    disable_button('#btn-next');
                    
                }else{
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
                    /*$('.check_result').html(response.trace);*/
                    $("#modal_link").html('Oops.. try again');
                    $("#modal_link").attr('data-action', 'check');
                    
                    
                    $.smallBox({
						title : "Warning",
						content: response.trace,
						color : "#C46A69",
						icon : "fa fa-warning",
		                timeout: 15000
		            });
		            
		             disable_button('#btn-next');
                        
                }
                IS_MACRO_ON = false;
                ticker_url = '';
                closeWait();
                $('#modal_link').removeClass('disabled');    
        	});
        }
        
        
        
        
        function engage_feeder(){
        	
        	IS_MACRO_ON = true;
        	openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Engaging feeder');
            $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
            $("#res-icon").addClass('fa-spinner fa-spin');
            $('#modal_link').addClass('disabled');
            
            var timestamp = new Date().getTime();
            
            ticker_url = '/temp/macro_trace';
                       
            $.ajax({
        		  url: ajax_endpoint + 'ajax/engage_feeder.php',
        		  dataType : 'json',
                  type: "POST", 
        		  async: true,
                  data : { file : file_selected.full_path, time : timestamp }
        	}).done(function(response) {
                
                var status = response.status;
                
                if(status == 200){
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
                    $("#modal_link").html('Continue');
                    $("#modal_link").attr('data-action', '')
                    $("#skip_engage").hide();
                    $('.check_result').html('');
                    
                    
                    $("#engaege_step1").hide();
                    $("#engaege_step2").show();
                    $('#modal_link').html('Ok');
                    
                    isEngageFeeder = 1;
                      
                    
                }else{
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
                    /*$('.check_result').html(response.trace);*/
                    $("#modal_link").html('Oops.. try again');
                    $("#modal_link").attr('data-action', 'check');
                    
                    $.smallBox({
						title : "Warning",
						content: response.trace,
						color : "#C46A69",
						icon : "fa fa-warning",
		                timeout: 15000
		            });
		            
		            isEngageFeeder = 0;                         
                }
                IS_MACRO_ON = false;
                ticker_url = '';
                closeWait();
                $('#modal_link').removeClass('disabled');    
        	});
        	
        	
        }
        
         $(".choose-calibration").on('click', function() {
            
            calibration = $(this).val();
            
        });
        

</script>