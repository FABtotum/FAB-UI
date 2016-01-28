<script type="text/javascript">


	var last_num_address = <?php echo explode('.', $info['inet_address'])[3] ?>;
	
	var imOnCable = <?php echo ($info['inet_address'] == $_SERVER['HTTP_HOST']) ? 'true' : 'false'; ?>;
	
	$(document).ready(function() {
		
		$("#new-ip-button").on('click', function() {
			$("#new-ip-form-container").show();
		});
		
		$("#save").on('click', save);
		
		
	});


	function save(){
		
		$("#save").addClass('disabled');
		openWait('Saving <i class="fa fa-spinner fa-pulse"></i>');
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/save_eth_ip.php' ?>',
		  	dataType : 'html',
		  	type: 'post',
		  	timeout: 60000,
          	data: {ip_num: $("#ip-num").val()}
		}).done(function(reponse) {
			
		 	$("#save").removeClass('disabled');
		 	closeWait();
		 	
        }).fail(function(jqXHR, textStatus) {
        	
        	if(textStatus == 'timeout' && (imOnCable && (last_num_address != $("#ip-num").val()))){
        		waitHideEmergencyButton();
        		waitTitle('<i class="fa fa-check"></i> New ip saved!');
        		document.location.href = 'http://169.254.1.' + $("#ip-num").val() + '/fabui'
        				
        	}
        	
        });
		
	}

</script>