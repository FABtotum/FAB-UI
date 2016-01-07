var smartbgimage = "<h6 class='margin-top-10 semi-bold'>Background</h6><img src='img/pattern/graphy-xs.png' data-htmlbg-url='img/pattern/graphy.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='img/pattern/tileable_wood_texture-xs.png' width='22' height='22' data-htmlbg-url='img/pattern/tileable_wood_texture.png' class='margin-right-5 bordered cursor-pointer'><img src='img/pattern/sneaker_mesh_fabric-xs.png' width='22' height='22' data-htmlbg-url='img/pattern/sneaker_mesh_fabric.png' class='margin-right-5 bordered cursor-pointer'><img src='img/pattern/nistri-xs.png' data-htmlbg-url='img/pattern/nistri.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='img/pattern/paper-xs.png' data-htmlbg-url='img/pattern/paper.png' width='22' height='22' class='bordered cursor-pointer'>";
$("#smart-bgimages").fadeOut(), $("#demo-setting").click(function() {
	$(".demo").toggleClass("activate")
}), $('input[type="checkbox"]#smart-fixed-header').click(function() {
	$(this).is(":checked") ? $.root_.addClass("fixed-header") : ($('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1), $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !1), $.root_.removeClass("fixed-header"), $.root_.removeClass("fixed-navigation"), $.root_.removeClass("fixed-ribbon"))
}), $('input[type="checkbox"]#smart-fixed-navigation').click(function() {
	$(this).is(":checked") ? ($('input[type="checkbox"]#smart-fixed-header').prop("checked", !0), $.root_.addClass("fixed-header"), $.root_.addClass("fixed-navigation"), $('input[type="checkbox"]#smart-fixed-container').prop("checked", !1), $.root_.removeClass("container")) : ($('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1), $.root_.removeClass("fixed-navigation"), $.root_.removeClass("fixed-ribbon"))
}), $('input[type="checkbox"]#smart-fixed-ribbon').click(function() {
	$(this).is(":checked") ? ($('input[type="checkbox"]#smart-fixed-header').prop("checked", !0), $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !0), $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !0), $.root_.addClass("fixed-header"), $.root_.addClass("fixed-navigation"), $.root_.addClass("fixed-ribbon"), $('input[type="checkbox"]#smart-fixed-container').prop("checked", !1), $.root_.removeClass("container")) : $.root_.removeClass("fixed-ribbon")
}), $('input[type="checkbox"]#smart-fixed-footer').click(function() {
	$(this).is(":checked") ? $.root_.addClass("fixed-page-footer") : $.root_.removeClass("fixed-page-footer")
}), $('input[type="checkbox"]#smart-rtl').click(function() {
	$(this).is(":checked") ? $.root_.addClass("smart-rtl") : $.root_.removeClass("smart-rtl")
}), $('input[type="checkbox"]#smart-top-menu').click(function() {
	$(this).is(":checked") ? $.root_.addClass("menu-on-top") : $.root_.removeClass("menu-on-top")
}), "top" == localStorage.getItem("sm-setmenu") ? $("#smart-topmenu").prop("checked", !0) : $("#smart-topmenu").prop("checked", !1), $('input[type="checkbox"]#colorblind-friendly').click(function() {
	$(this).is(":checked") ? $.root_.addClass("colorblind-friendly") : $.root_.removeClass("colorblind-friendly")
}), $('input[type="checkbox"]#smart-fixed-container').click(function() {
	$(this).is(":checked") ? ($.root_.addClass("container"), $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1), $.root_.removeClass("fixed-ribbon"), $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !1), $.root_.removeClass("fixed-navigation"), smartbgimage ? ($("#smart-bgimages").append(smartbgimage).fadeIn(1e3), $("#smart-bgimages img").bind("click", function() {
		var e = $(this), t = $("html");
		bgurl = e.data("htmlbg-url"), t.css("background-image", "url(" + bgurl + ")")
	}), smartbgimage = null) : $("#smart-bgimages").fadeIn(1e3)) : ($.root_.removeClass("container"), $("#smart-bgimages").fadeOut())
}), $("#reset-smart-widget").bind("click", function() {
	return $("#refresh").click(), !1
}), $("#smart-styles > a").on("click", function() {
	var e = $(this), t = $("#logo img");
	$.root_.removeClassPrefix("smart-style").addClass(e.attr("id")), t.attr("src", e.data("skinlogo")), $("#smart-styles > a #skin-checked").remove(), e.prepend("<i class='fa fa-check fa-fw' id='skin-checked'></i>")
})
function number_format(number, decimals, dec_point, thousands_sep) {

	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number, prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = ( typeof thousands_sep === 'undefined') ? ',' : thousands_sep, dec = ( typeof dec_point === 'undefined') ? '.' : dec_point, s = '', toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};

	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = ( prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function precise_round(num, decimals) {
	return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
}

/**
 * @param time
 * @returns {String}
 */
function _time_to_string(time) {

	var hours = parseInt(time / 3600) % 24;
	var minutes = parseInt(time / 60) % 60;
	var seconds = time % 60;

	var day = 86400;

	if (time < day) {
		return pad(precise_round(hours, 0)) + ":" + pad(precise_round(minutes, 0)) + ":" + pad(precise_round(seconds, 0));
	} else {
		return '1 day';
	}

}

/**
 *
 * @param val
 * @returns
 */
function pad(val) {
	return val > 9 ? val : "0" + val;
}

function freeze_menu(except) {

	var excepet_item_menu = new Array();

	excepet_item_menu[0] = 'dashboard';
	excepet_item_menu[1] = 'objectmanager';
	excepet_item_menu[2] = except;

	$("#left-panel nav > ul > li:not(:has(ul)) > a, #left-panel nav > ul > li > ul > li > a").each(function(index, element) {
		var controller = $(this).attr('data-controller');

		// se non è nella lista allora la rendo disabled
		if (excepet_item_menu.indexOf(controller) < 0) {

			$(this).addClass('menu-disabled');
			$(this).removeAttr('href');
		}
		//se corrisponde aggiungo punto esclamativo per notifica
		if (controller == except) {

			if ($(this).find('.freeze-menu').length <= 0) {
				$(this).append('<span class="badge bg-color-red pull-right inbox-badge freeze-menu">!</span>');
				freezed = true;
			}

		}

	});
}

/**
 *
 */
function unfreeze_menu() {

	$("#left-panel a").each(function(index, element) {
		$(this).removeClass('menu-disabled');
		$(this).attr('href', $(this).attr('data-href'));
	});

	$(".freeze-menu").remove();

	freezed = false;

}

function bytesToSize(bytes) {
	var k = 1000;
	var sizes = ["B", "Kb", "Mb", "Gb", "Tb"];
	if (bytes === 0)
		return '0 Bytes';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)), 10);
	return parseFloat((bytes / Math.pow(k, i))).toFixed(3) + ' ' + sizes[i];
}

