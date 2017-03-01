<script type="text/javascript">
	
	var imOnHostname = <?php echo $_SERVER['SERVER_NAME'] != $_SERVER['SERVER_ADDR'] ? 'true' : 'false' ?>;
	
	$(document).ready(function() {
		
		
		jQuery.validator.addMethod("notEqual", function(value, element, param) {
		  return this.optional(element) || value != param;
		}, "The requested operation is invalid because redundant. Please enter a different hostname");
		
		
		$('#hostname-form').validate({
			onkeyup: false,
			onfocusout: false,
			
			rules : {
				hostname : {
					required : true,
					notEqual: $("#hostname").val()
				}
			},

			messages : {
				hostname : {
					required : 'Please enter a hostname'
				}
			},
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
		
		$('#save').on('click', ask);
		$("#hostname-form").on('submit', function(){return false;});
		
	});
	
	function ask(){
		
		
		if($("#hostname-form").valid()){
			
			 $.SmartMessageBox({
				title: "<i class='fa fa-warning txt-color-orangeDark'></i> Warning!",
				content: "Do you really want to set <b>" + $("#hostname").val() + "</b> as the new name for the FABtotum Personal Fabricator ?",
				buttons: '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") {

					save();
				}
				if (ButtonPressed === "No") {

				}

			});
		}
		
		
		
	}
	
	
	function save(){
		
		openWait("<i class=\"fa fa-circle-o-notch fa-spin\"></i> Setting new name");
		
		var timeout = imOnHostname ? 30000 : 60000;
		
		$.ajax({
        	url : '<?php echo module_url('settings').'ajax/set_hostname.php' ?>',
		  	dataType : 'html',
		  	timeout: timeout,
		  	type: 'post',
          	data: {hostname: $("#hostname").val(), name:$("#name").val()}
		}).done(function(reponse) {
			
			if(!imOnHostname){
				waitContent(reponse);
				$("#response").val("ok");
				$("#new_hostname").val($("#hostname").val());
				$("#response-form").submit();
			}else{
				waitContent("Redirect...");
				document.location.href='http://' + $("#hostname").val()+'.local/';
			}

        }).fail(function(jqXHR, textStatus) {
        	
        	if(textStatus == 'timeout' && imOnWifi){
        		waitHideEmergencyButton();
        		waitContent("Redirect...");
        		document.location.href='http://' + $("#hostname").val()+'.local/';		
        	}
        	
        });
		
		
	}
	
	
</script>