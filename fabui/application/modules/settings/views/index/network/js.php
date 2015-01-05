<script type="text/javascript">
	
	
    
    var row_selected = 0;
    var need_password = false;
    var address = '';
    
    $(document).ready(function() {
    	
    	
    	$('.progress-bar').progressbar({
			display_text : 'fill'
		});

    	
    	
    	$(".table tbody tr").on('click', click_row);
   
		$("#eth-save-button").on('click', setEth);
		$("#wifi-save-button").on('click', setWifi);
			
		
	 });
	 

	 
	 
	 function setWifi(){
	 	
	 	if(row_selected <= 0 ){
	 		
	 		showAlert('Please select at least one wifi connection');
	 		return false;
	 	}
	 	
	 	if(need_password && $("password_" + row_selected).val() == ''){
	 		showAlert('Please insert password for this wifi connection');
	 		return false;
	 	}
	 	
	 	/** disable all systems call */
	 	do_system_call = false;
	 	openWait('Saving and restarting net');
	 	
		$("#wifi-save-button").addClass('disabled');
	 	
	 	var wifi_ssid = $("#net_" + row_selected).text();
	 	var wifi_password = $("#password_" + row_selected).val();
	 	
	 	
	 	$.ajax({
		        type: "POST",
		        url : "<?php echo site_url('settings/set-wifi'); ?>",
		        data: { net: wifi_ssid, password: wifi_password, address: address },
		        dataType: 'json'
		}).done(function( response ) {
		    
		    var title = '';
		    var content = '';
		    var color = '';
		    var icon = '';
		     
		    
		    $('.actual-wifi').remove();
		    
		     
		    if(response.response == 'OK'){
		    	
		    	reset_table();
		    	var counter = 1;
		    	
		    	$( "table > tbody > tr" ).each(function( index ) {
	 		
				  	if(counter == row_selected){
				  		
				  		$(this).find('td:first').append('<i class="fa fa-check pull-right actual-wifi"></i>');
				  	}
				  	
				  	counter++;
				  	
				});
		    	
		    	
	        	$("#wifi-ip").html(response.wlan_ip);
	        	$("#wifi-ip").attr('href','http://' + response.wlan_ip);
	        	$("#wifi-ssid-label").html(wifi_ssid);
	            
	            title = 'Success';
	            content = 'Network settings saved';
	            color = '#659265';
	            icon = 'fa-check';
	            
	            				
            }else{
            	
            	title = 'Warning';
	            content = 'Check the password  or check the network avaiability';
	            color = '#C46A69';
	            icon = 'fa-warning';
            	
            } 
            

            closeWait();
            
            $.smallBox({
	    			title : title,
	    			content : "<i class='fa" + icon +"'></i> " + content,
	    			color : color,
	    			iconSmall : "fa fa-thumbs-up bounce animated",
	                timeout : 5000
	        });
            
            $("#wifi-save-button").removeClass('disabled');
            $("#save-button").removeClass('disabled');
			$("#save-button").html('<i class="fa fa-save"></i> Save');
			
			/** enable system call */
			do_system_call = true;
            
		                
		 });
	 	
	 	
	 	
	 }
	 
	 function setEth(){
	 	
	 	
	 	openWait('Setting new configuration');
	 	
	 	$.ajax({
	 			<?php if(!$imOnCable): ?>
	 			dataType: 'json',
	 			<?php endif; ?>
		        type: "POST",
		        url: "<?php echo site_url('settings/set-eth'); ?>",
		        data: { number: $("#eth-endnumber").val() }
		}).done(function( response ) {
		
			
			<?php if(!$imOnCable): ?>
			
				
				closeWait(); 
				
				$.smallBox({
					title : "Success",
					content : "<i class='fa fa-check'></i> Network settings saved",
					color : "#659265",
					iconSmall : "fa fa-thumbs-up bounce animated",
	                timeout : 4000
		        });
		        
		        $("#eth-ip").html('169.254.1.' + $("#eth-endnumber").val());
				$("#eth-ip").attr('href', 'http://169.254.1.' + $("#eth-endnumber").val());
				
			
			<?php endif; ?>
			      
		});
		
		
		<?php if($imOnCable): ?>
		
	 	setTimeout(function(){
	 		
	 		document.location.href= 'http://169.254.1.' + $("#eth-endnumber").val() + '/fabui';
	 		
	 	}, 50000);
	 	
	 	<?php endif; ?>
	 	
	 	
	 }
	 
	 
	 
	 function click_row(){
	 	
	 	
	 	reset_table();
	 	
	 	var class_selected = 'success';
	 	
	 	need_password = false;
	 	
	 	var needPassword = $(this).attr('data-password') == 'true' ? true : false;
	 	
	 	address = $(this).attr('data-address');
	 	
	 		 	
	 	$( "table > tbody > tr" ).each(function( index ) {
	 		
		  	if(!$(this).hasClass('details')){
		  		$(this).removeClass(class_selected);
		  	}
		  	
		});
		
		
		
		var dataCount = $(this).attr('data-count');
		
	 	if(!needPassword){
	 		row_selected = parseInt(dataCount);
	 		$(this).addClass(class_selected);
	 		$('.details').hide();
	 		return;
	 	}
	 	
	 	
	 	
	 	/* check if is open */
	 	var open = $(this).next().is('.details') && $(this).next().is(':visible');
	 	
	 	/* close all */
	 	$('.details').hide();
	 	
	 	row_selected = 0;
	 	
	 	$('.arrow').removeClass("fa-chevron-down").addClass("fa-chevron-right");
	 	
	 	if(open) return;
	 	
	 	/* check if exists */
	 	var exist = false;
	 	if($(this).next().is(".details")){
	 		exist = true;
	 	}
	 
	 	if(exist){
	 		$(this).next().show();
	 		$(this).next().find('.details').show();
	 		
	 	}else{
	 		var html = '<tr class="details"><td class="details" colspan="2"><div class="fade in"><div class="form-inline"><label style="margin-right:5px">Password</label><input id="password_'+dataCount+'" type="password" class="form-control" placeHolder="Insert Password" /> </div> </div> </td></tr>'; 
	 		$(this).after(html);
	 	}
	 	
	 	$(this).find('.arrow').removeClass("fa-chevron-right").addClass("fa-chevron-down");
	 	row_selected = parseInt(dataCount);
	 	$(this).addClass(class_selected);
	 	need_password = needPassword;
	 	
	 	
	 	
	 }
	 
	 
	 
	 function showAlert(message){
	 	
	 	$.SmartMessageBox({
					title : "<i class='fa fa-warning txt-color-orange'></i> Wifi",
					content : message,
					buttons : "[Ok]",
		}, function(ButtonPress, Value) {});
		
	 }
	
	
	function reset_table(){
		
		
		var class_selected = 'success';
		
		$(".table").find('.details').hide();
		$(".table").find('.arrow').removeClass('fa-chevron-down').addClass('fa-chevron-right');
		
		$( "table > tbody > tr" ).each(function( index ) {
		  	
		  	if(!$(this).hasClass('details')){
		  		$(this).removeClass(class_selected);
		  	}
		  	
		});
		
		
		
	}
	
	
	
</script>