/**
 *
 *
 */

/**
 * VARIABLES: fabui global variables
 */

/**
 *  MODAL WAITING
 */
if ($.magnificPopup) {

	var loading = $.magnificPopup.instance;

	loading.close = function() {
		$(".white-popup").removeClass('bounceIn').addClass("bounceOut");
		$.magnificPopup.proto.close.call(this);
	};

}

function openWait(title, content, spinner) {

	content = content || '';
	spinner = spinner || true;

	var contentDisplay = 'display:none;'

	if ($(".wait-content").length > 0) {
		$(".wait-content").html('');
		$(".wait-content").remove();
	}

	var src_html = '<div class="white-popup animated bounceIn fast">';

	if (!pressedEmergencyButton) {
		src_html += '<a href="#" class="btn btn-default pull-right" data-action="emergencyButton"><i class="fa fa-times-circle txt-color-red"></i></a>';
	}

	src_html += '<h6 class="text-align-center wait-title">' + title + ' </h6>';

	if (spinner == true) {

		src_html += '<div class="progress progress-sm progress-striped active"><div class="progress-bar bg-color-teal"  role="progressbar" style="width: 100%"></div></div>';
		//src_html += '<h4 class="text-align-center wait-spinner"><i class="fa fa-spinner fa-spin"></i></h4>'
	}

	if (content != "") {
		contentDisplay = '';
	}

	src_html += '<div class="wait-content margin-top-10" style="' + contentDisplay + '"><pre>' + content + '</pre></div>';
	src_html += '</div>';

	loading.open({
		items : {
			src : src_html
		},

		removalDelay : 1000,
		type : 'inline',
		modal : true,
		mainClass : 'mfp-zoom-in',
		alignTop : false
	});

}

