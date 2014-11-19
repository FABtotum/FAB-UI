<script type="text/javascript">
	
	var ticker_url = '';
	var interval_ticker;

	$(function () {
		
		$(".do-calibration").on('click', do_calibration);
		interval_ticker   = setInterval(ticker, 500);
		
		
	});
	
	
	
	function ticker(){
		
	    if(ticker_url != ''){
	        
	         $.get( ticker_url , function( data ) {
	           
	            if(data != ''){
	            	
	            	waitContent(data);
	              
	            }
	       }).fail(function(){ 
	           
	        });
	    }
	}
	
	
	
	
	function do_calibration(){
		
		openWait('Calibration in process');
		
		var now = jQuery.now();
		ticker_url = '/temp/bed_calibration_' + now + '.trace'; 
		
		
		
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('maintenance').'ajax/bed_calibration.php' ?>",
			data : {time: now},
			dataType: "html"
		}).done(function( data ) {
			
			
			closeWait();
			ticker_url = '';
			
			if($(".step-1").is(":visible") ){
				
				$(".step-1").slideUp('fast', function(){
					
					$(".step-2").slideDown('fast');
					
				});
				
			}
			
			$(".todo").html(data);
			
			
			
		});
		
		
		
	}	

	
</script>