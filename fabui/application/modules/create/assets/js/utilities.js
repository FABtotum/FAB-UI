/**
 * CREATE MODULE UTILITIES FUNCTIONS
 */
var object;
var file_selected;
var stop_monitor = false;
var interval_monitors;
var monitor_count = 0; //counter for count how many times monitor we'll be called
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
var current_estimated_time; //in seconds
var estimated_time_left; //in seconds
/**/
//var array_estimated_time = new Array();
//var array_progress_steps = new Array();
/**/
var stopped = 0;
/**/
var do_trace = false;
/**/
var precision = 3;
/**/
var scene;
var gc_code_object;

/**/
var model;



/**
 * 
 * @param object
 */
function detail_files(object) {
    
  
    var printable_files = ['.gc', '.gcode', '.nc'];
	var files        = object.files.data;
	var files_number = object.files.number;

	if (files_number > 0) {

		var html = '';
		html += '<table class="table files-table table-bordered table-hover table-stripped smart-form has-tickbox">';
		html += '<thead>';
		html += '<tr>';
		html += '<th></th>';
		html += '<th>File</th>';
		html += '<th class="hidden-xs">Type</th>';
		html += '</tr>';
		html += '</thead>';

		html += '<tbody>'

		$
				.each(
						files,
						function(index, file) {
                           // if(jQuery.inArray( file.file_ext, printable_files) >= 0 ){
                            
                                var extended_class = '';
                                var status = '<i class="icon-fab-printable pull-right"></i>';
                                var icon_type = 'fa-cubes txt-color-blue';
                                
                                if(file.file_ext == '.stl' ){
                                    extended_class = 'warning';
                                    status        = '<i class="icon-fab-not-printable pull-right"></i>';
                                    var icon_type = 'fa-file-text-o txt-color-red';
                                }
                                
                               	
                               	//fa fa-file-text-o
                               	
                               
                            
    							html += '<tr class="file-row '+ extended_class +'" data-id="' + file.id + '">';
    							html += '<td><label class="radio"><input class="obj-file" value="'
    									+ file.id
    									+ '" type="radio" name="file-selected"><i></i> </label></td>';
    							html += '<td><strong><i class="fa '+icon_type+'"></i>  '
    									+ file.file_name + status + '</strong> </td>';
                                
                                var icon_src = '<span class="icon-fab-additive"></span>';          
    							html += '<td class="hidden-xs">' + file.print_type + icon_src +' </td>';
                              
    							html += '</tr>';
                           

						});

		html += '</tbody>';
		html += '</table>';

		$('#files-container').html(html);

		$('.obj-file').on('click', function() {
			select_file($(this).val());
		});
        
        
         $('.file-row').click(function () {
            
            $(this).find(':first-child').find('input').prop("checked", true);
            
            $(".files-table tbody tr").removeClass('success');
            $(this).addClass('success');
            
           	select_file($(this).find(':first-child').find('input').val());
            
            
            /** LAOD INTERSTITIAL */
            
            print_type = file_selected.print_type != '' ? file_selected.print_type : 'additive';
            
            /** MODAL IF IS STL FILE */
            if(file_selected.file_ext == '.stl'){
                $('#myModal').modal('show');
                 $("#btn-next").addClass("disabled");
               
            }else{
            	 $("#btn-next").removeClass("disabled");
            }
            
            
           try
			{
			   
			  $(".model-info").remove();
			  
			  if(file_selected.attributes != '' && file_selected.attributes != 'Processing'){
			  	
			  	var attributes = JSON.parse(file_selected.attributes);
			   
				var model_info_html = '<div class="alert alert-success fade in model-info margin-top-10 info">';
	
				var x = number_format(attributes.dimensions.x, 2, '.', '');
				var y = number_format(attributes.dimensions.y, 2, '.', '');
				var z = number_format(attributes.dimensions.z, 2, '.', '');
				   
				model_info_html += '<h6>Model size: <span class="text-info">'+ x +' x '+ y +' x ' + z + ' mm</span></h6>';
				model_info_html += '<h6>Filament used: <span class="text-info">'+ number_format(attributes.filament, 2, '.', '') +' mm</span></h6>';
				model_info_html += '<h6>Estimated time print: <span class="text-info">'+ attributes.estimated_time+'</span></h6>';
				model_info_html += '<h6>Layers: <span class="text-info">'+ attributes.number_of_layers +'</span></h6>';
				   
				model_info_html += '</div>';
				 
				 
				
			  }else{
			  	
			  	
			  	var message = file_selected.attributes != 'Processing' ? 'No information avaiable for this file' : 'Processing informations..';
			  	
			  	var model_info_html = '<div class="alert alert-warning fade in model-info margin-top-10 info">';
			  	
			  	model_info_html += '<strong><i class="fa fa-warning"></i> '+message+' </strong>';
			  	
			  	model_info_html += '</div>';
			  	
			  }
			  
			  $('#files-container').append(model_info_html);	
			  
			   
			}
			catch(e)
			{
			   
			}
            
           $("#step4").html('');
            
            $.ajax({
               /* url : ajax_endpoint + 'ajax/'+ print_type + '.php',*/
              	url: '/fabui/create/show/' + print_type,
                cache: false
            })
              .done(function( html ) {
                $("#step4").html(html);
              });
            
         });
         
         
         if(request_file > 0 && do_request_file == true){
            
             /** IF I COME FROM OBJECT MANAGER */
            $( ".files-table > tbody > tr" ).each(function() {
               
                if($(this).attr('data-id') == request_file){
                    $(this).trigger('click');
                    do_request_file = false;
                }
               
            });
            
         }

	}

}



