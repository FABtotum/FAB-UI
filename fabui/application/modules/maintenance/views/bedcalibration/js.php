<script type="text/javascript">
	
	var ticker_url = '';
	var interval_ticker;
	var num_probes = 1;
	var skip_homing = 0;

	$(function () {
		
		$(".do-calibration").on('click', do_calibration);
		interval_ticker   = setInterval(ticker, 500);
		
		
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
		
		openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Calibration in process');
		IS_MACRO_ON = true;
		IS_TASK_ON = true;
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
			
			if($(".step-1").is(":visible") ){
				
				$(".step-1").slideUp('fast', function(){
					
					$(".step-2").slideDown('fast');
					
				});
				
			}
			
			$(".result-response").html(data);
			
			IS_MACRO_ON = false;
			IS_TASK_ON = false
			
		});
		
		
		
	}	

	
</script>