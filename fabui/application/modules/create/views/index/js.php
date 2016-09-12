<script type="text/javascript">
	
	
	IS_TASK_ON = <?php echo  $_running ? 'true' : 'false' ?>;
	
	var id_task = <?php echo $_id_task; ?>;
	var pid     = <?php echo $_pid; ?>;
	var id_file = <?php echo $_id_file ?>;
	var id_object = <?php echo $_id_object ?>;
	/**/
	var monitor_file = "<?php echo $_monitor_file; ?>";
	var data_file    = "<?php echo $_data_file; ?>";
	var trace_file   = "<?php echo $_trace_file; ?>";
	var debug_file   = "<?php echo $_debug_file; ?>";
    var uri_monitor  = "<?php echo $_uri_monitor; ?>";
    var uri_trace    = "<?php echo $_uri_trace; ?>";
    var stats_file   = "<?php echo $_stats_file; ?>"; 
    var folder       = "<?php echo $_folder; ?>";
    var print_type   = "<?php echo $_print_type ?>";
    var progress_percent = <?php echo intval($progress_percent); ?>;
    var print_started = <?php echo $print_started ?>;
    var attributes_file = '<?php echo $attributes_file; ?>';
    
    
	/**/
	var monitor_response;
	<?php if($_running && strlen($_monitor) > 0): ?>
	monitor_response = <?php echo $_monitor; ?>;
	<?php endif; ?>
	/**/
	var elapsed_time  = <?php echo $_seconds ?>; 

	var array_estimated_time =  <?php echo $_estimated_time; ?>;
	var array_progress_steps =  <?php echo $_progress_steps; ?>;
	
    var ajax_endpoint         = '<?php echo module_url('create') ?>';
	
    var ajax_object_endpoint = '<?php echo module_url('objectmanager')?>';
    var ajax_intertitial_endpoint = '<?php echo module_url('interstitial')  ?>';
    var ajax_jog_endpoint = '<?php echo module_url('jog'); ?>';

	var is_running = <?php echo  $_running ? 'true' : 'false' ?>;
	var server_host = 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/';
    
    /** IF I COME FROM OBJECT MANAGER */
    var request_file = <?php echo $_request_file != FALSE ? $_request_file : 0 ?>;
    var do_request_file = <?php echo $_request_file != FALSE ? 'true' : 'false' ?>;
    
    /** ACE EDITOR */
    /*
    var editor;
    */
    var view_details = false;

    /** PRE-PRINT */
    var pre_print_trace    = '';
    var pre_print_response = '';
    var pre_print_url_response = '';
    var skip                   = 0;
    
    /** TICKER */
    var ticker_url = '';
    var interval_ticker;
    
    var extruder_target = <?php echo $ext_target == "" ? '0' : $ext_target ?>; 
    var bed_target      = <?php echo $bed_target == "" ? '0': $bed_target; ?>;
    
    
    /** CALIBRATION */
	var calibration = 'homing';
	
	/** PROGRESS */
	var progress = 0;
	
	var monitor_count = 0;
	
	var isEngageFeeder = 0;
	
	var process_type;
	
	var oTable;
	var recenTable;
	
	
	var blockSliderExt = false;
	var blockSliderBed = false;
	
	
	var max_plot = 200;
	var nozzle_temperatures = [];
	var nozzle_target_temperatures = [];
	var bed_temperatures = [];
	var bed_target_temperatures = [];
	var nozzlePlot = "";
	var bedPlot = "";
	
	var interval_charts;
	
	var now = new Date().getTime();
	var values = [];
	
	var speed = <?php echo $_velocity != '' ? $_velocity : 100 ?>;
	
	var layer_total = <?php echo $layer_total; ?>;
	var layer_actual = <?php echo $layer_actual; ?>;
	
	var $chrt_border_color = "#efefef";
	var $chrt_grid_color = "#DDD"
	var $chrt_main = "#E24913";
	/* red       */
	var $chrt_second = "#6595b4";
	/* blue      */
	var $chrt_third = "#FF9F01";
	/* orange    */
	var $chrt_fourth = "#7e9d3a";
	/* green     */
	var $chrt_fifth = "#BD362F";
	/* dark red  */
	var $chrt_mono = "#000";
	
	var speed_slider;
	var nozzle_slider;
	var bed_slider;
	var fan_slider;
	var flow_rate_slider;
	var rpm_slider;
	
	var z_override  = <?php echo $z_override; ?>;
	var interval_autostart;
	
	var recent_file_selected;
	var startFromRecent = false;
	var object;
	var autostart_timer = 20;

	var SOFT_EXTRUDER_MIN = 175;
	
		
	$(document).ready(function() {
		
		
		$('.progress-bar').progressbar({
			display_text : 'fill'
		});

		
 	  	oTable = $('#objects_table').dataTable({
			"aaSorting": [],
			"bFilter": true,
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth": false,
		});
		
		recenTable = $('#recent_table').dataTable({
			"aaSorting": [],
			"bFilter": true,
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth": false,
		});
		
		
		$(".recent-obj-file").on('click', function() {
			select_recent_file($(this).val());
		});
		
        $('.file-recent-row').on('click', select_file_recent_row);
        
        
        /*
		* WIZARD
		*/
		var wizard = $('.wizard').wizard({});

		$('#btn-next').on('click', function() {
			
			if(check_wizard_next()){
				
				
				var step = $('.wizard').wizard('selectedItem').step
				
				if(startFromRecent == true && step==1) $('.wizard').wizard('selectedItem', { step: 3 });
				else $('.wizard').wizard('next');
			}
			
		});

		$('#btn-prev').on('click', function() {
			
			if(check_wizard_prev()){
				
				var step = $('.wizard').wizard('selectedItem').step
				if(startFromRecent == true && step==3) $('.wizard').wizard('selectedItem', { step: 1 });
				$('.wizard').wizard('previous');
			}
			
		});
		
		$('.wizard').on('changed.fu.wizard', function (evt, data) {
			check_wizard();
		});

		$('.wizard').on('stepclick', function(e, data) {
			
			$('.wizard').wizard('selectedItem', { step: data.step });
			check_wizard();
		});
        
        /** CHECK IF I CAN MOVE TO NEXT STEP */
        function check_wizard_next(){
        	
        	var step = $('.wizard').wizard('selectedItem').step;
        	
        	switch(step){
        		case 1:
        			
        			if(request_file > 0){
        				return true;
        			}
        			
        			if(typeof object != 'undefined' && $("#table-objects").is(":visible")){
        				return true;
        			} 
        			
        			if(startFromRecent == true && $("#recent_table").is(":visible")){
        				return true;
        			}
        			break;
        		case 2:
        			if(request_file > 0){
        				return true;
        			}
        			
        			if(typeof file_selected != 'undefined' && file_selected != ''){
        				return true;
        			}
        			break;
        		case 3:
        		case 4:
        			return true;
        			break;
        	}
        	
        	return false;
        	
        	
        }
        
        /** CHECK IF I CAN MOVE BACK TO PREV STEP */
        function check_wizard_prev(){
        	var step = $('.wizard').wizard('selectedItem').step;
        	switch(step){
        		
        		case 2:
        			return true;
        			break;
        		case 3:
        			return true;
        			break;
        			
        	}
        	
        	return false;
        }
        
        /** ENABLE / DISABLE BUTTONS */
        function check_wizard(){
        	
        	
        	var step = $('.wizard').wizard('selectedItem').step;    	
        	switch(step){
        		case 1:
        			disable_button("#btn-prev");
        			if(startFromRecent == true){
        				enable_button("#btn-next");
        				
        				
        			}else if(typeof object != 'undefined'){
        				enable_button("#btn-next");
        			}
        			
        			stopCountDown();
        			break;
        		case 2:
        			enable_button("#btn-prev");
        			if(typeof file_selected != 'undefined' && file_selected != '') enable_button("#btn-next");
        			else disable_button("#btn-next");
        			
        			stopCountDown();
        			break;
        		case 3:
        			
        			enable_button("#btn-prev");
        			disable_button("#btn-next");
        			
        			if($('input[name="calibration"]').is(":visible")){
        				startCountDown();
        			}
        			
        			break;
        	}
        }
        
        
        
        
        $("#turn-off").on('change', function(){
            _controls_listener($(this));
        });
        
        

		<?php if(!$_running):?>
		$(".spinner").spinner();
		
        /** PROCESS STL TO GCODE BUTTON */
        $('#process-button').on('click', function(){
            document.location.href = '<?php echo base_url("objectmanager/prepare") ?>/'+ process_type + '/' + object.object.id + '/' + file_selected.id + '?return=1' ;
        });

		
		/**
		* Print button action
		*/
		$('#print-button').on('click', function() {
			print_object();	
		});


		<?php endif; ?>

		$('#trace').on('click', function(){

			if($(this).is(':checked')){
				do_trace = true;
				interval_trace   = setInterval(_trace, 1000);
				$( '.trace' ).show( "fast");
			}else{
				do_trace = false;
				$('.trace').hide("fast");
				_stop_trace();
			}

		});
        
        
        $('#details').on('click', function(){
         
            if(!do_trace){
                do_trace = true;
				interval_trace   = setInterval(_trace, 1000);
				/*$( '.details-container' ).show( "fast" );*/
                
                $( ".details-container" ).slideDown( "slow", function() {
						
					$('#details').find('i').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');                       
                });
                
                
                
            }else{
                do_trace = false;
				/*$('.details-container').hide("fast");*/
                $( ".details-container" ).slideUp( "slow", function() {
                      $('#details').find('i').removeClass('fa-angle-double-up').addClass('fa-angle-double-down'); 
                });
				_stop_trace();
            }   
            
            
        });
        
        
        /** INIT SLIDERS */
        initSliders();
        
        /** DISABLE SLIDERS */
        disableControlsTab();
        
        
        $(".sliders").on({
		      slide: manage_slide,
              change: manage_change
	   });
	   
	   
	   

		/**
		* Controls action (play, pause, stop, velocity, temperature) */
		
		$('.controls').on('click', function() {
			_controls_listener($(this));
		});
        
        
        $('#stop-button').on('click', ask_stop);

	
		
		<?php if($_running): ?>
	
		$('.wizard').wizard('selectedItem', { step: 4 });
		_resume();
        
        

		<?php endif; ?>
        
        <?php if($_request_file != FALSE && $_request_obj != FALSE && $_running == FALSE): ?>
                  
             /** IF I COME FROM OBJECT MANAGER */
            
            var rows = oTable.fnGetNodes( );
            
            $(rows).each(function() {
               
                if($(this).attr('data-id') == <?php echo $_request_obj?>){
                    $(this).trigger('click');
                }
               
            });
            
            $("#btn-next").trigger('click');
            
           $("#btn-next").trigger('click');
                   
        <?php endif; ?>
        
     
        /** TICKER */
        interval_ticker   = setInterval(ticker, 2500);
        
        var $chrt_fourth = "#6595b4";
        
        
       $(".restart").on('click', restart_create);
       $(".new").on('click', new_create);
       $(".save-z-override").on('click', save_z_override);
        
	});
    

