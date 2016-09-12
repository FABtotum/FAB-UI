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
		
		pre_jog();
		
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
		
		if($("#ext-target-temp").length > 0){
			
		
			noUiSlider.create(document.getElementById('ext-target-temp'), {
				start: typeof (Storage) !== "undefined" ? localStorage.getItem("nozzle_temp_target") : 0,
				connect: "lower",
				range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
				pips: {
					mode: 'positions',
					values: [0,25,50,75,100],
					density: 5,
					format: wNumb({
						postfix: '&deg;'
					})
				}
			});
			
			noUiSlider.create(document.getElementById('act-ext-temp'), {
				start: typeof (Storage) !== "undefined" ? localStorage.getItem("nozzle_temp") : 0,
				connect: "lower",
				range: {'min': 0, 'max' : <?php echo $max_temp; ?>},
				behaviour: 'none'
			});
			
			$("#act-ext-temp .noUi-handle").remove();
		
		}
		 
		$("#ext-target-temp").on({
			slide: extTempSlide,
        	change: extTempChange
	 	});
	 	
	 	
	 	/** BED TEMPERATURE */
      	noUiSlider.create(document.getElementById('bed-target-temp'), {
			start: typeof (Storage) !== "undefined" ? localStorage.getItem("bed_temp_target") : 0,
			connect: "lower",
			range: {'min': 0, 'max' : 100},
			pips: {
				
				mode: 'positions',
				values: [0,25,50,75,100],
				density: 5,
				format: wNumb({
					postfix: '&deg;'
				})
			}
		});
      	
		noUiSlider.create(document.getElementById('act-bed-temp'), {
			start: typeof (Storage) !== "undefined" ? localStorage.getItem("bed_temp") : 0,
			connect: "lower",
			range: {'min': 0, 'max' : 100},
			behaviour: 'none'
		});
      	
      	$("#act-bed-temp .noUi-handle").remove();
      	
      	
      	$("#bed-target-temp").on({
			slide: bedTempSlide,
        	change: bedTempChange
	 	});
	 	
	 	if($("#ext-target-temp").length > 0){
		 	//SLIDER EVENTS - EXTRUDER
		 	document.getElementById("ext-target-temp").noUiSlider.on('slide', extTempSlide);
			document.getElementById("ext-target-temp").noUiSlider.on('change', extTempChange);
			document.getElementById("ext-target-temp").noUiSlider.on('start', blockSliders);
			document.getElementById("ext-target-temp").noUiSlider.on('end', enableSliders);
		}
		
		//SLIDER EVENTS - BED
		document.getElementById("bed-target-temp").noUiSlider.on('slide', bedTempSlide);
		document.getElementById("bed-target-temp").noUiSlider.on('change', bedTempChange);
		document.getElementById("bed-target-temp").noUiSlider.on('start', blockSliders);
		document.getElementById("bed-target-temp").noUiSlider.on('end', enableSliders);
	 	
	 	
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
	   	

		
	
		/** TICKER */
    	interval_ticker   = setInterval(ticker, 500);
    	    	
    	
		
		
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
		
		
		
		/*disable sliders untili pre-jog finish*/
		if($("#ext-target-temp").length > 0){
			//document.getElementById("ext-target-temp").setAttribute('disabled', true);
		}
		
		//document.getElementById("bed-target-temp").setAttribute('disabled', true);
		
		

	});
	

	
	/** FUNCTIONS  */
	
	function eeprom(){
		var func = 'eeprom'
		
		
		jog_call(func, '');
		
		
	}
	
	
	function fan(){
		var func = 'fan';
		var value = $(this).attr('data-action');
		
		jog_call(func, value);
		
		
	}
	
	
	function bed_align(e){
	    make_jog_call("bed-align", true, true);
	}
	
	function saved_position(){
	    
	    var gcode = jQuery.trim($(this).attr("data-code"));
	    make_jog_call('mdi', gcode);
	    
	}
	
	function home_all_axis(){
	    make_jog_call("home_all_axis", true, true);
	}
	
	function extTempSlide(e){
		
		EXT_TARGET_BLOCKED = true;
    	var slide_val = parseInt(e[0]);
    	
    	
    	$("#ext-degrees").html(slide_val + '&deg;C');
    	$("#top-bar-nozzle-target").html(slide_val);
    
	}
	
	function extTempChange(e){
		
		EXT_TARGET_BLOCKED = false;
		
		jog_call("ext_temp", parseInt(e[0]));
		
		document.getElementById('top-ext-target-temp').noUiSlider.set([parseInt(e[0])]);
   		
	}
	
	function bedTempSlide(e){
		BED_TARGET_BLOCKED = true;
    	var slide_val = parseInt(e[0]);
    	$("#bed-degrees").html(slide_val + '&deg;C');
    	$("#top-bar-bed-target").html(slide_val);
	}
	
	function bedTempChange(e){
		BED_TARGET_BLOCKED = false;
		
		jog_call("bed_temp", parseInt(e[0]));
		
		document.getElementById('top-bed-target-temp').noUiSlider.set([parseInt(e[0])]);	
    	
	}
	
	function mdi(){
		
		var gcode = jQuery.trim($("#mdi").val());
		jQuery("#mdi").val(gcode.replace('<br>', ''));
	    if(gcode != ''){
	    	jog_call('mdi', gcode);
	    }	
	}
	
	function extruder_mode(mode){
	    
	    
	    jog_call("extruder_mode", mode);
	    
	}
	
	function motors(value){
		
		jog_call("motors", value);
	}
	
	
	function coordinates(value){
		
		jog_call("coordinates", value);
	    enable_save_position();
	}
	
	
	function lights(value){
		
		jog_call("lights", value);

	}
	
	function position(){
		
		jog_call("position", true);	
		
    	
	}
	
	function rotation(value){
		
		jog_call("rotation", value);	
			
	}
	
	function extruder_e_action(action){
		
		jog_call("extruder_e", action + $("#extruder-e-value").val());
		
	}
	
	
	
	function refresh_temperature(){
		
		
		if(SOCKET_CONNECTED){
			
			jog_call("get_temperature", "");
			
			
			
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
		          	showTemperatureConsole = false;
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
	
	
	function make_jog_call(func, value, macro){
    
    	macro = macro || false;
	    var timestamp = new Date().getTime();
	    
	    if(macro){
	    	jog_ticket_url = '/temp/macro_trace';
	    	isMacro=true;
	    	IS_MACRO_ON = true;
	    }
	            
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
	    
	 	
	 	jog_call('extruder_mode', 'e');
	 	jog_call('mdi', 'M106 S255');

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
			
			jog_call(function_name, function_value);
			
			
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