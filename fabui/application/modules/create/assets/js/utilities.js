/**
 * CREATE MODULE UTILITIES FUNCTIONS
 */

var file_selected;
var stop_monitor = false;
var interval_monitors;

//counter for count how many times monitor we'll be called
var print_finished = false;
/**/
 var monitor_timeout = 5000; // 1000 = 1s
 var interval_timer;
 var interval_trace;
 var interval_stop;
 var elapsed_time_stop = 0;
 var max_time_stop = 6;

 /**/
var progress_step;
var second_for_step;
var current_estimated_time;
//in seconds
var estimated_time_left;
//in seconds

var stopped = 0;
var precision = 3;

var model;

var not_printable = [".stl", ".asc"];

/**
 *
 * @param object
 */
function detail_files(object) {

	var printable_files = ['.gc', '.gcode', '.nc'];
	var files = object.files.data;
	var files_number = object.files.number;

	if (files_number > 0) {

		var html = '';
		html += '<table class="table files-table table-bordered table-hover table-stripped smart-form has-tickbox">';
		html += '<thead>';
		html += '<tr>';
		html += '<th></th>';
		html += '<th>File</th>';
		html += '<th class="hidden-xs">Note</th>';
		html += '<th class="hidden-xs">Type</th>';
		html += '</tr>';
		html += '</thead>';

		html += '<tbody>'

		$.each(files, function(index, file) {

			var extended_class = '';
			var status = '<i class="icon-fab-printable pull-right"></i>';
			var icon_type = 'fa-cubes txt-color-blue';

			if (not_printable.indexOf(file.file_ext) > -1) {
				extended_class = 'warning';
				status = '<i class="icon-fab-not-printable pull-right"></i>';
				var icon_type = 'fa-file-text-o txt-color-red';
			}

			html += '<tr class="file-row ' + extended_class + '" data-id="' + file.id + '">';
			html += '<td><label class="radio"><input class="obj-file" value="' + file.id + '" type="radio" name="file-selected"><i></i> </label></td>';
			html += '<td><strong><i class="fa ' + icon_type + '"></i>  ' + file.raw_name + status + '</strong> </td>';
			
			html += '<td class="hidden-xs">' + file.note + ' </td>';
			var icon_src = '<span class="icon-fab-additive"></span>';
			html += '<td class="hidden-xs">' + file.print_type + icon_src + ' </td>';

			html += '</tr>';

		});

		html += '</tbody>';
		html += '</table>';

		$('#files-container').html(html);
		
		//enable prev button
		

		$('.obj-file').on('click', function() {
			select_file($(this).val());
		});
		

		$('.file-row').click(function() {

			$(this).find(':first-child').find('input').prop("checked", true);

			$(".files-table tbody tr").removeClass('success');
			$(this).addClass('success');


			select_file($(this).find(':first-child').find('input').val());
			startFromRecent = false;

			/** LAOD INTERSTITIAL */

			print_type = file_selected.print_type != '' ? file_selected.print_type : 'additive';

			/** MODAL IF IS NOT PRINTABLE FILE */
			if (not_printable.indexOf(file_selected.file_ext) > -1) {
				process_type = file_selected.file_ext.replace('.', '');

				$('#myModal').modal('show');
				$("#btn-next").addClass("disabled");

			} else {
				$("#btn-next").removeClass("disabled");
			}

			try {

				$(".model-info").remove();

				if (file_selected.attributes != '' && file_selected.attributes != 'Processing') {
					
					$('#files-container').append(model_info(file_selected));
				}

			} catch(e) {

			}

			$("#step4").html('');

			$.ajax({
				/* url : ajax_endpoint + 'ajax/'+ print_type + '.php',*/
				url : '/fabui/create/show/' + print_type,
				cache : false
			}).done(function(html) {
				$("#step4").html(html);
			});

		});

		if (request_file > 0 && do_request_file == true) {

			/** IF I COME FROM OBJECT MANAGER */
			$(".files-table > tbody > tr").each(function() {

				if ($(this).attr('data-id') == request_file) {
					$(this).trigger('click');
					do_request_file = false;
				}

			});

		}

	}

}

