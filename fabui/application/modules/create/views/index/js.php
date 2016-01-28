<script type="text/javascript">
	
	
	IS_TASK_ON = <?php echo  $_running ? 'true' : 'false' ?>;
	
	var id_task = <?php echo $_id_task; ?>;
	var pid     = <?php echo $_pid; ?>;
	var id_file = '';
	var id_object = ''; 
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
	
		
	$(document).ready(function() {
		
		
		$('.progress-bar').progressbar({
			display_text : 'fill'
		});

		
 	  	oTable = $('#objects_table').dataTable({
			
		});
        
        
        
        /*
		* WIZARD
		*/
		var wizard = $('.wizard').wizard({});

		$('#btn-next').on('click', function() {
			$('.wizard').wizard('next');
			check_wizard();
		});

		$('#btn-prev').on('click', function() {
			$('.wizard').wizard('previous');
			check_wizard();
		});

		$('.wizard').on('stepclick', function(e, data) {
			
			$('.wizard').wizard('selectedItem', { step: data.step });
			check_wizard();
		});
        
        
        
        
        $("#turn-off").on('change', function(){
            _controls_listener($(this));
        });
        
        

		<?php if(!$_running):?>
		$(".spinner").spinner();


		$('.carousel.slide').carousel({
			interval : 3000,
			cycle : true
		});
	
		

		/*
		* ACCODION
		*/
		var accordionIcons = {
            header: "fa fa-plus",    
		    activeHeader: "fa fa-minus" 
		};

		$(".accordion").accordion({
			autoHeight : false,
			heightStyle : "content",
			collapsible : true,
			animate : 300,
			icons: accordionIcons,
			header : "h4",
			active: false
		});


		
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
	
	
	if (data.print.completed == 1) {
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
		/*if (print_type == 'additive') {
			$("#velocity-slider-container .well").height($("#ext-slider-container .well").height());
		} else {
			$("#velocity-slider-container .well").height($("#rpm-slider-container .well").height());
		}
		*/

		
	}
	
	if (!blockSliderExt) {
		$("#temp1").val(parseInt(data.print.stats.extruder_target), {
			animate : true
		});
		$("#label-temp1-target").html(parseInt(data.print.stats.extruder_target) + '&deg;C');
		$(".nozzle-target").html(parseInt(data.print.stats.extruder_target));
	}

	if (!blockSliderBed) {
		$("#temp2").val(parseInt(data.print.stats.bed_target), {
			animate : true
		});
		$("#label-temp2-target").html(parseInt(data.print.stats.bed_target) + '&deg;C');
	}
	
	progress = data.print.stats.percent;
	
	
	$(".layer-actual").html(parseInt(data.print.stats.layers.actual));
	$(".layer-total").html(parseInt(data.print.stats.layers.total));
	
	
	
	var layer_percent = (parseInt(data.print.stats.layers.actual) / parseInt(data.print.stats.layers.total) ) * 100;
	
	$('.progress-layer').attr('style', 'width:' + parseFloat(layer_percent) + '%');
	$('.layer-percent').html('('+number_format(parseFloat(layer_percent), 2, ',', '.') +'%)');
	
	
	
	
	$(".layer").html(parseInt(data.print.stats.layers.actual) + ' of ' + parseInt(data.print.stats.layers.total));
	
	
	
	$('.total-lines').html(data.print.lines);
	$('.current-line').html(data.print.stats.line_number);
	$('.pid').html(data.print.pid);
	$('.temperature').html(data.print.stats.extruder);
	$('.position').html(data.print.stats.position);
	
	
	
	
	
	$("#act-ext-temp").val(parseInt(data.print.stats.extruder), {
		animate : true
	});
	
	$("#act-bed-temp").val(parseInt(data.print.stats.bed), {
		animate : true
	});
	
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

	tip(data.print.tip.show, data.print.tip.message);
	
	
	/*** GRAPHS ***/
	addNozzleTemperature(data.print.stats.extruder);
	addNozzleTargetTemperature(data.print.stats.extruder_target);
	addBedTemperature(data.print.stats.bed);
	addBedTargetTemperature(data.print.stats.bed_target);
	
	/*updateNozzleGraph();
	updateBedGraph();*/
		
	
	var fan_percent = (parseFloat(data.print.stats.fan) / 255) * 100;
	
	$("#fan").val(parseInt(fan_percent), {
		animate : true
	});
			
	$(".label-fan").html('' + parseInt(fan_percent) + '%');
	$('.fan-progress').attr('style', 'width:' + parseInt(fan_percent) + '%');
	
	
	var rpm_percent = (parseInt(data.print.stats.rpm)/14000) * 100;
   	$(".label-rpm").html(parseInt(data.print.stats.rpm));
   	$('.rpm-progress').attr('style', 'width:' + parseFloat(rpm_percent) + '%');
   	$("#rpm").val(parseInt(data.print.stats.rpm), {
		animate : true
	});
	
	$(".z_override").html(data.print.stats.z_override);
	z_override = data.print.stats.z_override;
	
	/******* TOP BAR *********************/
	$("#top-bar-nozzle-actual").html(parseInt(data.print.stats.extruder));
	$("#top-bar-nozzle-target").html(parseInt(extruder_target));
	$("#top-bar-bed-actual").html(parseInt(data.print.stats.bed));
	$("#top-bar-bed-target").html(parseInt(bed_target));
	
	
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
	
	openWait('Finalizing task');
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
		console.log(e);
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
		},
		beforeSend : function(xhr) {
		}
	}).done(function(response) {

		object = response;
		detail_object(object);
		detail_files(object);
	});

});


