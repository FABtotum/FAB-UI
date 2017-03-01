/*** LOGIN */
$(document).ready(function() {

	$("#forget-password").on('click', password_modal);
	$("#send-mail").on('click', send_mail);
	$(".power-off").on('click', ask_power_off);

	$(".trigger").on('keydown', function(e) {
		if (e.which == 13) {
			$("#login-button").trigger('click');
		}
	});

	$(".trigger").on('keypress', function(e) {
		if (e.which == 13) {
			$("#login-button").trigger('click');
		}
	});

	$("#login-form").validate({

		rules : {
			email : {
				required : true,
				email : true
			},
			password : {
				required : true
			}
		},
		messages : {
			email : {
				required : 'Please enter your email address',
				email : 'Please enter a VALID email address'
			},
			password : {
				required : 'Please enter your password'
			}
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

	$("#login-button").click(function() {

		var $valid = $("#login-form").valid();

		if (!$valid) {
			return false;
		}

		$("#login-button").addClass('disabled');

		$("#login-button").html('Login..');

		$("#login-form").submit();

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

/*** password modal */
function password_modal() {
	
	
	$('#password-modal').modal({
		keyboard : false
	});

}

/*** send mail */
function send_mail() {

	$("#error-message").hide();
	$("#send-mail").addClass('disabled');
	$("#send-mail").html('Sending...');

	$.ajax({
		url : "/fabui/login/reset_mail",
		data : {
			email : $("#mail-for-reset").val()
		},
		type : 'POST',
		dataType : 'json'
	}).done(function(response) {

		$("#send-mail").removeClass('disabled');
		$("#send-mail").html('Send Mail');
		if (response.user == 0) {
			$("#error-message").show();
			return false;
		}
		if (response.user == 1) {
			$("#error-message").hide();
			if (response.sent == 1) {
				$('#password-modal').modal('hide')
				$.smallBox({
					title : "Success",
					content : "<i class='fa fa-check'></i>A message was be sent to that address containing a link to reset your password ",
					color : "#659265",
					iconSmall : "fa fa-thumbs-up bounce animated",
					timeout : 4000
				});
			}
		}
	});
}