/**
 *
 * @param fileID
 */
function select_file(fileID) {

	id_file = fileID;

	$.each(object.files.data, function(index, file) {
		if (file.id == fileID) {
			file_selected = file;
			
			$('.file_name').html(file_selected.raw_name);

			/** ABLE NEXT BUTTON */
			enable_button('#btn-next');
		}
	});

}

/**
 *
 * @param file
 */
function detail_file(file) {

	$('#file_name').html(file.file_name);
	$('.file_name').html(file.raw_name);
	$('#orig_name').html(file.orig_name);
	$('#full_path').html(file.full_path);
	$('#file_ext').html(file.file_ext);
	$('#file_size').html(file.file_size);
	$('#insert_date').html(file.insert_date);

	$('.file_name').html(file_selected.raw_name);
}


function model_info(file){
	
	if(file.attributes == '' || file.attributes == 'Processing'){
		return;
	}
	
	if (file.print_type != 'subtractive') {

		var attributes = JSON.parse(file.attributes);

		var model_info_html = '<div class="well well-sm model-info margin-top-10 info">';

		var x = number_format(attributes.dimensions.x, 2, '.', '');
		var y = number_format(attributes.dimensions.y, 2, '.', '');
		var z = number_format(attributes.dimensions.z, 2, '.', '');

		model_info_html += '<dl class="dl-horizontal">';

		model_info_html += '<dt>Model Size:</dt>';
		model_info_html += '<dd>' + x + ' x ' + y + ' x ' + z + ' mm</dd>';

		model_info_html += '<dt>Filament used:</dt>';
		model_info_html += '<dd>' + number_format(attributes.filament, 2, '.', '') + ' mm</dd>';

		model_info_html += '<dt>Estimated time print:</dt>';
		model_info_html += '<dd>' + attributes.estimated_time + '</dd>';

		model_info_html += '<dt>Layers:</dt>';
		model_info_html += '<dd>' + attributes.number_of_layers + '</dd>';

		model_info_html += '</dl>';

		model_info_html += '</div>';
		
	} else {
	
		var message = file.attributes != 'Processing' ? 'No information avaiable for this file' : 'Processing informations..';
		var model_info_html = '<div class="alert alert-warning fade in model-info margin-top-10 info">';
		model_info_html += '<strong><i class="fa fa-warning"></i> ' + message + ' </strong>';
		model_info_html += '</div>';
	
	}
	
	return model_info_html;
	
}


/**
 *
 * @param object
 */
function detail_object(object) {

	id_object = object.object.id;

	// azzerro lista file
	$('#files-container').html('');

	$('#obj_name').html(object.object.obj_name);
	$('#obj_description').html(object.object.obj_description);
	$('#date_insert').html(object.object.date_insert);
	$('#date_updated').html(object.object.date_updated);

	$('#btn-next').removeClass('disabled');
	$('.object_name').html(object.object.obj_name);
	$('.file_name').html('');

	file_selected = '';

}

/**
 *
 */
function _timer() {

	/**
	 * ELAPSED TIME
	 */

	elapsed_time = (parseInt(elapsed_time) + 1);
	$('.elapsed-time').html(_time_to_string(elapsed_time));

	/**
	 * TIME LEFT
	 */
	if (!isNaN(estimated_time_left)) {
		estimated_time_left = (parseInt(estimated_time_left) - 1);
		if (estimated_time_left >= 0) {
			$('.estimated-time-left').html(_time_to_string(estimated_time_left));
		}

	}
}

/**
 * DISPLAY PRINT TRACE
 */
function _trace() {

	if (!SOCKET_CONNECTED) {
		if (!print_finished) {
			_trace_call();
		}
	}
}

function _trace_call() {

	$.ajax({
		url : uri_trace,
		async : true,
	}).done(function(response) {
		$(".console").html(response);
		$('.console').scrollTop(1E10);
	});

}

/**
 *
 * @param action
 * @param value
 */
