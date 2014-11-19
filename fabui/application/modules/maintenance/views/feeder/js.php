<script type="text/javascript">
	
	
	 $(function () {
	 	
	 	
	 	
	 	$(".prepare-engage").on('click', prepare);
	 	
	 	
	 	
	 });
	 
	 
	 
	 function prepare(){
	 	
	 	
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
                   
                    
                }else{
                   
                    $.smallBox({
						title : "Warnign",
						content: response.trace,
						color : "#C46A69",
						icon : "fa fa-warning",
		                timeout: 15000
		            });
                        
                }
                
			
			
			
			closeWait();
  
        });
	 	
	 	
	 	
	 }






</script>