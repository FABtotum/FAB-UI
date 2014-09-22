<script type="text/javascript">
	
	var ask_wifi_password = false;
    var wifi_password;
    var wifi_ssid = '';
    
    
    $(document).ready(function() {
    	
    	
   
    
	    $('.net').on('click', function() {
	
				var tr = $(this).parent().parent().parent();
				
				var selected = tr.find(':first-child').find('input').prop("checked", true);
				
	            ask_wifi_password = selected.attr('data-password') == 'true' ? true : false;
	            wifi_ssid = selected.attr('value');
	            
	            
	            ask_password(false);

		});
		
		
		
		$('input:radio').on('click', function() {
				
				var tr = $(this).parent().parent().parent();
				
				var selected = tr.find(':first-child').find('input').prop("checked", true);
				
	            ask_wifi_password = selected.attr('data-password') == 'true' ? true : false;
	            wifi_ssid = selected.attr('value');
	            ask_password(false);
				
			});
			
			
			
			
			$("#save-button").on('click', function(){
				
				
				if(wifi_ssid == ''){
					
					$.smallBox({
	    				title : "Warning",
	    				content : "Please select a Wifi Network",
	    				color : "#C46A69",
	    				iconSmall : "fa fa-warning shake animated",
	                    timeout : 4000
           			 });
					return false;
				}
				
				
				if(ask_wifi_password == true){
					ask_password(true);
					return false;
				}
				
				
				$("#save-button").addClass('disabled');
				$("#save-button").html('Saving and restarting net');
				
				$.ajax({
		              type: "POST",
		              url: "<?php echo module_url("settings").'ajax/network.php' ?>",
		              data: { net: wifi_ssid, password: $("#net_password").val() },
		              dataType: 'json'
		        }).done(function( response ) {
		        	
		        	$("#wifi-ip").html(response.wlan_ip);
		        	$("#wifi-ip").attr('href','http://' + response.wlan_ip);
		            
		            $("#save-button").removeClass('disabled');
					$("#save-button").html('<i class="fa fa-save"></i> Save');
					
					$.smallBox({
		    				title : "Success",
		    				content : "<i class='fa fa-check'></i> Network settings saved",
		    				color : "#659265",
		    				iconSmall : "fa fa-thumbs-up bounce animated",
		                    timeout : 4000
		            });
		               
		        });
				
				
       

			
			
			
		})
			
		
	 });
	 
	 
	 
	 function ask_password(trigger){
	 	
	 	
	 		$.SmartMessageBox({
					title : "Wifi",
					content : "Please enter wifi password",
					buttons : "[Cancel][Save]",
					input : "password",
					placeholder : "password"
				}, function(ButtonPress, Value) {
					
					if(ButtonPress == 'Save'){
						if(Value != ''){
							wifi_password = Value;
							ask_wifi_password = false;
							$("#net_password").val(wifi_password);
							
							if(trigger == true){
								$("#save-button").trigger('click');
							}
							
							
						}
	                    return 0;
					}
					
					
					if(ButtonPress == 'Cancel'){
						
					}

				});
	 	
	 }
	
	
	
	
</script>