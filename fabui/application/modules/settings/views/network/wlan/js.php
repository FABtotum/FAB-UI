<script type="text/javascript">
	
	
	var mac_address = '<?php echo $info['ip_address'] != '' ? $info['ap_mac_address'] : ''?>';
	
	var essid = '';
	var password = '';
	
	var imOnWifi = <?php echo $info['ip_address'] == $_SERVER['SERVER_ADDR'] ? 'true' : 'false'; ?>;
	
	$(document).ready(function() {

		scan();
		$('.progress-bar').progressbar({});
		$("#scan").on('click', scan);
		
		$("#confirm-password").on('click', confirm_password);
		
		$(".password").on('click', show_password);
		
		$("#wifi-switch").on('click', switch_wlan);
		
		$("#hidden").on('click', hidden_wifi_modal);
		
		$("#hidden-connect").on('click', hidden_connect);
		
		$('.connect').on('click', start_connection);
		$('.disconnect').on('click', start_connection);
		$('.progress-bar').progressbar({display_text : 'fill'});
		
		$('.show-details').on('click', details);
	
	});
	
	
	function scan(){

		$(".btn").addClass('disabled');
		$(".table-container").css('opacity', '0.1');
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/wifi_scan.php' ?>',
		  	dataType : 'html',
		  	type: 'post',
          	data: {mac_address: mac_address}
		}).done(function(reponse) {
			
		 	$(".btn").removeClass('disabled');
		 	$(".table-container").html(reponse);
		 	$('.progress-bar').progressbar({display_text : 'fill'});
		 	$('.connect').on('click', start_connection);
		 	$('.disconnect').on('click', start_connection);
		 	$(".table-container").css('opacity', '1');
		 	
		 	
        });
	}
	
	
	function start_connection(){
		
		var action = $(this).attr('data-action');

		
		
		if(action == 'disconnect'){
			asck_disconnect($(this).attr('data-ssid'));
			return false;
		}
		
		var protected = $(this).attr('data-protected') == 'on' ? true : false;
		
		essid  = $(this).attr('data-ssid');
		$("#password").val('');
		$("#essid").val($(this).attr('data-ssid'));
		$("#type").val($(this).attr('data-type'));
		$("#action").val(action);

		
				
		if(protected){
			
			$('#show-password').prop('checked', false); 
			$(".password-modal-title").html(essid);
			$("#modal-password-input").val('');
			password_modal();
		}else{
			connect(action);
		}
		
	}
	
	
	function password_modal(){
		
		$(".password-input").attr('type', 'password');
		$(".checkbox").attr('checked', false); 
		$("#modal-password-input").val('');
		
		$('#password-modal').modal({
			keyboard : false
		});
	}
	
	
	function hidden_wifi_modal(){
		$(".password-input").attr('type', 'password');
		$(".checkbox").attr('checked', false); 
		
		$("#hidden-ssid-input").val('');
		$("#hidden-password-input").val('');
		
		$('#hidden-wifi-modal').modal({
			keyboard : false
		});
	}
	
	function confirm_password(){
		
		$("#password").val($("#modal-password-input").val());
		$('#password-modal').modal('hide');
		setTimeout(connect('connect'), 1000);
		
	}
	
	
	
	function hidden_connect(){
		
		if($("#hidden-ssid-input").val() == ''){
			$("#hidden-ssid-input").next('p').addClass('txt-color-red');
			$("#hidden-ssid-input").focus();
			return false;
			
		}
		
		$("#essid").val($("#hidden-ssid-input").val());
		$("#password").val($("#hidden-password-input").val());
		$("#action").val('connect');
		$('#hidden-wifi-modal').modal('hide');
		setTimeout(connect('connect'), 1000);
		
	}
	
	
	function show_password(){
		
		var type = $(this).is(":checked") ? 'text' : 'password';
		$(".password-input").attr('type', type);
		
	}
	
	
	function connect(action){
		
		
		var connection_label = action == 'connect' ? 'Connecting' : 'Disconnecting';
		
		openWait('<i class="fa fa-circle-o-notch fa-spin"></i> ' + connection_label);
		
		var timeout = !imOnWifi ? 180000 :  60000;
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/wifi_connect.php' ?>',
		  	dataType : 'json',
		  	type: 'post',
		  	timeout: timeout,
          	data: {essid: $("#essid").val(), password:$("#password").val(), type:$("#type").val(), action:action}
		}).done(function(data) {
			
			console.log(data);
			$("#response").val(data.response);
			$("#action").val(action);
			$("#connect-form").submit(); 
        }).fail(function(jqXHR, textStatus) {
        	
        	if(textStatus == 'timeout' && imOnWifi){
        		waitHideEmergencyButton();
        		waitTitle('<i class="fa fa-check"></i> Connected!');
        		waitContent('The FABtotum is now connected to ' + $("#essid").val() + '.\nTo get the new IP address of the WiFi connect to the FABtotum via ethernet cable');		
        	}
        	
        });
	}
	
	
	function switch_wlan(){
		
		var action = $(this).is(':checked') ? 'on' : 'off';	
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/switch_wifi.php' ?>',
		  	dataType : 'html',
		  	type: 'post',
          	data: {action: action}
		}).done(function(reponse) { 	
		 	
        });
		 
	}
	
	
	function asck_disconnect(essid) {
		
		$("#essid").val(essid);	
		$.SmartMessageBox({
				title : '<i class="fa fa-wifi"></i> ' + essid,
				content : "Are you sure you want to disconnect from the WiFi network ? ",
				buttons : '[Cancel][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") {
					connect('disconnect');
				}
				if (ButtonPressed === "No") {
				}
			});
	}
	
	
	function details(){
		/*$(".net-details").show().addClass('animated fadeIn');*/
		
		var button = $(this);
		
		if(button.attr('data-action') == 'down'){
			$(".net-details").slideDown('fast', function(){
				button.attr('data-action', 'up');
				button.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
			});
		}else{
			$(".net-details").slideUp('fast', function(){
				button.attr('data-action', 'down');
				button.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
			});
		}
		
		
		
		
	}
	
</script>