/** READ MACRO'S TRACE */    
function ticker(){
	
    if(!SOCKET_CONNECTED){
	    if(ticker_url != ''){
	    	
	    	$.ajax({
				type: 'GET',
				url: ticker_url,
			}).done(function(data, statusText, xhr) {
				
				if(xhr.status == 200){
					data = data.replace("\n", "<br>");
					waitContent(data);
				
				}
				
			});	
	    	
	    	
	    }
    }
}


    
function manage_slide(e){
    
   var id = $(this).attr('id');
       
   switch(id){
   	
   	case 'velocity':
   		 $(".label-"+ id ).html('' + parseInt($(this).val()) + '%');
   		 speed = parseInt($(this).val());
   		 
   		 var speed_percent = (speed/500) * 100;
   		 $('.speed-progress').attr('style', 'width:' + parseFloat(speed_percent) + '%');
   		 
   		 break;
   	case 'temp1':
   		extruder_target = parseInt($(this).val());
   		$("#label-"+ id + '-target' ).html('' + parseInt($(this).val()) + '&deg;C');
   		blockSliderExt = true;
   		break;
   	case 'temp2':
   		bed_target = parseInt($(this).val());
   		$("#label-"+ id + '-target' ).html('' + parseInt($(this).val()) + '&deg;C');
   		blockSliderBed = true;
   		break;
   	case 'rpm':
   		var rpm_percent = (parseInt($(this).val())/14000) * 100;
   		$(".label-"+ id ).html('' + parseInt($(this).val()) + '');
   		$('.rpm-progress').attr('style', 'width:' + parseFloat(rpm_percent) + '%');
   		break;
   	case 'fan':
   	
   		$(".label-"+ id ).html('' + parseInt($(this).val()) + '%');
   		$('.fan-progress').attr('style', 'width:' + parseInt($(this).val()) + '%');
   		break;
   	case 'flow-rate':
   		$(".label-"+ id ).html('' + parseInt($(this).val()) + '%');
   		
   		
   		var flow_percent =  (parseInt($(this).val()) / 500) * 100;
   		
   		$('.flow-rate-progress').attr('style', 'width:' + parseInt(flow_percent) + '%');
   		break;
   }
    
    
}


