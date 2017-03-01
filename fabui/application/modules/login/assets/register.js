/*** REGISTER */

$(document).ready(function() {
	
	$(".power-off").on('click', ask_power_off);

	$("#form-register").validate({

		// Rules for form validation
		rules : {
			email : {
				required : true,
				email : true,
				remote : "/fabui/login/check_mail"
			},
			password : {
				required : true,
				minlength : 3,
				maxlength : 20
			},
			passwordConfirm : {
				required : true,
				minlength : 3,
				maxlength : 20,
				equalTo : '#password'
			},
			first_name : {
				required : true
			},
			last_name : {
				required : true
			}
		},

		// Messages for form validation
		messages : {
			login : {
				required : 'Please enter your login'
			},
			email : {
				required : 'Please enter your email address',
				email : 'Please enter a VALID email address',
				remote : 'Email already used. Please enter a new email address'
			},
			password : {
				required : 'Please enter your password'
			},
			passwordConfirm : {
				required : 'Please enter your password one more time',
				equalTo : 'Please enter the same password as above'
			},
			firstname : {
				required : 'Please select your first name'
			},
			lastname : {
				required : 'Please select your last name'
			},
			gender : {
				required : 'Please select your gender'
			},
			terms : {
				required : 'You must agree with Terms and Conditions'
			}
		},

		// Do not change code below
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

	$("#register-button").click(function() {
		var $valid = $("#form-register").valid();
		if (!$valid) {
			return false;
		} else {
			$("#register-button").addClass('disabled');
			$("#form-register").submit();

		}

	});

});

/*** ask_power_off */
function ask_power_off() {

	$.SmartMessageBox({
		title : "Shutdown now ?",
		buttons : '[No][Yes]'
	}, function(ButtonPressed) {

		if (ButtonPressed === "Yes") {
			shutdown();
		}
		if (ButtonPressed === "No") {
		}
	});
}