function _stopper() {
	waitContent('Refreshing page');
	document.location.href = '<?php echo site_url("make/".strtolower($label)); ?>';
}


function initSliders() {
	
	
	
	$("#velocity").noUiSlider({
		        range: {'min': 0, 'max' : 500},
                /*range: [0, 500],*/
		        start: <?php echo $_velocity != '' ? $_velocity : 100 ?>,
		        handles: 1,
                connect: 'lower'
    });
    
    
    
    $("#fan").noUiSlider({
	        range: {'min': 50, 'max' : 100},
           	start: 255,
	        handles: 1,
            connect: 'lower'
    });
    
    $("#flow-rate").noUiSlider({
	        range: {'min': 0, 'max' : 500},
            /*range: [0, 500],*/
           	start: <?php echo $flow_rate; ?>,
	        handles: 1,
            connect: 'lower'
    });
    
    $("#temp1").noUiSlider({
	        range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
            /*range: [0, 250],*/
	        start: <?php echo $ext_target != "" ? $ext_target : '0'; ?>,
	        handles: 1,
            connect: 'lower'
    });
    
    
    $("#act-ext-temp").noUiSlider({
 	 	
        range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
        start: <?php echo intval($ext_temp) ?>,
        handles: 0,
        connect: 'lower',
        behaviour: "none"
	});
	
	
	$("#act-ext-temp .noUi-handle").remove();
    
    
    
    $("#temp2").noUiSlider({
	        range: {'min': 0, 'max' : 100 },
            /*range: [0, 100],*/
            start: <?php echo $bed_target == "" ? "0" : $bed_target; ?>,
	        handles: 1,
            connect: 'lower'
    });
    
    
    $("#act-bed-temp").noUiSlider({
 	 	
        range: {'min': 0, 'max' : 100},
        start: <?php echo intval($bed_temp) ?>,
        handles: 0,
        connect: 'lower',
        behaviour: "none"
	});
	
	
  	$("#act-bed-temp .noUi-handle").remove();
    
 	$("#rpm").noUiSlider({
	        range: {'min': 6000, 'max' : 14000 },
            /*range: [0, 100],*/
            start: <?php echo $_rpm != '' ? $_rpm : 6000 ?>,
	        handles: 1,
            connect: 'lower'
    });
    
    
    speed_slider = document.getElementById('velocity');
    nozzle_slider = document.getElementById('temp1');
    bed_slider    = document.getElementById('temp2');
    fan_slider    = document.getElementById('fan');
    flow_rate_slider = document.getElementById('flow-rate');
    rpm_slider = document.getElementById('rpm');
    
    $(".extruder-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,25, 50, 75, 100],
			density: 10,
			format: wNumb({
				prefix: '&deg;'
			})
		});
		
		
		$(".bed-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,25,50,75,100],
			density: 10,
			format: wNumb({
				prefix: '&deg;'
			})
		});
		
		$(".speed-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,20,40,60,80,100],
			density: 10,
			format: wNumb({
			})
		});
		
		$(".rpm-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,20,40,60,80,100],
			density: 10,
			format: wNumb({
			})
		});
		
		$(".fan-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,50,100],
			density: 10,
			format: wNumb({
			})
		});
        
        
        $(".flow-rate-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,20, 40, 60, 80,100],
			density: 10,
			format: wNumb({
			})
		});
    
    
	
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

</script>