function manage_change(e){
    
   	var action = $(this).attr('data-action');
	var value  = parseInt($(this).val());

	_do_action(action, value);

	if(action == 'stop'){
		_stop_monitor();
		_stop_timer();
		_stop_trace();
		stopped = 1;
		/*_update_task();*/
	}
    
}



/**
 *  OVVERRIDE GENERAL MONITOR FUNCTION
 */
function manage_task_monitor(obj){
	
	if(obj.type=="monitor"){
		if(obj.content != ""){
			data = jQuery.parseJSON(obj.content);
			monitor(data);
		}
		
	}
}



function monitor(data){

	if(data.print.hasOwnProperty('status')) handleTaskStatus(data.print.status);
	
	if (data.print.completed == 'True') {
		print_finished = true;
		finalize_print();
		return;
	}
	
	
	monitor_count++;
	
	if (parseFloat(data.print.stats.percent) > 0) {

		$(".create-monitor").slideDown("slow", function() {});
		$('#stop-button').removeClass('disabled');
		$('.controls').removeClass('disabled');
		
		
		
	}
	
	if(!print_started){
		if(data.print.print_started == "True"){
			$(".controls-tab").removeClass("disabled");
			$(".controls-tab").find("a").attr("data-toggle", "tab").trigger("click");
			/*$(".controls-tab").find("a").trigger("click");*/
			print_started = true;
			enableControlsTab();
		}
	}
	
	if(monitor_count == 1){
		
	}
	
	if (!blockSliderExt) {
		
		nozzle_slider.noUiSlider.set([parseInt(data.print.stats.extruder_target)]); 
		
		$("#label-temp1-target").html(parseInt(data.print.stats.extruder_target) + '&deg;C');
		$(".nozzle-target").html(parseInt(data.print.stats.extruder_target));
	}

	if (!blockSliderBed) {
		
		bed_slider.noUiSlider.set([parseInt(data.print.stats.bed_target)]);
		$("#label-temp2-target").html(parseInt(data.print.stats.bed_target) + '&deg;C');
	}
	
	progress = data.print.stats.percent;
	
	if(data.print.stats.hasOwnProperty('layers')){
		
		if(data.print.stats.layers.total.length == 1){
			
			$(".layers").removeClass('hidden');
			$(".layer-actual").html(parseInt(data.print.stats.layers.actual));
			$(".layer-total").html(parseInt(data.print.stats.layers.total[0]));
			
			
			var layer_percent = (parseInt(data.print.stats.layers.actual) / parseInt(data.print.stats.layers.total[0]) ) * 100;
			$('.progress-layer').attr('style', 'width:' + parseFloat(layer_percent) + '%');
			$('.layer-percent').html('('+number_format(parseFloat(layer_percent), 2, ',', '.') +'%)');
			$(".layer").html(parseInt(data.print.stats.layers.actual) + ' of ' + parseInt(data.print.stats.layers.total[0]));	
			
			
		}
		/*
		if(parseInt(data.print.stats.layers.total) > 0){
			$(".layers").removeClass('hidden');
			$(".layer-actual").html(parseInt(data.print.stats.layers.actual));
			$(".layer-total").html(parseInt(data.print.stats.layers.total));
			
			
			var layer_percent = (parseInt(data.print.stats.layers.actual) / parseInt(data.print.stats.layers.total) ) * 100;
			$('.progress-layer').attr('style', 'width:' + parseFloat(layer_percent) + '%');
			$('.layer-percent').html('('+number_format(parseFloat(layer_percent), 2, ',', '.') +'%)');
			$(".layer").html(parseInt(data.print.stats.layers.actual) + ' of ' + parseInt(data.print.stats.layers.total));	
		}*/
		
	}
	
	
	
	
	
	
	
	
	
	$('.total-lines').html(data.print.lines);
	$('.current-line').html(data.print.stats.line_number);
	$('.pid').html(data.print.pid);
	$('.temperature').html(data.print.stats.extruder);
	$('.position').html(data.print.stats.position);
	
	
	
	document.getElementById('act-ext-temp').noUiSlider.set([parseInt(data.print.stats.extruder)]);
	document.getElementById('act-bed-temp').noUiSlider.set([parseInt(data.print.stats.bed)]);
	
	
	$('#lines-progress').attr('style', 'width:' + parseFloat(data.print.stats.percent) + '%');
	$('#lines-progress').attr('aria-valuetransitiongoal', parseFloat(data.print.stats.percent));
	$('#lines-progress').attr('aria-valuenow', parseFloat(data.print.stats.percent));
	/*$('#lines-progress').html(number_format(parseFloat(data.print.stats.percent), 2, ',', '.') + ' %');*/
	
	$('.progress-status').html(number_format(parseFloat(data.print.stats.percent), 2, ',', '.') + ' %');

	$('#label-progress').html('(' + number_format(parseFloat(data.print.stats.percent), 2, ',', '.') + ' % )');

	$("#label-temp1").html(parseInt(data.print.stats.extruder) + '&deg;C');
	$(".nozzle-temperature").html(parseInt(data.print.stats.extruder));
	$(".nozzle-target").html(parseInt(data.print.stats.extruder_target));
	$("#label-temp2").html(parseInt(data.print.stats.bed) + '&deg;C');
	$(".bed-temperature").html(parseInt(data.print.stats.bed));
	$(".bed-target").html(parseInt(data.print.stats.bed_target));

	extruder_target = parseInt(data.print.stats.extruder_target);

	bed_target = parseInt(data.print.stats.bed_target);
	
	

	/*_update_task();*/

	estimated_time_left = ((elapsed_time / data.print.stats.percent) * 100) - elapsed_time;
	
	if(data.print.hasOwnProperty('tip')){
		tip(data.print.tip.show, data.print.tip.message);
	}
	
	
	/*** GRAPHS ***/
	addNozzleTemperature(data.print.stats.extruder);
	addNozzleTargetTemperature(data.print.stats.extruder_target);
	addBedTemperature(data.print.stats.bed);
	addBedTargetTemperature(data.print.stats.bed_target);
	
	/*updateNozzleGraph();
	updateBedGraph();*/
		
	
	var fan_percent = (parseFloat(data.print.stats.fan) / 255) * 100;
	
	document.getElementById('fan').noUiSlider.set([parseInt(fan_percent)]);
			
	$(".label-fan").html('' + parseInt(fan_percent) + '%');
	$('.fan-progress').attr('style', 'width:' + parseInt(fan_percent) + '%');
	
	
	var rpm_percent = (parseInt(data.print.stats.rpm)/14000) * 100;
   	$(".label-rpm").html(parseInt(data.print.stats.rpm));
   	$('.rpm-progress').attr('style', 'width:' + parseFloat(rpm_percent) + '%');
	
	document.getElementById('rpm').noUiSlider.set([parseInt(data.print.stats.rpm)]);
	
	$(".z_override").html(data.print.stats.z_override);
	z_override = data.print.stats.z_override;
	
	/******* TOP BAR *********************/
	$("#top-bar-nozzle-actual").html(parseInt(data.print.stats.extruder));
	$("#top-bar-nozzle-target").html(parseInt(extruder_target));
	$("#top-bar-bed-actual").html(parseInt(data.print.stats.bed));
	$("#top-bar-bed-target").html(parseInt(bed_target));
	
	if(document.getElementById("top-ext-target-temp") != null){
		document.getElementById('top-ext-target-temp').noUiSlider.set([parseInt(extruder_target)]);
	}
	if(document.getElementById("top-act-ext-temp") != null){
		document.getElementById('top-act-ext-temp').noUiSlider.set([parseInt(extruder_target)]);
	}
	
	
	document.getElementById('top-act-bed-temp').noUiSlider.set([parseInt(data.print.stats.bed)]);
	document.getElementById('top-bed-target-temp').noUiSlider.set([parseInt(bed_target)]);
	
	/***** *******/
	

	
}


