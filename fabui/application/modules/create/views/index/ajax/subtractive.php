<div id="row_1" class="row interstitial">
    <div class="col-sm-12">
        <div class="well text-center">
            <h1>Subtractive file</h1>
            <h2>Press the button to continue</h2>
            <h2><i class="icon-fab-mill fa-border fa-4x"></i></h2>
        </div>
    </div>
</div>


<div id="row_2" class="row interstitial" style="display: none;">
    <div class="col-sm-12">
        <div class="well text-center">
            <h1>Checking printer</h1>
            <h2 id="res-icon" class="fa fa-spinner"></h2>
            <p class="check_result"></p>
        </div>
    </div>
</div>


<div id="row_3" class="row interstitial" style="display: none;">
	<div class="col-sm-12">
		<div class="well">
			<div class="row">
				<div class="col-sm-6">
					<div class="text-center">
						<div class="row">
							<div class="col-sm-7">
								<img style=" display: inline;" class="img-responsive" src="../application/modules/create/assets/img/subtractive/1.png" />
							</div>
							<div class="col-sm-5">
								
								<h1></h1>
								<h2 class="text-center">Jog the endmill to the desired origin point (X=0, Y=0, Z=0), press <i class="fa fa-bullseye"></i> and then press "Start"</h2>
								
							</div>
						</div>
					</div>
				</div>
			    <div class="col-sm-6">
			        <div class="text-center">
			            <div class="row">
							<div class="col-sm-12">
								<div class="smart-form">
									<fieldset style="background: none !important;">
										<div class="row">
											<section class="col col-4">
												<label class="label-mill text-center">XY Step (mm)</label>
												<label class="input">
													<input  type="text" id="step" value="10">
												</label>
											</section>
											<section class="col col-4">
												<label class="label-mill text-center">Feedrate</label>
												<label class="input">
													<input  type="text" id="feedrate" value="1000">
												</label>
											</section>
											<section class="col col-4">
												<label class="label-mill text-center">Z Step (mm)</label>
												<label class="input"> 
													<input type="text" id="z-step" value="5">
												</label>
											</section>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
			            <div class="row">
							<div class="col-sm-8">
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
									<a href="javascript:void(0)" id="zero-all" rel="tooltip" title="Zero"  class="btn btn-default btn-lg btn-circle btn-xl rotondo">
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
			                    
			                    
			                    <div class="btn-group-vertical margin-top-10" style="margin-left: 10px;">
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zdown">
										<i class="fa fa-angle-double-up">
										</i>&nbsp;Z
									</a>
			                        <hr />
									<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zup">
										<i class="fa fa-angle-double-down">
										</i>&nbsp; Z
									</a>
									
								</div>
							</div>
							
							
							<div class="col-sm-4">
								<span>Mode:</span><span class="mode"> 4th Axis</span>
								<div class="knobs-demo  text-center margin-top-10" id="mode-a">
									<input class="knob" data-width="150" value="0" data-cursor="true" data-step="0.5" data-min="1" data-max="360" data-thickness=".3" data-fgColor="#A0CFEC" data-displayInput="true">
								</div>
							</div>
							
							
						</div>
			        </div>
        		</div>
    		</div>
		</div>
    </div>

</div>


<div class="row button-print-container margin-bottom-10">
        <div class="col-sm-12 text-center ">
            <a id="exec_button" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here if you are ready</a>
        </div>
</div>