function closeWait() {
	loading.close();
}

function waitTitle(title) {
	if ($(".wait-title").length > 0) {
		$(".wait-title").html(title);
	}
}

function waitContent(content) {
	if ($(".wait-content").length > 0) {

		$(".wait-content").find('pre').html('');
		$(".wait-content").show();
		$(".wait-content").find('pre').html(content);
	}
}

var color_green = "#659265";
var color_red = "#C46A69";
var freezed = false;

function show_small_box(title, message, color, icon, timeout) {
	$.smallBox({
		title : title,
		content : message,
		color : color,
		//timeout: 6000,
		icon : icon,
		timeout : timeout
	});
}

function show_error(message) {
	show_small_box('Error', message, color_red, 'fa fa-warning shake animated', 6000);
}

function show_info_message(message) {
	show_small_box('Info', message, color_green, 'fa fa-check bounce animated', 4000);

}

//======================================================================================================
/*
 *  GLOBAL
 *
 */
var notifications_interval;
var safety_interval;
var tasks_interval;
var EMERGENCY = false;
var IDLETIME = 0;
var idleInterval;
var do_system_call = true;
var SOCKET;
var SOCKET_CONNECTED = false;
var interval_internet;
var jogFirstEntry = true;
var PAGE_ACTIVE = true;
var PAGE_TITLE = '';
var RESETTING_CONTROLLER = false;
var STOPPING_ALL = false;
var IS_MACRO_ON = false;
var IS_TASK_ON = false;
var jog_ticket_url = '';
var interval_temperature;

/** CHECK PRINTE SAFETY AJAX MODE*/
function safety() {

	if (!PAGE_ACTIVE) {
		return false;
	}

	if (!do_system_call) {
		return false;
	}

	if (SOCKET_CONNECTED) {
		return
	}

	var timestamp = new Date().getTime();
	if (EMERGENCY == false) {
		$.get("/temp/fab_ui_safety.json?time=" + jQuery.now(), function(data) {
			if (data.type == 'emergency') {
				show_emergency(data.code);
			}
		});
	}
}

function secure(mode) {

	if (SOCKET_CONNECTED) {
		SOCKET.send('message', '{"name": "secure", "data":{"mode":' + mode + ' } }');
	} else {
		IS_MACRO_ON = true;
		$.ajax({
			type : "POST",
			url : "/fabui/application/modules/controller/ajax/secure.php",
			data : {
				mode : mode
			},
			dataType : 'json'
		}).done(function(response) {
			EMERGENCY = false;
			IS_MACRO_ON = false;
		});

	}

}

function set_tasks(data) {

	IS_TASK_ON = false;

	number_tasks = data.number;
	var controller = '';

	$(".task-list").find('span').html('	Tasks (' + data.number + ') ');

	if (data.number > 0) {

		IS_TASK_ON = true;

		$.each(data.items, function() {
			var row = this;
			controller = row.controller;
			
			if (controller == 'make') {
 				controller += '/' + row.type;
			}
			
		});

	}

	if (data.number > 0) {
		freeze_menu(controller);
		freezed = true;
	} else {
		freezed = false;
		unfreeze_menu();
	}

}

function set_updates(number) {

	number_updates = number;
	$(".update-list").find('span').html('	Updates (' + number + ') ');
	if (number > 0) {
		$("#left-panel").find('nav').find('ul').find('li').each(function() {
			if ($(this).find('a').attr("data-controller") == 'updates') {
				$(this).find('a').append('<span class="badge bg-color-red pull-right inbox-badge">' + number + '</span>');
			}
		});
	}
	
	if(number_updates > 0){
		
		var update_label = number_updates == 1 ? ' update is' : ' updates are';
		
		var html = '<div class="row"><div class="col-sm-12"><div class="alert alert-info alert-block animated  bounce"><button class="close" data-dismiss="alert">×</button><h4 class="alert-heading"><i class="fa fa-info-circle"></i> ' + number_updates + update_label + ' available, <a style="text-decoration:underline;" href="/fabui/updates">check it now</a> </h4></div></div></div>'
		
		if(MODULE != 'updates'){
			$("#content").prepend(html);
		}
		
	}
	

}