function finalize_print(){
	
	_stop_monitor();
	_stop_timer();
	_stop_trace();
	/*_update_task();*/
	$('.controls').addClass('disabled');
	$('.progress').removeClass('active');
	$('.estimated-time').html('-');
	$('.estimated-time-left').html('-');
	/** GO TO NEXT STEP */
	$("#btn-next").trigger('click');
	unfreeze_menu();
	$("#wizard-buttons").hide();
	
	if(z_override != 0){
		$(".z-override-alert").show();
	}
	
	waitContent('');
	openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Finalizing task', '', false);
	setTimeout(function() {
		closeWait();
		IS_TASK_ON = false;
		}, 30000);
	
	
	
}


function _resume() {	
	
	$(".create-monitor").slideDown("slow", function() {});
	monitor_count = 0;
	//faccio partire il monitor 1000 = 1 secondo
	interval_monitor = setInterval(print_monitor, monitor_timeout);
	interval_timer = setInterval(_timer, 1000);
	interval_trace   = setInterval(_trace, 1000);
	
	if(print_started){
		$(".controls-tab").removeClass("disabled");
		$(".controls-tab").find("a").attr("data-toggle", "tab");
		print_started = true;
		enableControlsTab();
	}
	
	
	$('.progress-status').html(number_format(parseFloat(progress_percent), 2, ',', '.') + ' %');
	var speed_percent = (speed/500) * 100;
   	$('.speed-progress').attr('style', 'width:' + parseFloat(speed_percent) + '%');
   	$("#lines-progress").attr('style', 'width:' + parseFloat(progress_percent) + '%');
	
	_trace_call();
	
	if(print_type == 'additive'){
		$(".subtractive-print").hide();
		var layer_percent = (parseInt(layer_actual) / parseInt(layer_total) ) * 100;
		$('.progress-layer').attr('style', 'width:' + parseFloat(layer_percent) + '%');
		$('.layer-percent').html('('+number_format(parseFloat(layer_percent), 2, ',', '.') +'%)');
		
		if ( typeof (Storage) !== "undefined") {	
			if(localStorage.getItem("nozzle_temperatures") !== null){			
				nozzle_temperatures =  JSON.parse(localStorage.getItem("nozzle_temperatures"));
			}
			
			if(localStorage.getItem("nozzle_target_temperatures") !== null){			
				nozzle_target_temperatures =  JSON.parse(localStorage.getItem("nozzle_target_temperatures"));
			}
			
			if(localStorage.getItem("bed_temperatures") !== null){			
				bed_temperatures =  JSON.parse(localStorage.getItem("bed_temperatures"));
			}
			
			if(localStorage.getItem("bed_target_temperatures") !== null){			
				bed_target_temperatures =  JSON.parse(localStorage.getItem("bed_target_temperatures"));
			}
		}
		initGraphs();

	}else{
		$(".speed-well").removeClass("col-sm-4").addClass("col-sm-6");
		$(".stats-well").removeClass("col-sm-4").addClass("col-sm-12");
		$(".additive-print").hide();
	}

	$(".steps >li").removeClass("complete");
	
	if(document.getElementById("top-ext-target-temp") != null){
		document.getElementById("top-ext-target-temp").setAttribute('disabled', true);
	}
	
	document.getElementById("top-bed-target-temp").setAttribute('disabled', true);
	$(".jog").addClass('disabled');
}



