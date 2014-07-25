<!--==================================================-->
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices) <script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="/assets/js/libs/jquery-2.0.2.min.js" ></script>
<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events <script src="/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
<!-- BOOTSTRAP JS -->
<script src="/assets/js/bootstrap/bootstrap.min.js">
</script>
<!-- CUSTOM NOTIFICATION -->
<script src="/assets/js/notification/SmartNotification.min.js">
</script>
<!-- JARVIS WIDGETS <script src="/assets/js/smartwidgets/jarvis.widget.min.js"></script>
<!-- EASY PIE CHARTS -->
<script src="/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js">
</script>
<!-- SPARKLINES -->
<script src="/assets/js/plugin/sparkline/jquery.sparkline.min.js">
</script>
<!-- JQUERY VALIDATE -->
<script src="/assets/js/plugin/jquery-validate/jquery.validate.min.js">
</script>
<!-- JQUERY MASKED INPUT -->
<script src="/assets/js/plugin/masked-input/jquery.maskedinput.min.js">
</script>
<!-- JQUERY SELECT2 INPUT -->
<script src="/assets/js/plugin/select2/select2.min.js">
</script>
<!-- JQUERY UI + Bootstrap Slider -->
<script src="/assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js">
</script>
<!-- browser msie issue fix -->
<script src="/assets/js/plugin/msie-fix/jquery.mb.browser.min.js">
</script>
<!-- FastClick: For mobile devices -->
<script src="/assets/js/plugin/fastclick/fastclick.js">
</script>
<!-- FastClick: For mobile devices -->
<script src="/assets/js/fabtotum.js">
</script>
<script src="/assets/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js">
</script>
<!--[if IE 7]>
	<h1>
		Your browser is out of date, please update your browser by going to www.microsoft.com/download
	</h1>
<![endif]-->
<!-- MAIN APP JS FILE -->
<script src="/assets/js/app.js">
</script>
<script type="text/javascript">
	
    
    runAllForms();
    
    var ask_wifi_password = false;

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
			tr.find(':first-child').find('input').prop("checked", true);
            ask_wifi_password = tr.find(':first-child').find('input').attr("data-password") == "true" ? true : false;

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
			'onNext': function(tab, navigation, index) {
				var $valid = $("#wizard-1").valid();
				if (!$valid) {
					$validator.focusInvalid();
					return false;
				} else {
				    
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
                    
                    if(index == 3){
                       
                       
                        
                        if(ask_wifi_password == true){
                            
                            $.SmartMessageBox({
            					title : "Wifi",
            					content : "Please enter wifi password",
            					buttons : "[Accept]",
            					input : "password",
            					placeholder : "password"
            				}, function(ButtonPress, Value) {
            					alert(ButtonPress + " " + Value);
                                return 0;
                                
                                
            				});
                
                            return false;
                        }
                        else{
                          $(".next").removeClass('disabled');
                          return true;
                            
                        }
                        
                    }
                    
                    if(index == 4){
                       $("#wizard-1").submit(); 
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







	});
</script>
</body>

</html>