<script type="text/javascript">

	
	var re = /fabui\/make\/mill\?obj=(\d+)\&file=(\d+)/;
	
	if ((m = re.exec(window.location.href)) !== null) {
		disable_button("#btn-next");
	}
	
	
	var setZero = false;

	$("#velocity-slider-container").removeClass('col-md-4 col-lg-4').addClass('col-md-6 col-lg-6');
	$("#ext-slider-container").hide();
	$("#bed-slider-container").hide();
	$("#rpm-slider-container").show();

    $("#zero-all").on("click", zero_all);
    
    $( ".axisz" ).on( "click", axisz );
    
	$(".directions").on("click", directions);
	
	$(".jog").addClass('disabled');
	
	
	$("#z-step").spinner({
				step : 0.01,
				numberFormat : "n",
				min: 0
		});
		
		
		$("#step").spinner({
				step :0.5,
				numberFormat : "n",
				min: 0
		});
		
		$("#feedrate").spinner({
				step :50,
				numberFormat : "n",
				min: 0
		});
		
		
	/** KNOB */
		$('.knob').knob({
	        change: function (value) {
	        },
	        release: function (value) {
				rotation(value);
	        },
	        cancel: function () {
	            console.log("cancel : ", this);
	        }
		 });
		 
		 
		$('.knob').keypress(function(e) {
	        if(e.which == 13) {
	        	rotation($(this).val());
	        }
	 	 });
	 	 
	 	 
	 	 
	 function rotation(value){
		
		if(SOCKET_CONNECTED){
			make_call_ws("rotation", value);
		}else{
			make_call("rotation", value);
		}
			
	}		

    $('#exec_button').on('click', function(){
        
        
        var actual_row;
        var next_row;
        var action = $(this).attr('data-action');
        
        
        $( ".interstitial" ).each(function( index ) {
                
            if($(this).is(":visible") ){
                actual_row = parseInt($(this).attr('id').replace('row_', ''));
            } 
        });
        
        if(actual_row == 3){
            
            print_object();
            return false;
            
        }
        
        if(action == "check"){
                pre_print();
                return false; 
        }
        
        
        next_row = actual_row + 1;
        
        if ($("#row_" + next_row).length > 0){
            
            $('#exec_button').addClass('disabled');
            
            $("#row_" + actual_row).slideUp('slow', function(){
                
            });
            
            $("#row_" + next_row).slideDown('slow', function(){
                
                switch(next_row){
                    
                    case 2:
                        pre_print();
                        break;
                    
                    case 3:
                    /*
                        $("#exec_button").html('Print');
                        $('#exec_button').removeClass('disabled');
                    */
                        break;
                    
                }
            });
        }
        
    });
    
    
    function pre_print(){
       
       
       	openWait('Checking printer');
       	 
        $('#exec_button').addClass('disabled');
        $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
        $("#res-icon").addClass('fa-spinner fa-spin');
        $('#modal_link').addClass('disabled');
        
        
        var timestamp = new Date().getTime();
            
        //ticker_url = '/temp/check_' + timestamp + '.trace';
        ticker_ulr = '/temp/macro_trace';
        
                        
        $.ajax({
        		  url: ajax_endpoint + 'ajax/pre_print.php',
        		  dataType : 'json',
                  type: "POST", 
        		  async: true,
                  data : { file : file_selected.full_path, time:timestamp},
        		  beforeSend: function( xhr ) {
        		  }
        	}).done(function(response) {
                
                var status = response.status;
                
                if(status == 200){
                	
                	$("#row_2").slideUp('slow', function(){
                    	$("#row_3").slideDown('slow');
                    });
                	
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
                    $("#exec_button").html('Start');
                    
                    $('.check_result').html('');           
                    $("#exec_button").attr('data-action', '');
                    
                    $("#exec_button").addClass("disabled");
                    
                    
                }else{
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
                    $('.check_result').html(response.trace);
                    $("#exec_button").html('Oops.. try again');
                    $("#exec_button").attr('data-action', 'check');
                }
                
                
                ticker_url = '';
                closeWait();
                //$('#exec_button').removeClass('disabled');
                
                
                
        	});
        
    }
    
    
    function zero_all(){
    	
    	setZero = true;
    	make_call("zero_all_pre_mill", true);
    	
    	$("#exec_button").removeClass('disabled');
    	
    	
    	//krios
    }
    
    
    function make_call(func, value){   	
    	
    	if(SOCKET_CONNECTED){	
    		make_call_ws(func, value);
    		return false;
    	}
    	
    	

    	$.ajax({
    		type: "POST",
    		url :ajax_jog_endpoint + 'ajax/exec.php',
    		data : {function: func, value: value, step:$("#step").val(), z_step:$("#z-step").val(), feedrate: $("#feedrate").val()},
    		dataType: "json"
    	}).done(function( data ) {
            //$(".btn").removeClass('disabled');
            
    	});
	
    }
    
    function make_call_ws(func, value){
    	
    	var jsonData = {};
		
		jsonData['func']     = func;
		jsonData['value']    = value;
		jsonData['step']     = $("#step").val();
		jsonData['z_step']   = $("#z-step").val();
		jsonData['feedrate'] = $("#feedrate").val();
		
		var message = {};
		
		message['name'] = "serial";
		message['data'] = jsonData;
		
		SOCKET.send('message', JSON.stringify(message));
		
    }

</script>

