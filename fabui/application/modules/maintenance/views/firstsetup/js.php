<script type="text/javascript">


	var ticker_url = '';
	var interval_ticker;
	
	var isStep2Ok = false;
	var isStep3Ok = false;
	var isStep4Ok = false;
	
	var choice = '';
	var probe_length = 0;
	
	var num_probes = 1;
	var skip_homing = 0;
	

	$(function () {
		
		$(".jog").addClass("disabled");
		
		interval_ticker   = setInterval(ticker, 500);
		
		var wizard = $('.wizard').wizard();
		
		/*
		
		wizard.on('finished', function (e, data) {
		
		});
		*/
		
		
		$(".step1-start").on('click', function() {
			$('.wizard').wizard('next');
		});
		
		$(".btn-prev").on('click', function() {
			$('.wizard').wizard('previous');
		});
		
		$(".btn-next").on('click', function() {
			$('.wizard').wizard('next');
		});
		
		
		$('.finish').on('click', finish_wizard);
		
		$("#heads").on('change', set_head_img);
		
		
		
		
		
		
		
		
		/*
		wizard.on('stepclick', function(e, data) {	
			check_wizard();
		});
		*/
		
		/*
		$('.btn-next').on('click', function() {
			
			check_wizard();
		});

		$('.btn-prev').on('click', function() {
			
			check_wizard();
		});
		
		*/
		
		$(".do-calibration").on('click', do_calibration);
		
		
		$("#probe-calibration-prepare").on('click', prepare);
		$("#probe-calibration-calibrate").on('click',  calibrate);
		$(".calibrate-again").on('click', do_again);
		
		$(".z-action").on('click', move_z);
		
		$("#z-value").spinner({
				step : 0.01,
				numberFormat : "n",
				max: 1,
				min: 0
		});
		
		
		$('.change-over').on({
			
		  mousedown : function () {
		  	
		  	
		    var over = parseFloat($("#over").val()).toFixed(2);
		    
		    if(over >= -2 && over <=  2){
		    	
		    	var action = $(this).attr("data-action");
		    
		   	 	over = eval(parseFloat(over) + action + '0.01');
		    
		    	$("#over").val(over.toFixed(2));
		    	
		    	interval = window.setInterval(function(){
		    	
		    		 if(over >= -2 && over <=  2){
			     		over = eval(parseFloat(over) + action + '0.01');
			     		$("#over").val(over.toFixed(2));
			     	}
			     	
			    }, 100);
		    	
		    	
		    }
		    
		  },
		  mouseup : function () {
		    window.clearInterval(interval);
		  }
		});
		
		
		$(".choice-button").on('click', function (){
                
            choice = $(this).attr('data-action');
            
            
            if(choice == 'normal'){
            	$( ".choice" ).slideUp( "slow", function() {});
            	$("#row-" + choice + "-1").slideDown('slow');
            	$(".re-choice").slideDown('slow');
           	 	$(".start").slideDown('slow');
            }
            
            if(choice == 'fast'){
            	
            	get_probe_length();
            	
            	
            }

                
        });
        
        
        $(".re-choice-button").on('click', function(){
            
            $("#row-" + choice + "-1").slideUp('slow');
            $( ".choice" ).slideDown( "slow", function() {});
            $(".re-choice").slideUp('slow');
            $(".start").slideUp('slow');
            
        });
        
        
        $("#probe-calibration-save").on('click', override_probe_length);
		
		$(".prepare-engage").on('click', prepare_feeder);
		
		
		
	});
	
	
	function ticker(){
		
		
		if(!SOCKET_CONNECTED){
		
		    if(ticker_url != ''){
		        
		         $.get( ticker_url , function( data ) {
		           
		            if(data != ''){
		            	
		            	waitContent(data);
		              
		            }
		       }).fail(function(){ 
		           
		        });
		    }
	    
	    }
	}
	
	
	function do_calibration(){
		
		IS_MACRO_ON = true;
		openWait('Calibration in process');
		
		var now = jQuery.now();
		ticker_url = '/temp/macro_trace'; 
		
		
		
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('maintenance').'ajax/bed_calibration.php' ?>",
			data : {time: now, num_probes : num_probes, skip_homing: skip_homing},
			dataType: "html"
		}).done(function( data ) {
			
			num_probes++;
			skip_homing = 1;
			
			closeWait();
			ticker_url = '';
			
			if($("#step2-1").is(":visible") ){
				
				$("#step2-1").slideUp('fast', function(){
					
					$("#step2-2").slideDown('fast');
					
				});
				
			}
			
			
			$(".bed-calibration-result-response").html(data);
			
			var reds = 0;
			var oranges = 0;
			var greens = 0;
			
			$('.screws-rows > tbody  > tr').each(function() {
				
				
				if($(this).hasClass('result-danger')){
					reds++;
				}
				
				if($(this).hasClass('result-warning')){
					oranges++;
				}
				
				if($(this).hasClass('success')){
					greens++;
				}
				
				
			});	
			
			if(reds > 0){
				isStep2Ok = false;
			}
			
			if(greens == 4 || reds == 0){
				isStep2Ok = true;
			}
			
			check_wizard();
			
			IS_MACRO_ON = false;
			
		});
		

	}
	
	
	function check_wizard(){
		
		
		var item = $('.wizard').wizard('selectedItem');
		
		switch(item.step){
			
			case 1:
				$('.btn-next').removeClass('disabled');
				break;
			case 2:
				if(isStep2Ok == false){
					$('.btn-next').addClass('disabled');
				}else{
					$('.btn-next').removeClass('disabled');
				}
				
				break;
			case 3:
				if(isStep3Ok == false){
					$('.btn-next').addClass('disabled');
				}else{
					$('.btn-next').removeClass('disabled');
				}
				break;
			case 4:
				if(isStep4Ok == false){
					$('.btn-next').addClass('disabled');
				}else{
					$('.btn-next').removeClass('disabled');
				}
				break;
				
			
		}
		
		
	}
	
	
	function prepare(){
		
		macro('prepare', 1);
	}
	
	
	function calibrate(){
		macro('calibrate', 2);
	}
	
	
	
	function macro(mode, index){
		
		IS_MACRO_ON = true;
		$(".re-choice").slideUp('slow');
		
		var message = mode == 'prepare' ? 'Preparing calibration, please wait' : 'Calibrating';
		
		openWait(message);
		$.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/probe_setup.php' ?>",
              data: { mode: mode},
              dataType: 'json',
              async: true
        }).done(function( response ) {
        	              
            $("#row-normal-" + index).slideUp('slow', function(){
				$("#row-normal-" + (index+1)).slideDown('slow');
				
				closeWait();
				if(mode == 'prepare'){
					jog_make_call('mdi', 'G91');
				}
				
				if(mode == 'calibrate'){
					
					$("#calibrate-trace").html(response.trace);
					isStep3Ok = true;
				}
				
				
				check_wizard();
				
			});
			
			
			IS_MACRO_ON = false;
			
            
            
        });
		
	}
	
	
	
	function move_z(){
		
		var sign = $(this).attr('data-action');
		var value = $("#z-value").val();
		
		var gcode = 'G0 Z' + sign + value;
		
		jog_make_call('mdi', gcode);
		
		
		
		
	}
	
	
	function jog_make_call(func, value){  
		
		
		IS_MACRO_ON = true;
		$(".z-action").addClass('disabled');
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('jog').'ajax/exec.php' ?>",
			data : {function: func, value: value},
			dataType: "json"
		}).done(function( data ) {
	       $(".z-action").removeClass('disabled'); 
	       IS_MACRO_ON = false;
		});
		
	}
	
	
	function do_again(){
		
		$("#calibrate-trace").html('');
		
		$(".calibration").slideUp('fast', function(){
			$(".choice").slideDown('fast');
		});
	
		
	}
	
	
	function get_probe_length(){
		
		
		if(probe_length <= 0){
			
			IS_MACRO_ON = true;
			openWait('please wait');
			
			
			
			$.ajax({
				type: "POST",
				url : "<?php echo module_url('maintenance').'ajax/probe_length.php' ?>",
				dataType: "json"
			}).done(function( data ) {
		       
		       	
		       	
		       	$( ".choice" ).slideUp( "slow", function() {});
	            $("#row-fast-1").slideDown('slow');
	            $(".re-choice").slideDown('slow');
	           	$(".start").slideDown('slow');
	           	closeWait();
	           	
	           	probe_length = data.probe_length;
	           	$("#probe-lenght").html(Math.abs(data.probe_length));
		       isStep3Ok = true;
		       check_wizard();
		       IS_MACRO_ON = false;
		       
			});
		
		}else{
			
				$( ".choice" ).slideUp( "slow", function() {});
	            $("#row-fast-1").slideDown('slow');
	            $(".re-choice").slideDown('slow');
	           	$(".start").slideDown('slow');
			
		}
		
	}
	
	
	
	function override_probe_length(){
		
		IS_MACRO_ON = true;
		$(".re-choice").slideUp('slow');
		openWait('please wait');
		
		
		$.ajax({
				type: "POST",
				url : "<?php echo module_url('maintenance').'ajax/override_probe_lenght.php' ?>",
				dataType: "json",
				data : {over : $("#over").val()}
			}).done(function( data ) {
		       
		       
		       var html = 'Calibrating probe\n';
		       html += '====================================\n';
		       html += 'Old Probe Length: ' + Math.abs(data.old_probe_lengt) + '\n';
		       html += 'Override value: ' +  data.over + '\n';
		       html += '====================================\n';
		       html += 'New Probe Length: ' + data.probe_length;
		       
		       $("#over-calibrate-trace").html(html);
		       $("#row-fast-1").slideUp('fast', function(){
		       		 $("#row-fast-2").slideDown();
		       });
		      
		       closeWait();
		       IS_MACRO_ON = false;
		       
		       
			});
		
		
	}
	
	
	
	function prepare_feeder(){
		
		IS_MACRO_ON = true;
	 	openWait('Preparing procedure');
	 	$.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/feeder.php' ?>",
              dataType: 'json'
        }).done(function( response ) { 
			
			var status = response.status;
                
                if(status == 200){
                	
                	$(".step-1").hide();
                	$(".step-2").show();
                	
                	isStep4Ok = true;
                	check_wizard();
                    
                }else{
                   
                    $.smallBox({
						title : "Warning",
						content: response.trace,
						color : "#C46A69",
						icon : "fa fa-warning",
		                timeout: 15000
		            });
                        
                }
                
			closeWait();
			IS_MACRO_ON = false;
  
        });
	 }
	 
	 
	 
	 function finish_wizard(){
	 	
	 	IS_MACRO_ON = true;
	 	openWait('Finalizing wizard');
	 	$(".finish").addClass('disabled');
	 	
	 	$.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/finish_wizard.php' ?>",
              dataType: 'json'
        }).done(function( response ) {
        	IS_MACRO_ON = false;
			setTimeout(function () {document.location.href = '/fabui';}, 3000);
        })
	 	
	 	
	 	
	 }
	 
	 
	 function set_head_img(){
		$("#head_img").attr('src', '<?php echo module_url('maintenance') ?>assets/img/head/' + $(this).val() + '.png');	 
	 }
	
	
	
	
</script>