function create_socket_response(jsonString){
	
	var obj = jQuery.parseJSON(jsonString);
	

	if(typeof obj.message !== 'undefined'){

		$.smallBox({
			title : "Success",
			content : "<i class='fa fa-check'></i> " + obj.message,
			color : "#659265",
			iconSmall : "fa fa-thumbs-up bounce animated",
			timeout : 8000
		});
	}
	
	
	
}


function addNozzleTemperature(temp){
	
	var obj = {'temp': parseFloat(temp), 'time': new Date().getTime()};
	
	if(nozzle_temperatures.length == max_plot){
		nozzle_temperatures.shift();
	}
	nozzle_temperatures.push(obj);
	
	
	if ( typeof (Storage) !== "undefined") {
		localStorage.setItem('nozzle_temperatures', JSON.stringify(nozzle_temperatures));
	}
	
	
}


function addNozzleTargetTemperature(temp){
	var obj = {'temp': parseFloat(temp), 'time': new Date().getTime()};
	
	if(nozzle_target_temperatures.length == max_plot){
		nozzle_target_temperatures.shift();
	}
	nozzle_target_temperatures.push(obj);
	
	if ( typeof (Storage) !== "undefined") {
		localStorage.setItem('nozzle_target_temperatures', JSON.stringify(nozzle_target_temperatures));
	}
}



function addBedTemperature(temp){
	
	var obj = {'temp': parseFloat(temp), 'time': new Date().getTime()};
	
	if(bed_temperatures.length == max_plot){
		bed_temperatures.shift();
	}
	bed_temperatures.push(obj);
	
	if ( typeof (Storage) !== "undefined") {
		localStorage.setItem('bed_temperatures', JSON.stringify(bed_temperatures));
	}
	
	
}