function update_notifications() {

	var total = number_updates + number_tasks + number_notifications;

	if (total > 0) {
		$("#activity").find('.badge').addClass('bg-color-red bounceIn animated');
		document.title = PAGE_TITLE + ' (' + total + ')';
	} else {
		$("#activity").find('.badge').removeClass('bg-color-red bounceIn animated');
		document.title = PAGE_TITLE;
	}

	if (number_tasks == 0) {
		freezed = false;
		unfreeze_menu();
	}

	$("#activity").find('.badge').html(total);

}

function refresh_notifications() {

	if (!PAGE_ACTIVE) {
		return false;
	}

	if (!do_system_call) {
		return false;
	}

	$(".notification").each(function(index, element) {
		var obj = $(this);
		if (obj.hasClass('active')) {
			var url = obj.find('input[name="activity"]').attr("id");
			var container = $(".ajax-notifications");
			loadURL(url, container);
		}
	});
}

/** CHECK TASKS, MENU AJAX MODE */
function check_notifications() {

	if (!PAGE_ACTIVE) {
		return false;
	}

	if (!do_system_call) {
		return false;
	}

	if (SOCKET_CONNECTED) {
		return false;
	}

	if (IDLETIME < max_idle_time || max_idle_time == 0) {
		var timestamp = new Date().getTime();
		$.ajax({
			type : "POST",
			url : "/fabui/application/modules/controller/ajax/check_notifications.php?time=" + timestamp,
			dataType : 'json',
			cache : false
		}).done(function(data) {

			//set_updates(data.updates);
			set_tasks(data.tasks);
			update_notifications();

			if (data.internet == true) {
				$('.internet').show();
			} else {
				$('.internet').hide();
			}
		});

	} else {

		$("#lock-screen-form").submit();
	}
}

/** ON LOAD */

$(function() {

	PAGE_TITLE = document.title;

	if (fabui) {

		//check is websocket

		if ("WebSocket" in window) {

			var host = window.location.hostname;
			var port = 9001;

			SOCKET = new FabWebSocket(host, port);

			SOCKET.bind('message', function(payload) {

				try {
					var obj = jQuery.parseJSON(payload);

				} catch(e) {
					return;
				}

				switch(obj.type) {

				case 'emergency':
					show_emergency(obj.code);
					break;
				case 'alert':
					show_alert(obj.code);
					//show_emergency(obj.code);
					break;
				case 'security':
					EMERGENCY = false;
					break;
				case 'internet':
					show_connected(obj.data);
					break;
				case 'serial':
				
					
					write_to_console(obj.data.command + ": " + obj.data.response);
					/* */
					break;
				case 'temperature':
					if ( typeof update_temperature_info == 'function') {
						update_temperature_info(obj.data);
						$(".btn").removeClass('disabled');
					}
					break;
				case 'error':
					//console.log(obj.error);
					break;
				case 'macro':
					manage_macro(obj.data);
					break;

				case 'task':

					if (obj.data.type == 'notifications') {
						set_tasks(obj.data);
						update_notifications();

					} else {
						manage_task(obj.data);
					}

					break;
				case 'create':

					if ( typeof create_socket_response == 'function') {
						create_socket_response(obj.data);
					}
					break;

				case 'system':
					manage_system_monitor(obj.data);
					break;
				case 'post_processing':
					manage_post_processing(obj.data);
					break;

				}

			});

			//when connected to the socket
			SOCKET.bind('open', function() {

				SOCKET_CONNECTED = true;
				SOCKET.send('message', '{"name": "getTasks"}');
				SOCKET.send('message', '{"name": "getInternet"}');
				SOCKET.send('message', '{"name": "getUsb"}');

			});

			//when connection is closed
			SOCKET.bind('close', function() {
				SOCKET_CONNECTED = false;
				socket_fallback();
			});

			//when an error occurred
			SOCKET.bind('error', function() {
				socket_fallback();

			});

			interval_internet = setInterval(check_connected, 360000);
			SOCKET.connect();

		}
		
		//init UI
		initUI();
		
		
		// Handler for .ready() called.
		notifications_interval = setInterval(check_notifications, 10000);
		idleInterval = setInterval(timerIncrement, 1000);
		safety_interval = setInterval(safety, 3000);
		interval_temperature = setInterval(get_temperatures, 2000);

		/* START TIMER... */
		$("#refresh-notifications").on('click', refresh_notifications);
		check_for_updates();
		check_for_wizard_setup();

	}

});

