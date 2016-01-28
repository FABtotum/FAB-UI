<script type="text/javascript">

	var positions = new Array();
	
	var interval_ticker;
	
	var isMacro=false;
	var showTemperatureConsole = false;
	var maxIdleTime = 60;
	
	var EXT_TARGET_BLOCKED = false;
	var BED_TARGET_BLOCKED = false;
	
	var KEY_ALLOWED = false;
	
	var PRE_JOG = true;

	
	$(function() {
		
		
		initJogUI();
		
		$(document).keyup(function(e) { 
			KEY_ALLOWED = true;
		});
		
		$(document).focus(function(e) { 
			KEY_ALLOWED = true;
		});
		
		
		$(document).keydown(function(event){
			
			
			if (event.repeat != undefined) {
		    	KEY_ALLOWED = !event.repeat;
		  	}
		  	
		  	if (!KEY_ALLOWED) return;
		  	
		  	KEY_ALLOWED = false;
		  	
        	keyboard(event);
    	}); 
		
		/** MOTORS */
		$("#motors").on('change', function(){
	        if ($(this).prop('checked')) {
	            motors("on");    
	        } else {
			    motors("off");         
	        }
	    });
	    
	    $(".motors-off").on('click', function() {
	    	motors('off');
	    })
	    
	    /** COORDINATES */
	    $("#coordinates").on('change', function(){
	        if ($(this).prop('checked')) {
	            coordinates("relative");    
	        } else {
			    coordinates("absolute");         
	        }
	    });  
	    
	    
	    /** LIGHTS */
	    $("#lights").on('change', function(){
	        if ($(this).prop('checked')) {
	            lights("on");    
	        } else {
			    lights("off");         
	        }
	    });
		
		
		
    	
    	
    	/** EVENTS */
    	$( ".axisz" ).on( "click", axisz );
    
		$(".directions").on("click", directions);
	
		$(".zero_all").on("click", zero_all);
	    
	    $("#position").on("click", position);
	    
	    $(".refresh-temperature").on("click", function() {
	    	showTemperatureConsole=true;
	    	refresh_temperature();
	    });
	    
	    $("#bed-align").on("click", bed_align);
	    
	    $('#run').on('click', mdi);
	    
	    
	    $('#save-position').on('click', save_position);
	    
	    $('.saved-position').on('click', saved_position);
	    
	    $("#home-all-axis").on('click', home_all_axis);
	    
	    
	    $("#clear-console").on('click', function(){
	    	$(".console").html('');
	    });
	    
	    
	    $("#clear-mdi").on('click', function(){
	    	$("#mdi").val('');
	    });
	
		$('.directions-container').on('keydown',function(e) {
		      keyboard(e);
		});
	     
	    $('.directions-container').on('keypress',function(e) {
		      keyboard(e);
		});
		
		$('.fan').on('click', fan);
		$('#eeprom').on('click', eeprom);
		
		/** KNOB */
		$('.knob').knob({
	        change: function (value) {
	        },
	        release: function (value) {
				rotation(value);
	        },
	        cancel: function () {
	           
	        }
		 });
		 
		 
		$('.knob').keypress(function(e) {
	        if(e.which == 13) {
	        	rotation($(this).val());
	        }
	 	 });
	 	 
	 	$('#exece-mdi').on( "click", mdi );
	 	 
	 	/** EXTRUDER TEMPERATURE */
	 	$("#ext-target-temp").noUiSlider({
	 	 	
	        range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
	        start: typeof (Storage) !== "undefined" ? localStorage.getItem("nozzle_temp_target") : 0,
	        handles: 1,
            connect: 'lower'
		});
		
		
		$("#act-ext-temp").noUiSlider({
	 	 	
	        range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
	        start: typeof (Storage) !== "undefined" ? localStorage.getItem("nozzle_temp") : 0,
	        handles: 0,
            connect: 'lower',
            behaviour: "none"
		});
		
		
		$("#act-ext-temp .noUi-handle").remove();
		 
		$("#ext-target-temp").on({
			slide: extTempSlide,
        	change: extTempChange
	 	});
	 	
	 	
	 	$(".extruder-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,25, 50, 75, 100],
			density: 5,
			format: wNumb({
				prefix: '&deg;'
			})
		});
	 	
		
	 	
	 	/** BED TEMPERATURE */
	 	$("#bed-target-temp").noUiSlider({
	        range: {'min': 0, 'max' : 100},
	       	start: typeof (Storage) !== "undefined" ? localStorage.getItem("bed_temp_target") : 0,
	        handles: 1,
	        connect: 'lower'
      	});
      	
      	$("#act-bed-temp").noUiSlider({
	 	 	
	        range: {'min': 0, 'max' : 100},
	        start: typeof (Storage) !== "undefined" ? localStorage.getItem("bed_temp") : 0,
	        handles: 0,
            connect: 'lower',
            behaviour: "none"
		});
      	
      	$("#act-bed-temp .noUi-handle").remove();
      	
      	
      	$("#bed-target-temp").on({
			slide: bedTempSlide,
        	change: bedTempChange
	 	});
	 	
	 	$(".bed-range").noUiSlider_pips({
			mode: 'positions',
			values: [0,25,50,75,100],
			density: 5,
			format: wNumb({
				prefix: '&deg;'
			})
		});
	 	
	 	 
	 	
	 	
	 	$(".extruder-mode").on('click', function() {
        
        	$('.extruder-mode').removeClass('active');
	    	$('.mode-container').hide();
	    	$(this).addClass('active');
	    	var mode = $(this).attr("data-mode");
	    	$("#mode-" + mode ).show();
	    	extruder_mode(mode); 
	    	
	    	var mode_label = mode == "a" ? '4th axis' : 'Extruder';
	    	
	    	$(".mode").html(mode_label);
	    	  
	        
	   	});
	   	
	   	
	   	$("#mdi").keypress(function(e) {
            if(e.which == 13) {
                if(!e.shiftKey){
                  mdi(); 
                }
            }
    	});
    	
    	var max_mdi_rows = 10;
    	
    	$('#mdi').keydown(function(e) {

	        newLines = $(this).val().split("\n").length;
	        if(e.keyCode == 13 && newLines >= max_mdi_rows) {
	            return false;
	        }
	        else {
	            
	        }
	    });
	   	
	   	
	   	$(".extruder-e-action").on('click', function(){
        	extruder_e_action($(this).attr("data-action"));
     	});
	   	

		pre_jog();
	
		/** TICKER */
    	interval_ticker   = setInterval(ticker, 500);
    	    	
    	/** RESET CONTROLLER */
    	$("#reset-controller").on('click', ask_reset);
		
		
		$("#z-step").spinner({
				step : 0.01,
				numberFormat : "n",
				min: 0
		});
		
		
		$("#step").spinner({
				step :0.5,
				numberFormat : "n",
				min: 0
		});
		
		$("#feedrate").spinner({
				step :50,
				numberFormat : "n",
				min: 0
		});
		
		
		$("#extruder-e-value").spinner({
			step:1,
			numberFormat : "n",
			min: 1
		});
		
		$("#extruder-feedrate").spinner({
			step:50,
			numberFormat : "n",
			min: 1
		});
		
		
		$('.progress-bar').progressbar({
			display_text : 'fill'
		});
		

	});
	

	
	/** FUNCTIONS  */
	
	function eeprom(){
		var func = 'eeprom'
		
		if(SOCKET_CONNECTED){
	    	jog_make_call_ws(func, '');
	    }else{
	    	make_call(func, '');
	    }
	}
	
	
	function fan(){
		var func = 'fan';
		var value = $(this).attr('data-action');
		
		if(SOCKET_CONNECTED){
	    	jog_make_call_ws(func, value);
	    }else{
	    	make_call(func, value);
	    }
		
		
	}
	
	function axisz(){
    
	    var func = $(this).attr("data-attribute-function");
	    var step = $(this).attr("data-attribute-step");
	    
	    if(SOCKET_CONNECTED){
	    	jog_make_call_ws(func, step);
	    }else{
	    	make_call(func, step);
	    }
	    
	    
	    
	}
	
	function directions(){
		var value = $(this).attr("data-attribue-direction");
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("directions", value);
		}else{
			make_call("directions", value);
		}
	}
	
	function zero_all(){
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("zero_all", true);
		}else{
			make_call("zero_all", true);
		}
		
	}
	
	function bed_align(e){
	    make_call("bed-align", true, true);
	}
	
	function saved_position(){
	    
	    var gcode = jQuery.trim($(this).attr("data-code"));
	    make_call('mdi', gcode);
	    
	}
	
	function home_all_axis(){
	    make_call("home_all_axis", true, true);
	}
	
	function extTempSlide(e){
		
		EXT_TARGET_BLOCKED = true;
    	var slide_val = parseInt($(this).val());
    	$("#ext-degrees").html(slide_val + '&deg;C');
    
	}
	
	function extTempChange(e){
		
		EXT_TARGET_BLOCKED = false;
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("ext_temp", parseInt($(this).val()));
		}else{
			make_call("ext_temp", parseInt($(this).val()));
		}
		
   		
	}
	
	function bedTempSlide(e){
		BED_TARGET_BLOCKED = true;
    	var slide_val = parseInt($(this).val());
    	$("#bed-degrees").html(slide_val + '&deg;C');
	}
	
	function bedTempChange(e){
		BED_TARGET_BLOCKED = false;
		if(SOCKET_CONNECTED){
			jog_make_call_ws("bed_temp", parseInt($(this).val()));
		}else{
			make_call("bed_temp", parseInt($(this).val()));
		}
		
		
    	
	}
	
	function mdi(){
		
		var gcode = jQuery.trim($("#mdi").val());
		jQuery("#mdi").val(gcode.replace('<br>', ''));
	    if(gcode != ''){
	    	
	    	if(SOCKET_CONNECTED){
				 jog_make_call_ws('mdi', gcode);
			}else{
				 make_call('mdi', gcode);
			}
	    }	
	}
	
	function extruder_mode(mode){
	    
	    if(SOCKET_CONNECTED){
			 jog_make_call_ws("extruder_mode", mode);
		}else{
			 make_call("extruder_mode", mode);
		}
	    
	}
	
	function motors(value){
		
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("motors", value);
		}else{
			make_call("motors", value);
		}
	
	}
	
	
	function coordinates(value){
		
		make_call("coordinates", value);
	    enable_save_position();
	}
	
	
	function lights(value){

		if(SOCKET_CONNECTED){
			jog_make_call_ws("lights", value);
		}else{
			make_call("lights", value);
		}

	}
	
	function position(){
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("position", true);
		}else{
			make_call("position", true);
		}
		
    	
	}
	
	function rotation(value){

		
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("rotation", value);
		}else{
			make_call("rotation", value);
		}
			
	}
	
	function extruder_e_action(action){
		
		
		if(SOCKET_CONNECTED){
			jog_make_call_ws("extruder_e", action + $("#extruder-e-value").val());
		}else{
			make_call("extruder_e", action + $("#extruder-e-value").val());
		}
		
    	
	}
	
	
	
	function refresh_temperature(){
		
		
		if(SOCKET_CONNECTED){
			
			jog_make_call_ws("get_temperature", "");
			
		}else{
    
		    $.ajax({ 
		    	url : '<?php echo module_url('jog').'ajax/temperature.php' ?>',
				dataType : 'json',
				type: 'post',
					  async : true,
			}).done(function(response) {
				  
		            if(response.ext != "" && response.ext != null){
		                
		                $("#ext-actual-degrees").html(parseInt(response.ext) + '&deg;C');
		                $("#ext-degrees").html(parseInt(response.ext_target) + '&deg;C');
		                
		                $("#ext-target-temp").val( parseInt(response.ext_target), {
		                	set: true,
		                	animate: true
		                });
		                	                
		                $("#act-ext-temp").val( parseInt(response.ext), {
		                	set: true,
		                	animate: true
		                });
	
		            }
		            
		            if(response.bed != "" && response.bed != null){
		                $("#bed-actual-degrees").html(parseInt(response.bed) + '&deg;C');
		                $("#bed-degrees").html(parseInt(response.bed_target) + '&deg;C');
	
		                $("#bed-target-temp").val( parseInt(response.bed_target), {
		                	set: true,
		                	animate: true
		                });
		                
		                $("#act-bed-temp").val( parseInt(response.bed), {
		                	set: true,
		                	animate: true
		                });
	
		            }
		            
		            
		            write_to_console('Temperatures (M105) [Ext: ' + parseInt(response.ext) + ' / ' + parseInt(response.ext_target)   + ' ---  Bed: ' + parseInt(response.bed) + ' / ' + parseInt(response.bed_target) +  ']\n');
		          
		        });
	        
	    }
	}
	
	
	function save_position(e){

	    $.ajax({
	        url : '<?php echo module_url('jog').'ajax/position.php' ?>',
	        dataType : 'json',
	        type: 'post',
	        async : true,
	   	}).done(function(response) {
	        
	        var coords = {'x' : response.x, 'y' : response.y, 'z': response.z};
	        
	        if(positions.length < 5){
	            positions[positions.length] = coords;
	        }else{
	            positions.shift();
	            positions.push(coords);
	        }
	        e.preventDefault();
	        set_positions();
	        
	        
	    });    
	}
	
	
	function ticker(){
		
		if(!SOCKET_CONNECTED){
		    if(jog_ticket_url != ''){
				getTrace(jog_ticket_url, 'GET', $(".console"));
		    }
	   }
	}
	
	
	function make_call(func, value, macro){
    
    
    	macro = macro || false;
	    var timestamp = new Date().getTime();
	    
	    
	    if(macro){
	    	jog_ticket_url = '/temp/macro_trace';
	    	isMacro=true;
	    	IS_MACRO_ON = true;
	    }
	            
	    $(".btn").addClass('disabled');
	    $("#reset-controller").removeClass('disabled');
	    
	    $(".status").html(' <i class="fa fa-spin fa-spinner fa-2x"></i>');
	
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('jog').'ajax/exec.php' ?>",
			data : {function: func, value: value, time: timestamp, step:$("#step").val(), z_step:$("#z-step").val(), feedrate: $("#feedrate").val(), macro:macro, extruderFeedrate: $("#extruder-feedrate").val()},
			dataType: "json"
		}).done(function( data ) {
			
			if(!macro){
				var separator = '-----------\n';
	        	write_to_console(separator + data.data.command + ': ' + data.data.response);
	       	}
	        
	        isMacro=false;
	        IS_MACRO_ON = false;
	        $(".btn").removeClass('disabled');
	        enable_save_position();
	        jog_ticket_url = '';
	        $(".status").html(' ');
		});
		
	}
	
	
	function enable_save_position(){
    
	    var type = $("#coordinates").prop('checked') ? 'relative' : 'absolute';
	    
	    if(type == 'relative'){
	        $('.saved-position').addClass('disabled');
	        $("#save-position").addClass('disabled'); 
	    }else{
	        $('.saved-position').removeClass('disabled');
	        $("#save-position").removeClass('disabled');
	    }
	    
	}
	
	
	function pre_jog(){
	    
	    
	   	IS_MACRO_ON = true;
	    $(".btn").addClass('disabled');
	        
	    jog_ticket_url = 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/temp/macro_trace';
	   
	    
	    $.ajax({
	    	url : '<?php echo module_url('jog').'ajax/pre_jog.php' ?>',
			dataType : 'json',
			type: 'post'
		}).done(function(response) {
	    	jog_ticket_url = '';
	        refresh_temperature();
	        $(".btn").removeClass('disabled');
	        
	        PRE_JOG = false;
	        IS_MACRO_ON = false;
	   });
	    
	}
		
	
	function ask_reset(){
		
		
		$.SmartMessageBox({
			title: "<i class='fa fa-warning'></i> <span class='txt-color-orangeDark'><strong>Reset Controller</strong></span> ",
			content: "This operation will reset your control board, continue?",
			buttons: '[No][Yes]'
			}, function(ButtonPressed) {
			   
				if (ButtonPressed === "Yes") {
				  	reset_controller();
					
				}
				if (ButtonPressed === "No") {
					
					return false;
				}
		
		});
		
		
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
		
		$(".btn").removeClass('disabled');
	}
	
	
	function keyboard(event){
		
		var $focused = $(':focus');
		
		
		if($focused.attr('id') == 'mdi' || $focused.is(':input')) return false;
		
		var keycode = (event.keyCode ? event.keyCode : event.which);
		
		var function_name = '';
		var function_value = '';
		
		switch(keycode){
			case 37:
				function_name = 'directions';
				function_value = 'left';
				break;
			case 38:
				function_name = 'directions';
				function_value = 'up';
				break;
			case 39:
				function_name = 'directions';
				function_value = 'right';
				break;
			case 40:
				function_name = 'directions';
				function_value = 'down';
				break;
			case 33:
				function_name = 'zdown';
				function_value = '';
				break;
			case 34:
				function_name = 'zup';
				function_value = '';
		}
		
		
		if(function_name != ''){
			
			if(SOCKET_CONNECTED){
				jog_make_call_ws(function_name, function_value);
			}else{
				make_call(function_name, function_value);
			}
		}
		
	}
	
	
	function initJogUI(){
		
		if ( typeof (Storage) !== "undefined") {
		
			/******* TEMP SLIDERS *********************/
			$("#ext-actual-degrees").html(parseInt(localStorage.getItem("nozzle_temp")) + '&deg;C');
			$("#ext-degrees").html(parseInt(localStorage.getItem("nozzle_temp_target")) + '&deg;C');
			$("#bed-actual-degrees").html(parseInt(localStorage.getItem("bed_temp"))+ '&deg;C');
			$("#bed-degrees").html(parseInt(localStorage.getItem("bed_temp_target"))+ '&deg;C');
		
		}
	}
	
</script>