function addBedTargetTemperature(temp){
	
	var obj = {'temp': parseFloat(temp), 'time': new Date().getTime()};
	
	if(bed_target_temperatures.length == max_plot){
		bed_target_temperatures.shift();
	}
	bed_target_temperatures.push(obj);
	
	if ( typeof (Storage) !== "undefined") {
		localStorage.setItem('bed_target_temperatures', JSON.stringify(bed_target_temperatures));
	}
	
	
}


function getNozzlePlotTemperatures(){
	
	var res1 = [];
	var res2 = [];
	
	for (var i = 0; i < nozzle_temperatures.length; ++i) {
		var obj = nozzle_temperatures[i];
		res1.push([obj.time, obj.temp]);
	}
	
	
	for (var i = 0; i < nozzle_target_temperatures.length; ++i) {
		var obj = nozzle_target_temperatures[i];
		res2.push([obj.time, obj.temp]);
	}
	
	return [{ label: "Actual", data: res1 },
		    { label: "Target", data: res2 }];
	
}




function getBedPlotTemperatures(){
	var res1 = [];
	var res2 = [];
	
	for (var i = 0; i < bed_temperatures.length; ++i) {
		var obj = bed_temperatures[i];
		res1.push([obj.time, obj.temp]);
	}
	
	for (var i = 0; i < bed_target_temperatures.length; ++i) {
		var obj = bed_target_temperatures[i];
		res2.push([obj.time, obj.temp]);
	}

	return [{ label: "Actual", data: res1 },
		    { label: "Target", data: res2 }];
}


function updateNozzleGraph(){
	try{
		if(typeof nozzlePlot == "object" ){
			var data = 	getNozzlePlotTemperatures();
			nozzlePlot.setData(data);
			nozzlePlot.draw();
			nozzlePlot.setupGrid();
		}
		
	}catch(e){
	}
	
}


function updateBedGraph(){
	try{
		if(typeof bedPlot == "object" ){
			var data = getBedPlotTemperatures()	
			bedPlot.setData(data);
			bedPlot.draw();
			bedPlot.setupGrid();
		}
		
	}catch(e){
		
	}
	
}



function  initGraphs(){
	
	nozzlePlot = $.plot("#nozzle-chart", [ getNozzlePlotTemperatures() ], {
        	series : {
				lines : {
					show : true,
					lineWidth : 1,
					fill : true,
					fillColor : {
						colors : [{
							opacity : 0.1
						}, {
							opacity : 0.15
						}]
					}
				}
			},
			xaxis: {
			    mode: "time",
			    show: true,
			    tickFormatter: function (val, axis) {
				    var d = new Date(val);
				    return d.getHours() + ":" + d.getMinutes();
				}
			},
			yaxis: {
		        min: 0,
		        max: 300,    
		        tickFormatter: function (v, axis) {
		            return v + "&deg;C";
		        }
        
    		},
			tooltip : true,
			tooltipOpts : {
				content : "%s: %y &deg;C",
				defaultTheme : false
			},
			colors : ["#FF0000", "#57889c", "#0000FF"],
			legend: {
				show : true
			},
			grid : {
					hoverable : true,
					clickable : true,
					borderWidth : 0
				},

							
			});
	
		bedPlot = $.plot("#bed-chart", [ getBedPlotTemperatures() ], {
        	series : {
				lines : {
					show : true,
					lineWidth : 1,
					fill : true,
					fillColor : {
						colors : [{
							opacity : 0.1
						}, {
							opacity : 0.15
						}]
					}
				},
				shadowSize : 0
			},
			xaxis: {
			    mode: "time",
			    show: true,
			    tickFormatter: function (val, axis) {
				    var d = new Date(val);
				    return d.getHours() + ":" + d.getMinutes();
				}
			},
			yaxis: {
		        min: 0,
		        max: 100,
		        tickSize: 20,        
		        tickFormatter: function (v, axis) {
		            return v + "&deg;C";
		        }
    		},
			tooltip : true,
			tooltipOpts : {
				content : "%s: %y &deg;C",
				defaultTheme : false
			},
			colors : ["#FF0000", "#57889c", "#0000FF"],
			grid : {
					hoverable : true,
					clickable : true,
					borderWidth : 0
			},
							
	});
	
	interval_charts = setInterval(updateCharts, 1000);
}

function updateCharts(){
	updateNozzleGraph();
	updateBedGraph();
}

/*
 * OBJECT
 */
$('.obj').click(function() {

	$(this).find(':first-child').find('input').prop("checked", true);
	$("#objects_table tbody tr").removeClass('success');
	$(this).addClass('success');
	var id = $(this).attr("data-id");
	
	id_object = id;
	
	$.ajax({
		url : ajax_object_endpoint + 'ajax/object.php',
		dataType : 'json',
		type : "POST",
		async : true,
		data : {
			printable : true,
			id_object : id,
			print_type: print_type
		}
	}).done(function(response) {

		object = response;
		detail_object(object);
		detail_files(object);
		startFromRecent = false;
		enable_button('#btn-next');
		resetTableRecent();
		
	});

});


function _stopper() {
	waitContent('Refreshing page');
	document.location.href = '<?php echo site_url("make/".strtolower($label)); ?>';
}