/**
 * 
 * @param id_file
 */
function select_file(id_file) {
	
	$.each(object.files.data, function(index, file) {
		if (file.id == id_file){
			file_selected = file;
            
            $('.file-title').html(' > ' + file_selected.file_name);
            
            /** ABLE NEXT BUTTON */
           $('#btn-next').removeClass('disabled');    
        }
	});

}

/**
 * 
 * @param file
 */
function detail_file(file) {
	
	$('#file_name').html(file.file_name);
	$('#orig_name').html(file.orig_name);
	$('#full_path').html(file.full_path);
	$('#file_ext').html(file.file_ext);
	$('#file_size').html(file.file_size);
	$('#insert_date').html(file.insert_date);

	$('.file-title').html(' > ' + file_selected.file_name);
}

/**
 * 
 * @param object
 */
function detail_object(object) {

	// azzerro lista file
	$('#files-container').html('');

	$('#obj_name').html(object.object.obj_name);
	$('#obj_description').html(object.object.obj_description);
	$('#date_insert').html(object.object.date_insert);
	$('#date_updated').html(object.object.date_updated);

	$('#btn-next').removeClass('disabled');
	$('.object-title').html(' > ' + object.object.obj_name);
	$('.file-title').html('');

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
        if(estimated_time_left >= 0){
            $('.estimated-time-left').html(_time_to_string(estimated_time_left));
        }
		

	}
}

/**
 * DISPLAY PRINT TRACE
 */
function _trace() {

	if (!print_finished) {

		$.ajax({
			url: uri_trace,
			async : true,
		}).done(function(response) {
			
			$("#ace-editor").html(response);
			$('#ace-editor').scrollTop(1E10);
		});

	}
}

/**
 * 
 * @param action
 * @param value
 */
function _do_action(action, value) {
	$.ajax({
        url : ajax_endpoint + 'ajax/action.php',
		data : {
			id_task : id_task,
			pid : pid,
			data_file : data_file,
			action : action,
			value : value, 
			progress : progress
		},
		type : 'post',
		dataType : 'json',
		async : true
	}).done(function(response) {
		
		$.smallBox({
    		title : "Success",
    		content : "<i class='fa fa-check'></i> "+ response.message,
    		color : "#659265",
    		iconSmall : "fa fa-thumbs-up bounce animated",
            timeout : 8000
         });
		

	});

}

