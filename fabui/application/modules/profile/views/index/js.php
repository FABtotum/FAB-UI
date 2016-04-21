<script type="text/javascript">


	var avatar;
	var avatar_change = 0;
	var theme_skin= '<?php echo $_SESSION['user']['theme-skin'] ?>';
		
	
	$(function() {
		
		
		 $("#uploadFile").on("change", function(){
		 	
		 	
		 	avatar_change = 1;
		 	
	        var files = !!this.files ? this.files : [];
	        if (!files.length || !window.FileReader) return; 
	 
	        if (/^image/.test( files[0].type)){
	            var reader = new FileReader(); 
	            reader.readAsDataURL(files[0]); 
	 
	            reader.onloadend = function(){
	               
	               	avatar = this.result;
	                $("#img-preview").attr('src', this.result);
	                
	            }
	        }
    	});
		
		
		
		$("#select-image").click(function() {
			$("#uploadFile").trigger('click');
		});
		
		$('.thumbnail').click(function(){
			$("#uploadFile").trigger('click');
		});
		
		$("#remove-image").click(function() {
			$("#uploadFile").val("");
			avatar = '';
			avatar_change = 1;
			$("#img-preview").attr('src', '<?php echo base_url()."application/layout/assets/img/male.png"; ?>');
		});
		
		
		var $validator = $("#basic-info-form").validate({

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
		
		
		
		var $validator_password = $("#password-form").validate({

			rules: {
				old_password: {
					required: true
				},
				new_password: {
					required: true
				},
				confirm_new_password: {
					required: true,
					equalTo : '#new_password'
				}

			},
			messages: {
				old_password: "Please enter your password",
				new_password: "Please enter your new password",
				confirm_new_password: {
					
					required: "Please confirm yuor new password",
					equalTo: 'Please enter the same password as above'
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
		
		
		
  		
	});
	
	
	$("#save-button").click(function(){
		
		
		
		var active_tab = $("#myTabContent1").find('.active').attr("id");
		
		
		
		
		if(active_tab == 'password-tab'){
			
			$valid = $("#password-form").valid();
			
			if($valid){
				save_password();
			}
			
			
		}else{
			
			var $valid = $("#basic-info-form").valid();
		
			if(!$valid){
				
				return false;
			}
			
			
			save_basic_info();
			
		}
		
		
		
		
		
		
	});
	
	
	
	$("input[name='theme_skin']").click(function() {

		$.root_.removeClassPrefix('smart-style').addClass($(this).attr("value"));
		
		theme_skin = $(this).attr("value");
	
		var new_image = $(this).attr("value") == 'smart-style-0' ? 'logo-0.png' : 'logo-3.png';
		var src = $('#logo').find('img').attr('src');
	
		var t = src.split('/');
	
		var old_image = t[t.length - 1];
	
		if (new_image != old_image) {
	
			$('#logo').find('img').attr('src', src.replace(old_image, new_image));
	
		}
	});
		
	
	
	function save_basic_info(){
		
		 
		$("#save-button").addClass('disabled');
		$("#save-button").html('Saving..');
		
		$.ajax({
          url: '<?php echo module_url('profile').'ajax/basic_info.php'; ?>',
          data:{ first_name: $("#first_name").val(), last_name: $("#last_name").val(), email: $("#email").val(), avatar: avatar, 
          			avatar_change: avatar_change, theme_skin : theme_skin, lock_screen : $("#lock_screen").val(), 
          			header_fixed: $('#smart-fixed-header').prop('checked'), navigation_fixed: $('#smart-fixed-navigation').prop('checked'),
          			ribbon_fixed: $('#smart-fixed-ribbon').prop('checked'), footer_fixed: $('#smart-fixed-footer').prop('checked'), menu_on_top: $('#smart-top-menu').prop('checked')},
          type: "POST"
		}).done(function( html ) {
			
			
			$.smallBox({
    				title : "Success",
    				content : "<i class='fa fa-check'></i> Basic info saved",
    				color : "#659265",
    				iconSmall : "fa fa-thumbs-up bounce animated",
                    timeout : 4000
            });
			
			$("#save-button").removeClass('disabled');
			$("#save-button").html('Save');
			
			if(avatar != ''){
				$("#user-avatar").attr('src', avatar);
			}else{
				$("#user-avatar").attr('src', '<?php echo base_url()."application/layout/assets/img/male.png"; ?>');
			}
			
			
			$("#user_basic_info").html($("#first_name").val() + ' ' + $("#last_name").val());
			
			
			max_idle_time = parseInt($("#lock_screen").val());

		           
		});
		
		
	}
	
	
	function save_password(){
		
		$("#save-button").addClass('disabled');
		$("#save-button").html('Saving..');
		
		
		$.ajax({
          url: '<?php echo module_url('profile').'ajax/save_password.php'; ?>',
          data:{ old_password: $("#old_password").val(), new_password: $("#new_password").val(), confirm_new_password: $("#confirm_new_password").val()},
          type: "POST",
          dataType: 'json'
		}).done(function( response ) {
			
			
			
			$.smallBox({
				title : response.title,
				content : response.message,
				color : response.color,
				iconSmall : response.icon,
                timeout : 4000
            });
			
			
			if(response.result = 'ok'){
				$("#password-form")[0].reset();
			}
			
			$("#save-button").removeClass('disabled');
			$("#save-button").html('Save');
			
		           
		});
		
	}
	
	
	/*
 * FIXED HEADER
 */
$('input[type="checkbox"]#smart-fixed-header')
    .click(function () {
        if ($(this)
            .is(':checked')) {
            //checked
            $.root_.addClass("fixed-header");
        } else {
            //unchecked
            $('input[type="checkbox"]#smart-fixed-ribbon')
                .prop('checked', false);
            $('input[type="checkbox"]#smart-fixed-navigation')
                .prop('checked', false);

            $.root_.removeClass("fixed-header");
            $.root_.removeClass("fixed-navigation");
            $.root_.removeClass("fixed-ribbon");

        }
    });

/*
 * FIXED NAV
 */
$('input[type="checkbox"]#smart-fixed-navigation')
    .click(function () {
        if ($(this)
            .is(':checked')) {
            //checked
            $('input[type="checkbox"]#smart-fixed-header')
                .prop('checked', true);

            $.root_.addClass("fixed-header");
            $.root_.addClass("fixed-navigation");

            $('input[type="checkbox"]#smart-fixed-container')
                .prop('checked', false);
            $.root_.removeClass("container");

        } else {
            //unchecked
            $('input[type="checkbox"]#smart-fixed-ribbon')
                .prop('checked', false);
            $.root_.removeClass("fixed-navigation");
            $.root_.removeClass("fixed-ribbon");
        }
    });

/*
 * FIXED RIBBON
 */
$('input[type="checkbox"]#smart-fixed-ribbon')
    .click(function () {
        if ($(this)
            .is(':checked')) {

            //checked
            $('input[type="checkbox"]#smart-fixed-header')
                .prop('checked', true);
            $('input[type="checkbox"]#smart-fixed-navigation')
                .prop('checked', true);
            $('input[type="checkbox"]#smart-fixed-ribbon')
                .prop('checked', true);

            //apply
            $.root_.addClass("fixed-header");
            $.root_.addClass("fixed-navigation");
            $.root_.addClass("fixed-ribbon");

            $('input[type="checkbox"]#smart-fixed-container')
                .prop('checked', false);
            $.root_.removeClass("container");

        } else {
            //unchecked
            $.root_.removeClass("fixed-ribbon");
        }
    });

/*
 * FIXED FOOTER
 */
$('input[type="checkbox"]#smart-fixed-footer')
    .click(function () {
        if ($(this)
            .is(':checked')) {

            //checked
            $.root_.addClass("fixed-page-footer");

        } else {
            //unchecked
            $.root_.removeClass("fixed-page-footer");
        }
    });


/*
 * RTL SUPPORT
 */
$('input[type="checkbox"]#smart-rtl')
    .click(function () {
        if ($(this)
            .is(':checked')) {

            //checked
            $.root_.addClass("smart-rtl");

        } else {
            //unchecked
            $.root_.removeClass("smart-rtl");
        }
    });

/*
 * MENU ON TOP
 */

$('#smart-top-menu')
    .on('change', function (e) {
        if ($(this)
            .prop('checked')) {
            $.root_.addClass("menu-on-top");
        } else {
            $.root_.removeClass("menu-on-top");
        }
    });

/*
 * COLORBLIND FRIENDLY
 */

$('input[type="checkbox"]#colorblind-friendly')
    .click(function () {
        if ($(this)
            .is(':checked')) {

            //checked
            $.root_.addClass("colorblind-friendly");

        } else {
            //unchecked
            $.root_.removeClass("colorblind-friendly");
        }
    });



/*
 * INSIDE CONTAINER
 */
$('input[type="checkbox"]#smart-fixed-container')
    .click(function () {
        if ($(this)
            .is(':checked')) {
            //checked
            $.root_.addClass("container");

            $('input[type="checkbox"]#smart-fixed-ribbon')
                .prop('checked', false);
            $.root_.removeClass("fixed-ribbon");

            $('input[type="checkbox"]#smart-fixed-navigation')
                .prop('checked', false);
            $.root_.removeClass("fixed-navigation");

            if (smartbgimage) {
                $("#smart-bgimages")
                    .append(smartbgimage)
                    .fadeIn(1000);
                $("#smart-bgimages img")
                    .bind("click", function () {
                        var $this = $(this);
                        var $html = $('html')
                        bgurl = ($this.data("htmlbg-url"));
                        $html.css("background-image", "url(" + bgurl + ")");
                    })
                smartbgimage = null;
            } else {
                $("#smart-bgimages")
                    .fadeIn(1000);
            }

        } else {
            //unchecked
            $.root_.removeClass("container");
            $("#smart-bgimages")
                .fadeOut();
        }
    });
	
	
</script>