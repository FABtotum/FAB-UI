<script type="text/javascript">
	
	
	
	
	$(function () {
		
		$("#probe-calibration-prepare").on('click', prepare);
		$("#probe-calibration-calibrate").on('click',  calibrate);
		$("#calibrate-again").on('click', do_again);
		
		$(".z-action").on('click', move_z);
		
		$("#z-value").spinner({
				step : 0.01,
				numberFormat : "n",
				max: 1,
				min: 0
		});

		
	});
	
	
	
	function prepare(){
		
		macro('prepare', 1);
	}
	
	
	function calibrate(){
		macro('calibrate', 2);
	}
	
	
	
	function macro(mode, index){
		
		
		var message = mode == 'prepare' ? 'Preparing calibration, please wait' : 'Calibrating';
		
		openWait(message);
		$.ajax({
              type: "POST",
              url: "<?php echo module_url("settings").'ajax/probe_setup.php' ?>",
              data: { mode: mode},
              dataType: 'json',
              async: true
        }).done(function( response ) {
        	              
            
            
            
            $("#row-" + index).slideUp('slow', function(){
				$("#row-" + (index+1)).slideDown('slow');
				
				closeWait();
				if(mode == 'prepare'){
					jog_make_call('mdi', 'G91');
				}
				
				if(mode == 'calibrate'){
					
					$("#calibrate-trace").html(response.trace);
					
				}
				
				
			});
			
			
            
            
        });
		
	}
	
	
	
	function move_z(){
		
		var sign = $(this).attr('data-action');
		var value = $("#z-value").val();
		
		var gcode = 'G0 Z' + sign + value;
		
		jog_make_call('mdi', gcode);
		
		
		
		
	}
	
	
	function jog_make_call(func, value){  

		$(".z-action").addClass('disabled');
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('jog').'ajax/exec.php' ?>",
			data : {function: func, value: value},
			dataType: "json"
		}).done(function( data ) {
	       $(".z-action").removeClass('disabled'); 
		});
		
	}
	
	
	function do_again(){
		
		$("#calibrate-trace").html('');
		
		
		$("#row-3").slideUp('fast', function(){
			
			
			$("#row-1").slideDown('fast');
			
		});
		
		
	}
	
	
	
	
</script>