$(".lock-ribbon").click(function() {

	$("#lock-screen-form").submit();

});

$(".language").click(function() {

	var actual_lang = $("#actual_lang").val();
	var new_lang = $(this).attr("data-value");

	if (actual_lang != new_lang) {
		$("#lang").val(new_lang);

		openWait('<i class="fa fa-flag"></i><br> loading language... ');
		$("#lang_form").submit();
	}

});

/** MOUSE MOVE FOR LOCK SCREEN */
$(document).mousemove(function(e) {
	IDLETIME = 0;
});

/** IDLE TIMER */
function timerIncrement() {
	IDLETIME++;
}

/** SHUTDOWN */
function shutdown() {
	IS_MACRO_ON = true;
	openWait('Shutdown in progress');

	clearInterval(notifications_interval);
	clearInterval(safety_interval);
	clearInterval(idleInterval);

	$.ajax({
		type : "POST",
		url : "/fabui/application/modules/controller/ajax/shutdown.php",
		dataType : 'json'
	}).done(function(response) {

		setTimeout(function() {

			$(".wait-spinner").remove();
			waitTitle('Now you can switch off the power');
			waitContent($("#power-off-img").html());

		}, 12000);

	});
}

function restart() {
	
	IS_MACRO_ON = true;
	openWait("Restart in progress");

	clearInterval(notifications_interval);
	clearInterval(safety_interval);
	clearInterval(idleInterval);

	$.ajax({
		type : "POST",
		url : "/fabui/application/modules/controller/ajax/restart.php",
		dataType : 'json'
	}).done(function(response) {

		waitContent("Restarting please wait...");

		setTimeout(function() {

			document.location.href = '/fabui/login/out';

		}, 70000);

	});

}

/** SHUTDOWN */
function check_for_updates() {

	if (!do_system_call) {
		return false;
	}

	$.ajax({
		type : "POST",
		url : "/fabui/application/modules/controller/ajax/check_updates.php",
		dataType : 'json'
	}).done(function(response) {

		set_updates(response.updates.number);
		update_notifications();
	});
}

/** SUGGESTION */
$("#send-suggestion").on('click', function() {

	if ($.trim($("#suggestion-text").val()) == '') {
		return false;
	}

	$(".modal-content").find(".btn").addClass('disabled');
	$("#send-suggestion").html('<i class="fa fa-envelope-o"></i> Sending..');

	$.ajax({
		type : "POST",
		url : "/fabui/controller/suggestion",
		dataType : 'json',
		data : {
			text : $("#suggestion-text").val(),
			title : $("#suggestion-title").val()
		}
	}).done(function(response) {

		$(".modal-content").find(".btn").removeClass('disabled');
		$("#send-suggestion").html('<i class="fa fa-envelope-o"></i> Send');

		if (response.result == 1) {
			$("#suggestion-text").val('');
			$("#suggestion-title").val('');
			$(".suggestion-modal").modal("hide");

			$.smallBox({
				title : "Thanks",
				content : "so much for taking the time to help improve FABUI .",
				color : "#659265",
				iconSmall : "fa fa-smile-o fa-2X",
				timeout : 7000
			});

		} else {

			$.smallBox({
				title : "Warning",
				content : "an error occurred, please try to send again",
				color : "#C46A69",
				iconSmall : "fa fa-warning shake animated",
				timeout : 7000
			});

		}

	});

});

/** REPORT BUG */
$("#send-bug").on('click', function() {

	if ($.trim($("#bug-text").val()) == '') {
		return false;
	}

	$(".modal-content").find(".btn").addClass('disabled');
	$("#send-bug").html('<i class="fa fa-envelope-o"></i> Sending..');

	$.ajax({
		type : "POST",
		url : "/fabui/controller/bug",
		dataType : 'json',
		data : {
			text : $("#bug-text").val(),
			title : $("#bug-title").val()
		}
	}).done(function(response) {

		$(".modal-content").find(".btn").removeClass('disabled');
		$("#send-bug").html('<i class="fa fa-envelope-o"></i> Send');

		if (response.result == 1) {
			$("#bug-text").val('');
			$("#bug-title").val('');
			$(".bug-modal").modal("hide");

			$.smallBox({
				title : "Thanks",
				content : "so much for taking the time to help improve FABUI .",
				color : "#659265",
				iconSmall : "fa fa-smile-o fa-2X",
				timeout : 7000
			});

		} else {

			$.smallBox({
				title : "Warning",
				content : "an error occurred, please try to send again",
				color : "#C46A69",
				iconSmall : "fa fa-warning shake animated",
				timeout : 7000
			});

		}

	});

});

