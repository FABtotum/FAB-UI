<script type="text/javascript">
	
	 var choice = '';
	 var probe_length = 0;
	 var interval;
	
	$(function () {
		
		
		$(".jog").addClass("disabled");
		
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
		
		
		
		

		
	});
	
	
	
	function prepare(){
		
		macro('prepare', 1);
	}
	
	
	function calibrate(){
		macro('calibrate', 2);
	}
	
	
	
	function macro(mode, index){
		IS_MACRO_ON = true;
		$(".re-choice").slideUp('slow');
		var content = mode == 'preapre' ? 'Heating extruder and bed<br>This operation will take a while': '';
		openWait('Calibration', content);
		$.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/probe_setup.php' ?>",
              data: { mode: mode},
              dataType: 'json',
        }).done(function( response ) {
            if(response.response){
            	$("#row-normal-" + index).slideUp('slow', function(){
    				$("#row-normal-" + (index+1)).slideDown('slow');
    				
    				if(mode == 'prepare'){
    					jog_call('mdi', 'G91');
    				}
    				if(mode == 'calibrate'){	
    					$("#calibrate-trace").html(response.trace);
    				}
    			});
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
	
	
	
	function move_z(){
		
		var sign = $(this).attr('data-action');
		var value = $("#z-value").val();
		var gcode = 'G0 Z' + sign + value;
		
		
		jog_call('mdi', gcode);
		
		
		
		
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
		
			openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Please wait');
			
			IS_MACRO_ON = true;
			
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
	           	$("#z-max").html(Math.abs(data.z_max));
	           	
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
		
		$(".re-choice").slideUp('slow');
		openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Please wait');
		IS_MACRO_ON = true;
		
		$.ajax({
				type: "POST",
				url : "<?php echo module_url('maintenance').'ajax/override_probe_length.php' ?>",
				dataType: "json",
				data : {over : $("#over").val()}
			}).done(function( data ) {
				
		       var html = 'Override value: ' +  data.over + '\n';
		       html += '====================================\n';
		       html += 'Old Probe Length: ' + Math.abs(data.old_probe_lengt) + '\n';
		       html += 'Old Z Max: ' + Math.abs(data.old_z_max) + '\n';
		       html += '====================================\n';
		       html += 'New Probe Length: ' + data.probe_length+'\n';
		       html += 'New Z Max: ' + data.z_max;

		       $("#over-calibrate-trace").html(html);
		       $("#row-fast-1").slideUp('fast', function(){
		       		 $("#row-fast-2").slideDown();
		       });
		       closeWait();
		       IS_MACRO_ON = false;
			});
		
		
	}
	
	/*
	function write_to_console(text, type){
		$(".z-action").removeClass('disabled');
	}
	*/
	
	
</script>