/** ask stop */
function ask_stop() {

	$.SmartMessageBox({
		title: "Attention!",
		content: "Stop print ?",
		buttons: '[No][Yes]'
	}, function(ButtonPressed) {
	   
		if (ButtonPressed === "Yes") {
		  
          stop_print();
          
		}
		if (ButtonPressed === "No") {
           
		}

	});
    
   ;

}


function stop_print(){
    
    openWait('Stopping print, please wait..');
    _do_action('stop', true);
    _stop_monitor();
    _stop_timer();
    stopped = 1;
    setTimeout(_stopper, 30000);

}


function _stopper(){
    waitContent('Refreshing page');
    document.location.href = document.location.href;
}

/**
 * 
 */
function _update_task() {

	var _async = true;

	$.ajax({
        url : ajax_endpoint + 'ajax/update.php',
		data : {
            //folder       : folder,
            monitor_file : monitor_file,
			id_task : id_task,
			//stopped : stopped,
			estimated_time: array_estimated_time,
			progress_steps: array_progress_steps,
            stats_file : stats_file
		},
		type : 'post',
		dataType : 'json',
		async : _async
	}).done(function(response) {

	});

}





/**
 * 
 */
function _monitor_call(){

	
	if(!print_finished){

		$.ajax({
			//url : ajax_endpoint + '/monitor',
            url: uri_monitor,
			  dataType : 'json',
			  //type: 'post',
			  async : true,
			  //data : {id_task: id_task, file_monitor: monitor_file}
		}).done(function(response) {
			
			

			monitor_count++;
			
			monitor_response = response;
			
			pid = response.print.pid;

                if(parseFloat(response.print.stats.percent) > 0){
                    
                  
                     $(".create-monitor" ).slideDown( "slow", function() {});
                     $('#stop-button').removeClass('disabled');
                     $('.controls').removeClass('disabled');
                    
                }
                
                progress = response.print.stats.percent;

				$('.total-lines'   ).html(response.print.lines);
				$('.current-line'  ).html(response.print.stats.line_number);
				$('.pid'           ).html(response.print.pid);
				$('.temperature'   ).html(response.print.stats.extruder);
				$('.position'      ).html(response.print.stats.position);
				$('#lines-progress').attr('style', 'width:' + parseFloat(response.print.stats.percent) + '%');
				$('#lines-progress').attr('aria-valuetransitiongoal',  parseFloat(response.print.stats.percent));
				$('#lines-progress').attr('aria-valuenow', parseFloat(response.print.stats.percent));
				$('#lines-progress').html(number_format(parseFloat(response.print.stats.percent), 2, ',', '.') + ' %');
			
				$('.progress-status').html(number_format(parseFloat(response.print.stats.percent), 2, ',', '.') + ' %');
				
               
                $('#label-progress').html('(' +	number_format(parseFloat(response.print.stats.percent), 2, ',', '.') + ' % )');
                
                $("#temp1").val(parseInt(monitor_response.print.stats.extruder), {	animate: true });
                $("#temp2").val(parseInt(monitor_response.print.stats.bed), {	animate: true });
                $("#label-temp1").html(parseInt(monitor_response.print.stats.extruder));
                $("#label-temp2").html(parseInt(monitor_response.print.stats.bed));
                 
                
               
                
                if(parseInt(extruder_target) == 0){
                	$("#label-temp1-target").html(parseInt(monitor_response.print.stats.extruder_target));
                	extruder_target = parseInt(monitor_response.print.stats.extruder_target);
                }else{
                	$("#label-temp1-target").html(extruder_target);
                }
                
                
                 if(parseInt(bed_target) == 0){
                	$("#label-temp2-target").html(parseInt(monitor_response.print.stats.bed_target));
                	bed_target = parseInt(monitor_response.print.stats.bed_target);
                }else{
                	$("#label-temp2-target").html(bed_target);
                }
                
			
			if(response.print.completed == 1){
				print_finished = true;
				
			}


			_update_task();
			
			
			estimated_time_left = ((elapsed_time / response.print.stats.percent) * 100) - elapsed_time;
			
			tip(monitor_response.print.tip.show, monitor_response.print.tip.message);
			
			
			
		});


	}
	
}