/**
 *
 */

function check_for_wizard_setup() {

	if (!do_system_call) {
		return false;
	}

	setTimeout(function() {
		if (setup_wizard) {

			$.smallBox({
				title : "Wizard Setup",
				content : "It seems that you still did not complete the first recommended setup:<ul><li>Manual Bed Calibration</li><li>Probe Lenght Calibration</li><li>Engage Feeder</li></ul><br>Without a proper calibration you will not be able to use the FABtotum correctly<br>Do you want to do it now?<br><br><p class='text-align-right'><a href='/fabui/maintenance/first-setup' class='btn btn-primary btn-sm'>Yes</a> <a href='javascript:void(0);' class='btn btn-danger btn-sm'>No</a> <a href='javascript:dont_ask_wizard();' class='btn btn-warning btn-sm'>Don't ask me anymore</a> </p>",
				color : "#296191",
				icon : "fa fa-warning swing animated"
			});
		}
	}, 1000);
}

function dont_ask_wizard() {

	$.ajax({
		type : "POST",
		url : "/fabui/controller/wizard",
		dataType : 'json',
		data : {
			set : 0
		}
	}).done(function(response) {

	});

}

/** GET TRACE */
function getTrace(url, type, contenitor) {

	$.ajax({
		type : type,
		url : url,
	}).done(function(data, statusText, xhr) {

		if (xhr.status == 200) {
			contenitor.html(data);
			contenitor.scrollTop(1E10);
		}

	});
}

////////////////////////
function show_emergency(code) {

	jogFirstEntry = true;

	if (EMERGENCY == true) {
		return;
	}
	EMERGENCY = true;
	
	var buttons ='[OK][IGNORE]';
	
	if(code == parseInt(103)){
		
		buttons = '[IGNORE] [INSTALL HEAD]';
		
	}

	$.SmartMessageBox({
		title : "<h4><span class='txt-color-orangeDark'><i class='fa fa-warning fa-2x'></i></span>&nbsp;&nbsp;" + decode_emergency_code(code) + "<br>&nbsp;Press OK to continue or Ignore to disable this warning</h4>",
		buttons : buttons
	}, function(ButtonPressed) {
		
		console.log(ButtonPressed);
		
		if (ButtonPressed === "OK") {
			secure(1);
		}
		if (ButtonPressed === "IGNORE") {
			
			if(buttons.indexOf("INSTALL HEAD") > -1){		
				secure(1);
			}else{
				secure(0);
			}
			
			
		}
		
		if (ButtonPressed === "INSTALL HEAD") {
			installHead();
		}
	});

}

function show_alert(code) {

	$.smallBox({
		title : "Message",
		content : decode_emergency_code(code),
		color : "#5384AF",
		timeout : 10000,
		icon : "fa fa-warning"
	});

	
}

function decode_emergency_code(code) {

	switch(parseInt(code)) {
	case 100:
		return 'General Safety Lockdown';
		break;
	case 101:
		return 'Printer stopped due to errors';
		break;
	case 102:
		return 'Front panel is open, cannot continue';
		break;
	case 103:
		return 'Head not properly locked in place';
		break;
	case 104:
		return 'Extruder Temperature critical, shutting down';
		break;
	case 105:
		return 'Bed Temperature critical, shutting down';
		break;
	case 106:
		return 'X max Endstop hit';
		break;
	case 107:
		return 'X min Endstop hit';
		break;
	case 108:
		return 'Y max Endstop hit';
		break;
	case 109:
		return 'Y min Endstop hit';
		break;
	case 110:
		return 'The FABtotum has been idling for more than 8 minutes. Temperatures and Motors have been turned off.';
		break;
	case 120:
		return 'Both Y Endstops hit at the same time';
		break;
	case 121:
		return 'Both Z Endstops hit at the same time';
		break;
	default:
		return 'Unknown error Error code: ' + code;
		break;
	}

}