function initSliders() {
	    
	noUiSlider.create(document.getElementById('velocity'), {
		start: <?php echo $_velocity != '' ? $_velocity : 100 ?>,
		connect: "lower",
		range: {'min': 0, 'max' : 500},
		pips: {
			mode: 'positions',
			values: [0,20,40,60,80,100],
			density: 4,
			format: wNumb({})
		}
	});
	
	noUiSlider.create(document.getElementById('fan'), {
		start: 255,
		connect: "lower",
		range: {'min': 50, 'max' : 100},
		pips: {
			mode: 'positions',
			values: [0,50,100],
			density: 4,
			format: wNumb({})
		}
	});
	
	noUiSlider.create(document.getElementById('flow-rate'), { 
		start: <?php echo $flow_rate; ?>,
		connect: "lower",
		range: {'min': 0, 'max' : 500},
		pips: {
			mode: 'positions',
			values: [0,20,40,60,80,100],
			density: 4,
			format: wNumb({})
		}
	});
    
    
    
    noUiSlider.create(document.getElementById('temp1'), {
		start: <?php echo $ext_target != "" ? $ext_target : '0'; ?>,
		connect: "lower",
		range: {'min': 0, 'max' : <?php echo $max_temp > 0 ? $max_temp : 1; ?>},
		pips: {
			mode: 'values',
			values: [0, 175,<?php echo $max_temp ?>],
			density: 4,
			format: wNumb({
				postfix: '&deg;'
			})
		}
	});
    
    
    noUiSlider.create(document.getElementById('act-ext-temp'), {
		start: <?php echo intval($ext_temp) ?>,
		connect: "lower",
		range: {'min': 0, 'max' : <?php echo $max_temp > 0 ? $max_temp : 1; ?>},
		behaviour: 'none'
	});
    
    
    
	$("#act-ext-temp .noUi-handle").remove();
    
    
    noUiSlider.create(document.getElementById('temp2'), {
		start: <?php echo $bed_target == "" ? "0" : $bed_target; ?>,
		connect: "lower",
		range: {'min': 0, 'max' : 100 },
		pips: {
			mode: 'values',
			values: [0,50,100],
			density: 4,
			format: wNumb({
				postfix: '&deg;'
			})
		}
	});
    
    
    noUiSlider.create(document.getElementById('act-bed-temp'), {
		start: <?php echo intval($bed_temp) ?>,
		connect: "lower",
		range: {'min': 0, 'max' : 100},
		behaviour: 'none'
	});
    
	
  	$("#act-bed-temp .noUi-handle").remove();
  	
  	
  	noUiSlider.create(document.getElementById('rpm'), {
		start: <?php echo $_rpm != '' ? $_rpm : 6000 ?>,
		connect: "lower",
		range: {'min': 6000, 'max' : 14000 },
		pips: {
			mode: 'positions',
			values: [0,20,40,60,80,100],
			density: 4,
			format: wNumb({})
		}
	});
  	
    
    speed_slider = document.getElementById('velocity');
    nozzle_slider = document.getElementById('temp1');
    bed_slider    = document.getElementById('temp2');
    fan_slider    = document.getElementById('fan');
    flow_rate_slider = document.getElementById('flow-rate');
    rpm_slider = document.getElementById('rpm');
    
    
    /*event sliders*/
   	nozzle_slider.noUiSlider.on('slide', manageNozzleSlider);
	nozzle_slider.noUiSlider.on('change', setNozzleTemp);
	
	bed_slider.noUiSlider.on('slide', manageBedSlider);
	bed_slider.noUiSlider.on('change', setBedTemp);
	
	speed_slider.noUiSlider.on('slide', manageSpeedSlider);
	speed_slider.noUiSlider.on('change', setSpeed);
	
	fan_slider.noUiSlider.on('slide', manageFanSlider);
	fan_slider.noUiSlider.on('change', setFan);
	
	flow_rate_slider.noUiSlider.on('slide', manageFlowRateSlider);
	flow_rate_slider.noUiSlider.on('change', setFlowRate);
	
	rpm_slider.noUiSlider.on('slide', manageRpmSlider);
	rpm_slider.noUiSlider.on('change', setRpm);
	
}

function manageNozzleSlider(e){
	
	extruder_target = parseInt(e[0]);
	if(extruder_target < SOFT_EXTRUDER_MIN) extruder_target = SOFT_EXTRUDER_MIN;
	
   	$("#label-temp1-target").html('' + extruder_target + '&deg;C');
   	$("#top-bar-nozzle-target").html(extruder_target);
   	blockSliderExt = true;
}

function setNozzleTemp(e){

	if ( parseInt(e[0]) < SOFT_EXTRUDER_MIN ) {
		nozzle_slider.noUiSlider.set(SOFT_EXTRUDER_MIN);
		return;
	}
	_do_action('temp1', parseInt(e[0]));
}


function manageBedSlider(e){
	
	bed_target = parseInt(e[0]);
   	$("#label-temp2-target").html('' + parseInt(e[0]) + '&deg;C');
   	$("#top-bar-bed-target").html(parseInt(e[0]));
   	blockSliderBed = true;
	
}

function setBedTemp(e){
	_do_action('temp2', parseInt(e[0]));
}

function manageSpeedSlider(e){
	
	$(".label-velocity").html('' + parseInt(e[0]) + '%');
   	speed = parseInt(e[0]);
   	var speed_percent = (speed/500) * 100;
   	$('.speed-progress').attr('style', 'width:' + parseFloat(speed_percent) + '%');
}