function _do_action(action, value) {

	if (SOCKET_CONNECTED) {

		var jsonData = {};

		jsonData['function'] = 'operation';
		jsonData['id_task'] = id_task;
		jsonData['data_file'] = data_file;
		jsonData['action'] = action;
		jsonData['value'] = value;
		jsonData['progress'] = progress;
		jsonData['attributes_file'] = attributes_file;
		jsonData['type'] = print_type;

		var message = {};

		message['name'] = "create";
		message['data'] = jsonData;

		SOCKET.send('message', JSON.stringify(message));

	} else {

		$.ajax({
			url : ajax_endpoint + 'ajax/action.php',
			data : {
				id_task : id_task,
				pid : pid,
				data_file : data_file,
				action : action,
				value : value,
				progress : progress,
				attributes_file : attributes_file,
				type : print_type
			},
			type : 'post',
			dataType : 'json',
			async : true
		}).done(function(response) {

			$.smallBox({
				title : "Success",
				content : "<i class='fa fa-check'></i> " + response.message,
				color : "#659265",
				iconSmall : "fa fa-thumbs-up bounce animated",
				timeout : 8000
			});

		});

	}

}

/** ask stop */
function ask_stop() {
	var make_label = print_type == 'additive' ?  'print' : 'mill';
	$.SmartMessageBox({
		title : "<i class='fa fa-warning'></i> Do you really want to stop the " + make_label,
		buttons : '[No][Yes]'
	}, function(ButtonPressed) {

		if (ButtonPressed === "Yes") {
			stop_print();
		}
	});
}

function stop_print() {
	var make_label = print_type == 'additive' ?  'print' : 'mill';
	openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Aborting ' + make_label, '', false);
	_do_action('stop', true);
	_stop_monitor();
	_stop_timer();
	stopped = 1;
	setTimeout(_stopper, 30000);

}

/**
 *
 */
function _update_task() {

	if (SOCKET_CONNECTED) {

		var jsonData = {};
		jsonData['function'] = 'update';
		jsonData['estimated_time'] = array_estimated_time;
		jsonData['progress_steps'] = array_progress_steps;
		jsonData['stats_file'] = stats_file;

		var message = {};

		message['name'] = "create";
		message['data'] = jsonData;

		SOCKET.send('message', JSON.stringify(message));

	} else {

		var _async = true;

		$.ajax({
			url : ajax_endpoint + 'ajax/update.php',
			data : {
				//folder       : folder,
				//monitor_file : monitor_file,
				//id_task : id_task,
				//stopped : stopped,
				estimated_time : array_estimated_time,
				progress_steps : array_progress_steps,
				stats_file : stats_file
			},
			type : 'post',
			dataType : 'json',
			async : _async
		}).done(function(response) {

		});

	}

}

/**
 *
 */
function _monitor_call() {

	if (!print_finished) {

		$.ajax({
			url : uri_monitor,
			dataType : 'json',
			async : true,
			cache : false,
		}).done(function(response) {
			monitor(response);
		});

	}

}

function tip(show, message) {

	show = show == 'True' ? true : false;

	if (show) {
		$(".tip-message").html(message);
		$(".tip").show();
	} else {
		$(".tip").hide();
	}

}

/**
 *
 */
var print_monitor = function() {
	if (!SOCKET_CONNECTED) {
		if (!print_finished) {
			_monitor_call();
		}
	}
}
/**
 *
 */