function tip(show, message){
	
	
	show = show == 'True' ? true : false;
	
	if(show){
		$(".tip-message").html(message);
		$(".tip").show();
	}else{
		$(".tip").hide();
	}
	
}

/**
 * 
 */
var print_monitor = function (){

	//controllo finch� la stampa non � finita
	if(!print_finished){
		_monitor_call();
		//_trace();
	}else{ //se � finita, termino
		_stop_monitor();
		_stop_timer();
		_stop_trace();
		//_update_task();
		$('.controls').addClass('disabled');
		$('.progress').removeClass('active');
		$('.estimated-time').html('-');
		$('.estimated-time-left').html('-');
        /** GO TO NEXT STEP */
        $("#btn-next").trigger('click');
        unfreeze_menu();
        $("#wizard-buttons").hide();
        
        				
	}
	
}
 

/**
 * 
 */
function print_object(){
    
     $(".final-step-response").html("");
     openWait('Starting');
     
     var timestamp = new Date().getTime();    
     ticker_url = '/temp/print_check_' + timestamp + '.trace';

	$.ajax({
		  //url: ajax_endpoint + '/do_print/' + object.object.id + '/' + file_selected.id,
         url : ajax_endpoint + 'ajax/create.php',
         // url: '/fabui/create/start',
		  type: 'POST',
          dataType : 'json',
		  async : true,
          data: {object: object.object.id, file: file_selected.id, print_type: print_type, calibration:calibration, time: timestamp}
	}).done(function(response) {
        //respons
        if(response.response == true){
            
            /** CHECK BACKGROUND TASKS FOR NOTIFICATIONS */
            check_notifications()
            /*freeze_menu('create');*/
            
            id_task      = response.id_task;
    		monitor_file = response.monitor_file;
    		data_file    = response.data_file;
    		trace_file   = response.trace_file;
            uri_monitor  = response.uri_monitor;
            uri_trace    = response.uri_trace;
            stats_file   = response.stats;
            folder       = response.folder;
            
            var status = JSON.parse(response.status);
            status = jQuery.parseJSON(status);
                   
    		//print_monitor();
            $('#lines-progress').attr('style', 'width:' + parseFloat(status.print.stats.percent) + '%');
			$('#lines-progress').attr('aria-valuetransitiongoal',  parseFloat(status.print.stats.percent));
			$('#lines-progress').attr('aria-valuenow', parseFloat(status.print.stats.percent));
			$('#lines-progress').html(number_format(parseFloat(status.print.stats.percent), 2, ',', '.') + ' %');
			$('.progress-status').html(	number_format(parseFloat(status.print.stats.percent), 2, ',', '.') + ' %');
            $('#label-progress').html('(' +	number_format(parseFloat(status.print.stats.percent), 2, ',', '.') + ' % )');
    		
    		//azzero contatore monitor
    		monitor_count = 0;
    
    		//faccio partire il monitor 1000 = 1 secondo
    		print_monitor();
    		interval_monitor = setInterval(print_monitor, monitor_timeout);
    		interval_timer   = setInterval(_timer, 1000);
    		
    		if(do_trace == true){	
    			interval_trace   = setInterval(_trace, 1000);
    		}
    
    		//printo a video il commando utitlizzato per lanciare il gpusher (debug)
    		$('.command').html(response.command);
    
    		
    		//freeze menu
    		//vado avanti negli step
    		$('#btn-next').trigger('click');
    		$('#status-icon').removeClass('hide');
            $("#wizard-buttons").hide();
            closeWait();
            ticker_url = '';
            
            
            $("#details").trigger('click');
            $(".stop").removeClass('disabled');
            
            
            
        }else{
            $(".final-step-response").append('<h5> Oops <i class="fa fa-meh-o"></i></h5>');
            $(".final-step-response").append('<p class="">' + response.message + '</p>');
            $(".final-step-response").append('<h5>try again</h5>');
            closeWait();
            ticker_url = '';
            
        }
				
	});

}