function show_connected(bool) {
	if (bool) {
		$('.internet').show();
		$('.no-internet-detected').remove();
	} else {
		$('.internet').hide();
		console.log($('.no-internet-detected').length);
		if($('.no-internet-detected').length <= 0){
			var html = '<div class="row no-internet-detected"><div class="col-sm-12"><div class="alert alert-warning alert-block animated  bounce"><button class="close" data-dismiss="alert">×</button><h4 class="alert-heading"><i class="fa fa-warning"></i>   No internet connectivity detected. For a better experience please <a style="text-decoration:underline;" href="/fabui/settings/network">connect</a> </h4></div></div></div>';
			$("#content").prepend(html);		
		}

	}
}

function check_connected() {

	if (SOCKET_CONNECTED && PAGE_ACTIVE) {
		SOCKET.send('message', '{"name": "getInternet"}');
	}
}

function socket_fallback() {
	SOCKET_CONNECTED = false;
	//safety_interval = setInterval(safety, 3000);
	//notifications_interval = setInterval(check_notifications, 10000);

}

function write_to_console(text, type) {

	type = type || '';

	if (type == 'macro' || type == "task") {
		$('.console').html(text);
	} else {
		$('.console').append(text);
	}

	$('.console').scrollTop(1E10);
	waitContent(text);
}

function manage_macro(obj) {

	if (obj.type == 'trace') {
		write_to_console(obj.content, 'macro');
	} else if (obj.type == 'response') {
		manage_macro_response(obj.content);
	} else if (obj.type == 'status') {

	}

}

function manage_task(obj) {

	if (obj.type == 'trace') {
		write_to_console(obj.content, 'task');
	} else if (obj.type == 'monitor') {
		manage_task_monitor(obj);
	}

}

function manage_task_monitor(obj) {

}

function manage_macro_response(response) {

}

function manage_system_monitor(obj) {

	switch(obj.type) {
	case 'usb':
		mange_usb_monitor(obj.status, obj.alert)
		break;
	}
}

function manage_post_processing(obj) {

}

function mange_usb_monitor(status, alert) {

	var message = '<p><strong>';

	if (status) {
		message += 'Usb disk inserted';
		$(".usb-ribbon").show();
	} else {
		message += 'Usb disk removed';
		$(".usb-ribbon").hide();
	}

	message += '</strong></p>';

	if (alert) {
		$.smallBox({
			title : "System",
			content : message,
			color : "#296191",
			timeout : 3000,
			icon : "icon-fab-usb"
		});
	}
}

function reset_controller() {

	
	RESETTING_CONTROLLER = true;

	openWait("Reset Controller...");
	$.ajax({
		url : '/fabui/application/modules/controller/ajax/reset_controller.php',
		dataType : 'json',
		type : 'post'
	}).done(function(response) {
		closeWait();
		RESETTING_CONTROLLER = false;
	});
}

function stopAll() {

	openWait("Stopping all...");

	STOPPING_ALL = true;

	$.ajax({
		url : '/fabui/application/modules/controller/ajax/stop_all.php',
		dataType : 'json',
		type : 'post',
		data : {
			'module' : MODULE
		}
	}).done(function(response) {

		/*$.xhrPool.abortAll();*/
		waitContent("Refreshing page");
		STOPPING_ALL = false;
		document.location.href = document.location.href;
	});
}


$(window).on("blur focus", function(e) {

	var prevType = $(this).data("prevType");

	if (prevType != e.type) {
		switch (e.type) {
		case "blur":
			PAGE_ACTIVE = false;
			//document.title = document.title + ' (idle)';
			break;
		case "focus":
			PAGE_ACTIVE = true;
			//document.title = document.title.replace('(idle)', '');
			break;
		}
	}

	$(this).data("prevType", e.type);

});

$.xhrPool = [];
$.xhrPool.abortAll = function(url) {
	$(this).each(function(i, jqXHR) {//  cycle through list of recorded connection
		
		//if (!url || url === jqXHR.requestURL) {
		jqXHR.abort();
		//  aborts connection
		$.xhrPool.splice(i, 1);
		//  removes from list by index
		//}
	});
};
$.ajaxSetup({
	beforeSend : function(jqXHR) {
		$.xhrPool.push(jqXHR);
		//  add connection to list
	},
	complete : function(jqXHR) {
		var i = $.xhrPool.indexOf(jqXHR);
		//  get index for current connection completed
		if (i > -1)
			$.xhrPool.splice(i, 1);
		//  soremoves from list by index
	}
});
$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
	jqXHR.requestURL = options.url;
});