function print_object() {
	
	if(document.getElementById("top-ext-target-temp") != null){
		document.getElementById("top-ext-target-temp").setAttribute('disabled', true);
	}
	document.getElementById("top-bed-target-temp").setAttribute('disabled', true);
	
	disable_button('.jog');
	
	IS_MACRO_ON = true;
	$(".final-step-response").html("");
	
	var make_label = print_type == 'additive' ?  'print' : 'mill';
	
	openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Starting '+ make_label, '', false);
	var timestamp = new Date().getTime();
	//ticker_url = '/temp/print_check_' + timestamp + '.trace';
	ticker_url = '/temp/macro_trace';

	nozzle_temperatures = [];
	nozzle_target_temperatures = [];
	bed_temperatures = [];
	bed_target_temperatures = [];

	$.ajax({
		url : ajax_endpoint + 'ajax/create.php',
		type : 'POST',
		dataType : 'json',
		async : true,
		data : {
			object_id : id_object,
			file : id_file,
			print_type : print_type,
			calibration : calibration,
			time : timestamp
		}
	}).done(function(response) {

		if (response.response == true) {

			if (!SOCKET_CONNECTED) {
				check_notifications();
			}

			id_task = response.id_task;
			monitor_file = response.monitor_file;
			data_file = response.data_file;
			trace_file = response.trace_file;
			uri_monitor = response.uri_monitor;
			uri_trace = response.uri_trace;
			stats_file = response.stats;
			folder = response.folder;
			attributes_file = response.attributes_file;

			var status = JSON.parse(response.status);
			status = jQuery.parseJSON(status);

			monitor(status);
			//faccio partire il monitor 1000 = 1 secondo
			print_monitor();

			interval_monitor = setInterval(print_monitor, monitor_timeout);
			interval_timer = setInterval(_timer, 1000);
			interval_trace = setInterval(_trace, 1000);

			//vado avanti negli step
			$('#btn-next').trigger('click');
			$('#status-icon').removeClass('hide');
			$("#wizard-buttons").hide();
			closeWait();

			ticker_url = '';
			$("#details").trigger('click');
			$(".stop").removeClass('disabled');

			if (print_type == 'additive') {
				$(".subtractive-print").hide();
				initGraphs();
			} else {
				$(".additive-print").hide();
				$(".speed-well").removeClass("col-sm-4").addClass("col-sm-6");
				$(".stats-well").removeClass("col-sm-4").addClass("col-sm-12");
				$(".controls-tab").removeClass("disabled");
				$(".controls-tab").find("a").attr("data-toggle", "tab");
				print_started = true;
				enableSliders();
			}

			IS_TASK_ON = true;
			//disbale wizard previous steps button
			$(".steps >li").removeClass("complete");

		} else {

			$(".final-step-response").append('<h5> Oops <i class="fa fa-meh-o"></i></h5>');
			$(".final-step-response").append('<p class="">' + response.message + '</p>');
			$(".final-step-response").append('<h5>try again</h5>');
			closeWait();
			ticker_url = '';
			$(".jog").removeClass('disabled');
			document.getElementById("top-ext-target-temp").removeAttribute('disabled');
			document.getElementById("top-bed-target-temp").removeAttribute('disabled');

		}

		IS_MACRO_ON = false;

	});

}

function _stop_monitor() {
	clearInterval(interval_monitor);
	$('.controls').addClass('disabled');
}

//controls listener
function _controls_listener(obj) {

	var action = obj.attr('data-action');
	var value = obj.val();

	if (action == 'zup' || action == 'zdown') {
		value = parseFloat($("#z-height").val());
		var z_override = parseFloat($(".z_override").html());

		if (action == 'zup') {
			var new_z_override = z_override + value;
		} else {
			var new_z_override = z_override - value;
		}

		$(".z_override").html(new_z_override.toPrecision(3));

	}

	if (obj.attr("id") == 'light-switch') {

		if (action == 'light-on') {
			obj.attr('data-action', 'light-off');
			obj.removeClass('txt-color-red').addClass('txt-color-yellow');
		} else {
			obj.attr('data-action', 'light-on');
			obj.removeClass('txt-color-yellow').addClass('txt-color-red');
		}

	}

	if (obj.attr("id") == 'turn-off') {

		if (obj.prop('checked')) {
			value = 'yes';
		} else {
			value = 'no';
		}
	}

	if (obj.attr("id") == 'photo') {

		if (obj.prop('checked')) {
			value = 'yes';
		} else {
			value = 'no';
		}
	}

	if (obj.attr("id") == 'mail') {

		value = obj.is(':checked');
	}
	
	if(action == 'notes'){
		value = $("#notes").val();
	}

	console.log(action + ' ' + value);
	_do_action(action, value);
}

function _stop_timer() {
	clearInterval(interval_timer);
}

function _stop_trace() {
	clearInterval(interval_trace);
}
