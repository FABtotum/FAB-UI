/**
 * CREATE MODULE UTILITIES FUNCTIONS
 */
//init...
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
		html += '<th>Type</th>';
        html += '<th class="hidden-xs">Size</th>';
        html += '<th class="hidden-xs">Full path</th>';
		html += '<th class="hidden-xs"></th>';
		html += '</tr>';
		html += '</thead>';

		html += '<tbody>'

		$
				.each(
						files,
						function(index, file) {
                           // if(jQuery.inArray( file.file_ext, printable_files) >= 0 ){
                            
                                var extended_class = '';
                                var status = '<i class="fa fa-check pull-right"></i>';
                                
                                if(file.file_ext == '.stl' ){
                                    extended_class = 'warning';
                                    status        = '<i class="fa fa-warning pull-right"></i>';
                                }
                                
                               
                            
    							html += '<tr class="file-row '+ extended_class +'" data-id="' + file.id + '">';
    							html += '<td><label class="radio"><input class="obj-file" value="'
    									+ file.id
    									+ '" type="radio" name="file-selected"><i></i> </label></td>';
    							html += '<td><i class="fa fa-file-o"></i>  '
    									+ file.file_name + status + ' </td>';
                                
                                var icon_src = '<span class="icon-fab-additive"></span>';      
                                        
    							html += '<td>' + file.print_type + icon_src +' </td>';
                                html += '<td class="hidden-xs">' + bytesToSize(file.file_size) + ' </td>';
                                html += '<td class="hidden-xs">' + file.full_path + ' </td>';
    							html += '<td class="hidden-xs"> </td>';
    							html += '</tr>';
                            //}

						});

		html += '</tbody>';
		html += '</table>';

		$('#files-container').html(html);

		$('.obj-file').on('click', function() {
			select_file($(this).val());
			//detail_file(file_selected);
			//detail_model(file_selected);			
			//preview_file(file_selected);
		});
        
        
         $('.file-row').click(function () {
            
            //alert("CLICK");
            
            //var _file = $(this).find(':first-child').find('input');
            $(this).find(':first-child').find('input').prop("checked", true);
           	select_file($(this).find(':first-child').find('input').val());
            //_file.trigger('click');
            
            
            
            /** LAOD INTERSTITIAL */
            
            print_type = file_selected.print_type != '' ? file_selected.print_type : 'additive';
            
            /** MODAL IF IS STL FILE */
            if(file_selected.file_ext == '.stl'){
                $('#myModal').modal('show');
            }else{
            }
             


            $("#step4").html('');
            
            $.ajax({
                url : ajax_endpoint + 'ajax/'+ print_type + '.php',
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
            editor.getSession().setValue(response);
            editor.navigateLineEnd();

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
			value : value
		},
		type : 'post',
		dataType : 'json',
		async : true
	}).done(function(response) {

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
    
    openWait('Stopping print');
    _do_action('stop', true);
    _stop_monitor();
    _stop_timer();
    _stop_trace();
    stopped = 1;
    _update_task();
    interval_stop   = setInterval(_stopper, 1000);

}


function _stopper(){
	elapsed_time_stop = (parseInt(elapsed_time_stop) + 1);
    if(elapsed_time_stop == max_time_stop){
        document.location.href = document.location.href;
    }
}

/**
 * 
 */
function _update_task() {

	var _async = true;

	$.ajax({
        url : ajax_endpoint + 'ajax/update.php',
		data : {
            folder       : folder,
            monitor_file : monitor_file,
			id_task : id_task,
			stopped : stopped,
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

			
			monitor_response = response;
			pid = response.print.pid;

			//if pid > 0, so if process id exist, so if printer start to print
			//if(pid > 0) {
			 
             
                if(parseFloat(response.print.stats.percent) > 0){
                    
                  
                     $( ".create-monitor" ).slideDown( "slow", function() {});
                      
                      $('#stop-button').removeClass('disabled');
                      $('.controls').removeClass('disabled');
                    
                }

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
				//$('.progress-status').html(parseFloat(response.print.stats.percent).toFixed(2) + '%');
               
                $('#label-progress').html('(' +	number_format(parseFloat(response.print.stats.percent), 2, ',', '.') + ' % )');
                
                
                 $("#temp1").val(parseInt(response.print.stats.extruder), {	animate: true });
                 $("#temp2").val(parseInt(response.print.stats.bed), {	animate: true });
                 $("#label-temp1").html(parseInt(response.print.stats.extruder));
                 $("#label-temp2").html(parseInt(response.print.stats.bed));
                
					
				monitor_count++ ;
			//}

			
			//al primo giro
			if(monitor_count == 1){
				//aggiorno le info del task
				_update_task();
				
				
                

				//progress_step = parseFloat(response.print.stats.percent);
				progress_step = precise_round(response.print.stats.percent, precision);
				second_for_step = elapsed_time;
				
				//if the process is already running
				if(is_running == true){
				
					
					current_estimated_time = parseFloat((eval(array_estimated_time.join('+')))/(eval(array_progress_steps.join('+')))).toFixed(0);
                    if(!isNaN(current_estimated_time)){				
					   $('.estimated-time').html(_time_to_string(current_estimated_time));
                    }
					estimated_time_left = (parseInt(current_estimated_time) - parseInt(elapsed_time)); //stima secondi rimasti = stima secondi totali - elapsed_time dall'inizio della stampa
					estimated_time_left = Math.abs(estimated_time_left);
					
				}else{
					
					$('.estimated-time').html('-');
					$('.estimated-time-left').html('-');
					
				}

				
			}
			
			if(response.print.completed == 1){
				print_finished = true;
				
			}


			/**
			* CALCULING ESTIMATED TIME LEFT
			**/
			//se cambia la percentuale verifico di quanta è cambiata ne calcolo il tempo e faccio una stima di quanto ci si mette a completare tutto al 100%
			if(progress_step != precise_round(response.print.stats.percent, precision)){

				
				var second_for_this_step            = (elapsed_time - second_for_step);
				var progress_for_this_step          = precise_round(Math.abs(precise_round(response.print.stats.percent, 2) - progress_step), precision);
				var estimated_seconds_for_all_steps = precise_round(parseFloat((second_for_this_step * 100) / progress_for_this_step), precision); 


				//calcolo la media ponderata per la stima del tempo totale di stampa
                
                if(!isNaN(progress_for_this_step)){
                    array_progress_steps.push(progress_for_this_step);
                }
                
                var print_estimated_time = precise_round(Math.abs(estimated_seconds_for_all_steps * progress_for_this_step), precision);
				
                if(!isNaN(print_estimated_time)){
                    array_estimated_time.push(print_estimated_time);
                }
                
				//array_estimated_time.push(precise_round(Math.abs(estimated_seconds_for_all_steps * progress_for_this_step), precision));
				
				current_estimated_time = precise_round(parseFloat((eval(array_estimated_time.join('+')))/(eval(array_progress_steps.join('+')))), 0);
                				
				estimated_time_left = (parseInt(current_estimated_time) - parseInt(elapsed_time)); //stima secondi rimasti = stima secondi totali - elapsed_time dall'inizio della stampa
				estimated_time_left = Math.abs(estimated_time_left);
				
				
                if(!isNaN(current_estimated_time)){
				    $('.estimated-time').html(_time_to_string(current_estimated_time));
                }

				
				progress_step   = precise_round(response.print.stats.percent, precision);
				second_for_step = elapsed_time;
				
				_update_task();

			}
			
		});


	}
	
}


/**
 * 
 */
var print_monitor = function (){

	//controllo finchè la stampa non è finita
	if(!print_finished){
		_monitor_call();
		//_trace();
	}else{ //se è finita, termino
		_stop_monitor();
		_stop_timer();
		_stop_trace();
		_update_task();
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
     openWait('Initializiang print..');
     
     var timestamp = new Date().getTime();
            
     ticker_url = '/temp/print_check_' + timestamp + '.trace';

	$.ajax({
		  //url: ajax_endpoint + '/do_print/' + object.object.id + '/' + file_selected.id,
          url : ajax_endpoint + 'ajax/create.php',
		  type: 'POST',
          dataType : 'json',
		  async : true,
          data: {object: object.object.id, file: file_selected.id, print_type: print_type, skip:skip, time: timestamp}
	}).done(function(response) {
        //respons
        if(response.response == true){
            
            /** CHECK BACKGROUND TASKS FOR NOTIFICATIONS */
            check_tasks();
            freeze_menu('create');
            
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
            
            //krios
            $("#details").trigger('click');
            
            
            
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
    
    
    $(this).find(':first-child').find('input').prop("checked", true)
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

	if(item.step > 1){
		$('#btn-prev').removeClass('disabled');
	}

	if(item.step == 1){
		$('#btn-prev').addClass('disabled');
	}
    
    
    if(item.step >= 4){
        
        //$("#wizard-buttons").hide();
        
    }else{
        //$("#wizard-buttons").show();
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