/********************
 *
 *
 * CALL JOG FUNCTIONS
 *
 */
function jog_make_call_ws(func, value) {

	var jsonData = {};

	jsonData['func'] = func;
	jsonData['value'] = value;
	jsonData['step'] = $("#step").val();
	jsonData['z_step'] = $("#z-step").val();
	jsonData['feedrate'] = $("#feedrate").val();
	jsonData['extruderFeedrate'] = $("#extruder-feedrate").val();

	var message = {};

	message['name'] = "serial";
	message['data'] = jsonData;

	if (func != 'get_temperature')
		$(".btn").addClass('disabled');
	SOCKET.send('message', JSON.stringify(message));

}

function get_temperatures() {

	if (!RESETTING_CONTROLLER && !STOPPING_ALL && !IS_MACRO_ON && !IS_TASK_ON && !PRINTER_BUSY) {
		
		jog_make_call_ws("get_temperature", "");
	}

}

/*****************************
 *
 * UPDATE TEMPERATURES
 *
 */
function update_temperature_info(data) {

	if (data.response.indexOf('ok T:') > -1) {

		var str_temp = data.response.replace('ok ', '');
		var temperature = str_temp.split(' ');

		var ext_temp = temperature[0].split(':')[1];
		var ext_target = temperature[1].split('/')[1];
		var bed_temp = temperature[2].split(':')[1];
		var bed_target = temperature[3].split('/')[1];

		/******* TOP BAR *********************/
		$("#top-bar-nozzle-actual").html(parseInt(ext_temp));
		$("#top-bar-nozzle-target").html(parseInt(ext_target));
		$("#top-bar-bed-actual").html(parseInt(bed_temp));
		$("#top-bar-bed-target").html(parseInt(bed_target));

		if ( typeof (Storage) !== "undefined") {
			
			
			localStorage.setItem("nozzle_temp", ext_temp);
			localStorage.setItem("nozzle_temp_target", ext_target);
			localStorage.setItem("bed_temp", bed_temp);
			localStorage.setItem("bed_temp_target", bed_target);

		} else {

		}

		/*********** JOG ***************************/
		if (MODULE == "jog") {

			$("#ext-actual-degrees").html(parseInt(ext_temp) + '&deg;C');

			$("#act-ext-temp").val(parseInt(ext_temp), {
				set : true,
				animate : true
			});

			if (!EXT_TARGET_BLOCKED) {
				$("#ext-target-temp").val(parseInt(ext_target), {
					set : true,
					animate : true
				});

				$("#ext-degrees").html(parseInt(ext_target) + '&deg;C');
			}

			$("#bed-actual-degrees").html(parseInt(bed_temp) + '&deg;C');

			$("#act-bed-temp").val(parseInt(bed_temp), {
				set : true,
				animate : true
			});

			if (!BED_TARGET_BLOCKED) {
				$("#bed-target-temp").val(parseInt(bed_target), {
					set : true,
					animate : true
				});

				$("#bed-degrees").html(parseInt(bed_target) + '&deg;C');
			}

			if (showTemperatureConsole) {
				write_to_console('Temperatures (M105) [Ext: ' + parseInt(ext_temp) + ' / ' + parseInt(ext_target) + ' ---  Bed: ' + parseInt(bed_temp) + ' / ' + parseInt(bed_target) + ']\n');
			}

		}

	}

}

/***
 * FUNCTION TO AVOID PAGE CHANGE WHEN SOME MACROS ARE ON
 *
 */
function checkExit() {
	if (IS_MACRO_ON) {
		return "You have attempted to leave this page.  A macro operation is still working. Are you sure you want to reload this page?";
	}
}

function initUI(){
	
	if ( typeof (Storage) !== "undefined") {
		/******* TOP BAR *********************/
		$("#top-bar-nozzle-actual").html(parseInt(localStorage.getItem("nozzle_temp")));
		$("#top-bar-nozzle-target").html(parseInt(localStorage.getItem("nozzle_temp_target")));
		$("#top-bar-bed-actual").html(parseInt(localStorage.getItem("bed_temp")));
		$("#top-bar-bed-target").html(parseInt(localStorage.getItem("bed_temp_target")));
		
	}
	
}


function installHead(){
	document.location.href = '/fabui/maintenance/head?warning=1';
}
