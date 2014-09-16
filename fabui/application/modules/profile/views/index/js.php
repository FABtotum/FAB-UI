<script type="text/javascript">


	var avatar;
	var theme_skin= '<?php echo $_SESSION['user']['theme-skin'] ?>';
		
	
	$(function() {
		
		
		 $("#uploadFile").on("change", function(){
		 	
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
  		
	});
	
	
	$("#save-button").click(function(){
		
		var $valid = $("#basic-info-form").valid();
		
		if(!$valid){
			
			return false;
		}
		
		
		save_basic_info();
		
		
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
          data:{ first_name: $("#first_name").val(), last_name: $("#last_name").val(), email: $("#email").val(), avatar: avatar, theme_skin : theme_skin},
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
		           
		});
		
		
	}
	
	
</script>