/*
* OBJECT
*/
$('.obj').click(function () {
    
    
    $(this).find(':first-child').find('input').prop("checked", true);
    $("#objects_table tbody tr").removeClass('success');
    $(this).addClass('success');
    var id = $(this).attr("data-id");
	
	$.ajax({
		  url: ajax_object_endopoint + 'ajax/object.php',
		  dataType : 'json',
          type: "POST", 
		  async: true,
          data : { printable : true, id_object : id},
		  beforeSend: function( xhr ) {
		  }
	}).done(function(response) {
	
		object = response;
		detail_object(object);
		detail_files(object);
	});
     
});



function check_wizard(){

	var item = $('.wizard').wizard('selectedItem');
	
	$('#btn-next').show();

	if(item.step > 1){
		$('#btn-prev').removeClass('disabled');
	}

	if(item.step == 1){
		$('#btn-prev').addClass('disabled');
	}
    
    
    
    if(item.step == 2 && file_selected == ''){
    	$('#btn-next').addClass('disabled');
    }
    
    
    if(item.step == 3){
    	
    	$('#btn-next').hide();
    }
    
    if(item.step >= 4){
        
        $("#wizard-buttons").hide();
        
       
        
    }else{
        
    }

}



function _stop_monitor(){
	clearInterval(interval_monitor);
	$('.controls').addClass('disabled');
}


//controls listener
function _controls_listener(obj){
	
	var action = obj.attr('data-action');
	var value  = obj.val();
	
    
    if(obj.attr("id") == 'light-switch'){
        
        if(action == 'light-on'){
            obj.attr('data-action', 'light-off');
            obj.removeClass('txt-color-red').addClass('txt-color-yellow');
        }else{
            obj.attr('data-action', 'light-on');
            obj.removeClass('txt-color-yellow').addClass('txt-color-red');
        }
        
    }
    
    
    if(obj.attr("id") == 'turn-off'){
        
         if (obj.prop('checked')) {
            value = 'yes';    
        } else {
		    value = 'no';         
        }
    }
    
    
    if(obj.attr("id") == 'photo'){
        
         if (obj.prop('checked')) {
            value = 'yes';    
        } else {
		    value = 'no';         
        }
    }
    
    
    
    if(obj.attr("id") == 'send-mail'){
        
        if(action == 'send-mail-true'){
            obj.attr('data-action', 'send-mail-false');
            obj.attr('title', "A mail will be send at the end of the print");
            obj.removeClass('txt-color-red').addClass('txt-color-green');
        }else{
            obj.attr('data-action', 'send-mail-true');
            obj.removeClass('txt-color-green').addClass('txt-color-red');
        }
        
    }
    
    

    _do_action(action, value);
}





function _stop_timer(){
	clearInterval(interval_timer);
}

function _stop_trace(){
	clearInterval(interval_trace);
}



function _resume(){
    
    $("#details").trigger('click');
    $( ".create-monitor" ).slideDown( "slow", function() {});
	monitor_count = 0;
	//faccio partire il monitor 1000 = 1 secondo
	interval_monitor = setInterval(print_monitor, monitor_timeout);
	interval_timer   = setInterval(_timer, 1000);
	//interval_trace   = setInterval(_trace, 1000);
}



