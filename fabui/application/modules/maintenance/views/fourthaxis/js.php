<script type="text/javascript">


	var ticker_url = '';
	var interval_ticker;
	
	
	$(function () {
		
		$(".do-engage").on('click', do_engage);
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
	
	
	
	function do_engage(){
		
		openWait('Engaging in process');
		IS_MACRO_ON = true;
		
		var now = jQuery.now();
		ticker_url = '/temp/macro_trace'; 
		
		
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('maintenance').'ajax/4axis_engage.php' ?>",
			data : {time: now},
			dataType: "json"
		}).done(function( data ) {
			
			
			closeWait();
			ticker_url = '';
			IS_MACRO_ON = false;
			
			
		});
		
		
		
	}
	

</script>