function setSpeed(e){
	_do_action('velocity', parseInt(e[0]));
}

function manageFanSlider(e){
	$(".label-fan").html('' + parseInt(e[0]) + '%');
   	$('.fan-progress').attr('style', 'width:' + parseInt(e[0]) + '%');
}

function setFan(e){
	_do_action('fan', parseInt(e[0]));
}

function manageFlowRateSlider(e){
	
	$(".label-flow-rate").html('' + parseInt(e[0]) + '%');
  	var flow_percent =  (parseInt(e[0]) / 500) * 100;	
   	$('.flow-rate-progress').attr('style', 'width:' + parseInt(flow_percent) + '%');
}

function setFlowRate(e){
	_do_action('flow-rate', parseInt(e[0]));
}

function manageRpmSlider(e){
	
	var rpm_percent = (parseInt(e[0])/14000) * 100;
   	$(".label-rpm").html('' + parseInt(e[0]) + '');
   	$('.rpm-progress').attr('style', 'width:' + parseFloat(rpm_percent) + '%');
	
}

function setRpm(e){
	_do_action('rpm', parseInt(e[0]));
}

function disableSliders(){
	
	speed_slider.setAttribute('disabled', true);
    nozzle_slider.setAttribute('disabled', true);
	bed_slider.setAttribute('disabled', true);
	fan_slider.setAttribute('disabled', true);
	flow_rate_slider.setAttribute('disabled', true);
	rpm_slider.setAttribute('disabled', true);
	
}

function enableSliders(){
	
	speed_slider.removeAttribute('disabled');
    nozzle_slider.removeAttribute('disabled');
	bed_slider.removeAttribute('disabled');
	fan_slider.removeAttribute('disabled');
	flow_rate_slider.removeAttribute('disabled');
	rpm_slider.removeAttribute('disabled');	
}


function disableControlsTab(){
	
	disableSliders();
	$("#controls").find('button').addClass('disabled');
}

function enableControlsTab(){
	enableSliders();
	$("#controls").find('button').removeClass('disabled');
}


function restart_create(){
	document.location.href = '<?php echo site_url('make/'.strtolower($label)); ?>?obj='+id_object+'&file='+id_file;
}

function new_create(){
	document.location.href = '<?php echo site_url('make/'.strtolower($label)); ?>';
}

function save_z_override(){

	$.ajax({
		type: "POST",
		url : "<?php echo module_url('maintenance').'ajax/override_probe_lenght.php' ?>",
		data : {over : z_override},
		dataType: "json"
	}).done(function( data ) {
		
		$(".z-override-alert").slideUp('slow', function(){
			
			$.smallBox({
				title : "Z Height",
				content : 'New value saved',
				color : "#5384AF",
				timeout : 10000,
				icon : "fa fa-check"
			});
				
		});
	});
}



function select_recent_file(idFile){
	
	$(".model-info").remove();
	var recent_file = recent_files[idFile];
	
	id_file = idFile;
	id_object = recent_file.id_object;
	file_selected = recent_file;
	
	startFromRecent = true;
	
	if(recent_file.attributes != '' && recent_file.attributes != 'Processing'){
		$("#recent_table").after(model_info(recent_file));
	}
	
	
	$.ajax({
		url : '/fabui/create/show/' + print_type,
		cache : false
	}).done(function(html) {
		$("#step4").html(html);
	});

	resetTableObjects();
	enable_button('#btn-next');
	resetTableObjects();
	
	
}




function resetTableObjects(){
	
	$( "#objects_table tbody > tr > td" ).find('input').each(function() {
		$(this).prop('checked', false);
	});
	
	$( "#objects_table tbody > tr " ).each(function() {
		$(this).removeClass('success');
	});
	
	object = undefined;
}


function resetTableRecent(){
	
	$( "#recent_table tbody > tr > td" ).find('input').each(function() {
		$(this).prop('checked', false);
	});
	
	$( "#recent_table tbody > tr " ).each(function() {
		$(this).removeClass('success');
	});
	
	$(".model-info").remove();
	
	startFromRecent = false;
}

function select_file_recent_row(){
	
	$(this).find(':first-child').find('input').prop("checked", true);
	var idFile = $(this).find(':first-child').find('input').val();
	
	$("#recent_table tbody tr").removeClass('success');
	$(this).addClass('success');
	
	select_recent_file(idFile);
	
}


function stopCountDown(){
	
	autostart_timer = 20;
	$(".autostart-timer").html(autostart_timer);
	
	clearInterval(interval_autostart);
}

function startCountDown(){
	interval_autostart   = setInterval(countDown, 1000);
}

function countDown(){
	autostart_timer = autostart_timer - 1;
    $(".autostart-timer").html(autostart_timer);
        	
    if(autostart_timer == 0){
    	$("#modal_link").trigger('click');
    }
}

/**
 * 
 */
function handleTaskStatus(status)
{
	switch(status){
		case 'error':
			handleStoppedTask();
			break;
	}
}
/**
 * 
 */
function handleErrorTask()
{
	$.get('/temp/task_debug', function(data){
		$('#debugModal').modal({
				keyboard: false,
				backdrop: 'static'
		});
		$("#modalDebugContent").html('<pre>' + data + '</pre>');
		$("#debugModal").modal("show");
	});
	
}
</script>