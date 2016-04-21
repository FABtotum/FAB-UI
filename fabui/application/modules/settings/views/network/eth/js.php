<script type="text/javascript">


	var ip_address = '<?php echo $info['inet_address'] ?>';
	
	var imOnCable = <?php echo ($info['inet_address'] == $_SERVER['SERVER_ADDR']) ? 'true' : 'false'; ?>;
	
	$(document).ready(function() {
		
		$("#new-ip-button").on('click', function() {
			$("#new-ip-form-container").show();
		});
		
		$("#save").on('click', save);
		$("#ip").inputmask();

	});


	function save(){
		
		if(!is_valid_ip()){
			alert('Please use an address in the range 169.254.X.X (169.254.0.0 – 169.254.254.254)');
			return false;
		}
		
		if(ip_address == $("#ip").val()){
			return false;
		}
		
		$("#save").addClass('disabled');
		openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Saving new IP address');
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/save_eth_ip.php' ?>',
		  	dataType : 'html',
		  	type: 'post',
		  	timeout: 60000,
          	data: {ip: $("#ip").val()}
		}).done(function(reponse) {
			
			document.location.href =  '<?php echo site_url('settings/network/eth'); ?>?ip_changed';
		 	
        }).fail(function(jqXHR, textStatus) {
        	
        	if(textStatus == 'timeout' && (imOnCable && (ip_address != $("#ip").val()))){
        		
        		waitTitle('<i class="fa fa-check"></i> New IP address saved');
        		document.location.href = 'http://' + $("#ip").val() + '/fabui/settings/network/eth?ip_changed'
        				
        	}
        	
        });
		
	}
	
	function is_valid_ip(){
		
		var ip = $("#ip").val();
		ip = ip.split('.');
		if(ip[0] != 169 || ip[1] != 254){
			return false;
		}
		if(ip[2] > 254 || ip[3] > 254){
			return false;
		}
		return true;
	}

</script>