<?php 
require_once '/var/www/lib/utilities.php';
$networkConfiguration = networkConfiguration();
$imOnCable = $_SERVER['SERVER_ADDR'] == $networkConfiguration['eth'] ? true : false;

?>
<script src="/assets/js/app.config.js?"></script>
<script src="/assets/js/bootstrap/bootstrap.min.js"></script>
<script src="/assets/js/notification/SmartNotification.min.js"></script>
<script src="/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
<script src="/assets/js/plugin/fastclick/fastclick.min.js"></script>
<script src="/assets/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<!--[if IE 7]>
	<h1>
		Your browser is out of date, please update your browser by going to www.microsoft.com/download
	</h1>
<![endif]-->
<!-- MAIN APP JS FILE -->
<script src="/assets/js/app.min.js"></script>
<script type="text/javascript">
	
    
    runAllForms();
    
    var ask_wifi_password = false;
    var wifi_password;
    var wifi_ssid;

	$(document).ready(function() {

		pageSetUp();

		$('.table thead').css('cursor', 'pointer');

		$('.table thead').on('click', function() {

			var display = $(this).parent().find('tbody').css('display');

			var th = $(this).find(':first-child').find(':last-child').find('i');

			if (display == 'none') {

				$(this).parent().find('tbody').show('fast', function() {

					th.removeClass('fa-angle-double-down').addClass('fa-angle-double-up');

				});
			} else {

				$(this).parent().find('tbody').hide('fast', function() {
					th.removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
				});
			}

		});



		$('.net').on('click', function() {

			var tr = $(this).parent().parent().parent();
			
			var selected = tr.find(':first-child').find('input').prop("checked", true);
			
            ask_wifi_password = selected.attr('data-password') == 'true' ? true : false;
            wifi_ssid = selected.attr('value');
            
            
            
            
		});
		
		
		$('input:radio').on('click', function() {
			
			
			
			var tr = $(this).parent().parent().parent();
			
			var selected = tr.find(':first-child').find('input').prop("checked", true);
			
            ask_wifi_password = selected.attr('data-password') == 'true' ? true : false;
            wifi_ssid = selected.attr('value');
			
		});



		var $validator = $("#wizard-1").validate({

			rules: {
				email: {
					required: true,
					email: "Your email address must be in the format of name@domain.com"
				},
				first_name: {
					required: true
				},
				last_name: {
					required: true
				},
				password: {
					required: true
				},
				lan: {
					required: true
				},
				ip_address : {
					digits: true,
					required : true,
					max: 255,
					min: 1
				}

			},
			messages: {
				fname: "Please specify your First name",
				lname: "Please specify your Last name",
				email: {
					required: "We need your email address to contact you",
					email: "Your email address must be in the format of name@domain.com"
				}
			},

			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			}
		});



		$('#bootstrap-wizard-1').bootstrapWizard({
			'tabClass': 'form-wizard',
			'onPrevious': function(tab, navigation, index){
				$(".next").find('a').html('Next');
			},
			'onNext': function(tab, navigation, index) {
				var $valid = $("#wizard-1").valid();
				$(".next").find('a').html('Next');
				if (!$valid) {
					$validator.focusInvalid();
					return false;
				} else {
				    
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
                   
                   	
                    if(index == 3){

                        if(ask_wifi_password == true){
                            
                            $.SmartMessageBox({
            					title : "<i class='fa fa-wifi'></i> Wifi password",
            					content : "Please enter wifi password",
            					buttons : "[Submit]",
            					input : "password",
            					placeholder : "password"
            				}, function(ButtonPress, Value) {
            					if(Value != ''){
            						wifi_password = Value;
            						ask_wifi_password = false;
            						
            						$("#net_password").val(wifi_password);
            						$(".next").trigger('click');
            						
            					}
                                return 0;

            				});
                            return false;
                        }
                     
                      
                       $(".next").find('a').attr('style', 'cursor: pointer !important;');
                       $(".next").find('a').html('Install');		
                       
                        
                    }
                    
                    if(index == 4){

                    	install();
                    /*	
                       $("#wizard-1").submit();
                       $(".next").find('a').html('Installing...');
                       $("a").addClass('disabled');
                      */ 
                    }
				}
			},
            'onLast' : function(){
                return false;
            },
            'onTabClick' : function () {
                
                return false;
            }
		});
		
		
		
		
		function check_connection(){
			
			
			$.ajax({
		          url: "ajax/wifi.php",
		          data:{ssid: wifi_ssid, password: wifi_password},
		          type: "POST"
		        }).done(function( html ) {
		           
		        });
			
			
		}
		
		
		
		
function install(){

	$(".next").find('a').html('<i class="fa fa-spinner fa-pulse"></i>');
	$("a").addClass('disabled');
	
	$.ajax({
		type: "POST",
		url: "install.php",
		dataType: 'json',
		data: { first_name : $("#first_name").val(), last_name : $("#last_name").val(), email: $("#email").val(), password : $("#password").val(), net_password : $("#net_password").val(), net: '', ip_address: $("#ip_address").val() }
	}).done(function( response ) {
		document.location.href= 'http://169.254.1.' + $("#ip_address").val();
	});
	
	
	if($("#ip_address").val() != ip_last_num){
		
		console.log("aggiorno tra 60 secondi");
		
		setTimeout(function () {
			document.location.href= 'http://169.254.1.' + $("#ip_address").val();
		}, 60000);
	}

	 	
}


	});
</script>
</body>

</html>