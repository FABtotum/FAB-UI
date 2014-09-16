<script type="text/javascript">

	var positions = new Array();
	var ticker_url = '';
	var interval_ticker;
	
	
	$(function() {
		
		
		/** MOTORS */
		$("#motors").on('change', function(){
	        if ($(this).prop('checked')) {
	            motors("on");    
	        } else {
			    motors("off");         
	        }
	    });
	    
	    
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
		
		
		/** INIT  */
		<?php echo $_motors == "on" ? ' $("#motors").prop("checked", true);' : ''; ?>
    
    	<?php echo $_coordinates == "relative" ? ' $("#coordinates").prop("checked", true);' : ''; ?>
    
    	<?php echo $_lights == "on" ? ' $("#lights").prop("checked", true);' : ''; ?>
    	
    	
    	/** EVENTS */
    	$( ".axisz" ).on( "click", axisz );
    
		$(".directions").on("click", directions);
	
		$("#zero-all").on("click", zero_all);
	    
	    $("#position").on("click", position);
	    
	    $("#get-temp-ext").on("click", refresh_temperature);
	    
	    $("#bed-align").on("click", bed_align);
	    
	    $('#run').on('click', mdi);
	    
	    
	    $('#save-position').on('click', save_position);
	    
	    $('.saved-position').on('click', saved_position);
	    
	    $("#home-all-axis").on('click', home_all_axis);
	    
	    
	    $("#clear-console").on('click', function(){
	    	$("#console").html('');
	    });
	    
	
		$('.directions-container').on('keydown',function(e) {
		      keyboard(e);
		});
	     
	    $('.directions-container').on('keypress',function(e) {
		      keyboard(e);
		});
		
		/** KNOB */
		$('.knob').knob({
	        change: function (value) {
	        },
	        release: function (value) {
				rotation(value);
	        },
	        cancel: function () {
	            console.log("cancel : ", this);
	        }
		 });
		 
		 
		$('.knob').keypress(function(e) {
	        if(e.which == 13) {
	        	rotation($(this).val());
	        }
	 	 });
	 	 
	 	$('#exece-mdi').on( "click", mdi );
	 	 
	 	/** EXTRUDER TEMPERATURE */
	 	$("#ext-temp").noUiSlider({
	 	 	
	        range: {'min': 0, 'max' : 230},
	        start: <?php echo $_ext_temp ?>,
	        handles: 1,
            connect: 'lower'
		});
		 
		$("#ext-temp").on({
			slide: extTempSlide,
        	change: extTempChange
	 	});
	 	
	 	
	 	/** BED TEMPERATURE */
	 	$("#bed-temp").noUiSlider({
	        range: {'min': 0, 'max' : 100},
	        start: <?php echo $_bed_temp ?>,
	        handles: 1,
	        connect: 'lower'
      	});
      	
      	$("#bed-temp").on({
			slide: bedTempSlide,
        	change: bedTempChange
	 	});
	 	 
	 	
	 	
	 	$(".extruder-mode").on('click', function() {
        
        	$('.extruder-mode').removeClass('active');
	    	$('.mode-container').hide();
	    	$(this).addClass('active');
	    	var mode = $(this).attr("data-mode");
	    	$("#mode-" + mode ).show();
	    	extruder_mode(mode);   
	        
	   	});
	   	
	   	
	   	$("#mdi").keypress(function(e) {
            if(e.which == 13) {
                if(!e.shiftKey){
                  mdi(); 
                }
            }
    	});
	   	
	   	
	   	$(".extruder-e-action").on('click', function(){
        	extruder_e_action($(this).attr("data-action"));
     	});
	   	

		/** TICKER */
    	interval_ticker   = setInterval(ticker, 1000);
    	
    	pre_jog();
		
		
		
		
	});
	
	
	/** FUNCTIONS  */
	
	function axisz(){
    
	    var func = $(this).attr("data-attribute-function");
	    var step = $(this).attr("data-attribute-step");
	    make_call(func, step);
	    
	}
	
	function directions(){
		var value = $(this).attr("data-attribue-direction");
		make_call("directions", value);	
	}
	
	function zero_all(){
		make_call("zero_all", true);
	}
	
	function bed_align(e){
	    make_call("bed-align", true);
	}
	
	function saved_position(){
	    
	    var gcode = jQuery.trim($(this).attr("data-code"));
	    make_call('mdi', gcode);
	    
	}
	
	function home_all_axis(){
	    make_call("home_all_axis", true);
	}
	
	function extTempSlide(e){
    	var slide_val = parseInt($(this).val());
    	$("#ext-degrees").html(slide_val);
    
	}
	
	function extTempChange(e){
   		make_call("ext-temp", parseInt($(this).val()));
	}
	
	function bedTempSlide(e){
    	var slide_val = parseInt($(this).val());
    	$("#bed-degrees").html(slide_val);
	}
	
	function bedTempChange(e){
    	make_call("bed-temp", parseInt($(this).val()));
	}
	
	function mdi(){
		
		var gcode = jQuery.trim($("#mdi").val());
		jQuery("#mdi").val(gcode.replace('<br>', ''));
	    if(gcode != ''){
	        make_call('mdi', gcode);
	    }	
	}
	
	function extruder_mode(mode){
	    make_call("extruder_mode", mode);
	}
	
	function motors(value){
	
		make_call("motors", value);
		save_value("motors", value);	
	}
	
	
	function coordinates(value){
		
		make_call("coordinates", value);
		save_value("coordinates", value);
	    enable_save_position();
	}
	
	
	function lights(value){

		make_call("lights", value);
		save_value("lights", value);
	}
	
	function position(){
    	make_call("position", true);
	}
	
	function rotation(value){

		make_call("rotation", value);	
	}
	
	function extruder_e_action(action){
    	make_call("extruder-e", action + $("#extruder-e-value").val());
	}
	
	
	
	function refresh_temperature(){
    
	    $.ajax({
	              url : '<?php echo module_url('jog').'ajax/temperature.php' ?>',
				  dataType : 'json',
				  type: 'post',
				  async : true,
			}).done(function(response) {
			  
	            if(response.ext != "" && response.ext != null){
	                
	                $("#ext-actual-degrees").html(response.ext);
	                $("#ext-degrees").html(response.ext_target);
	                
	                $("#ext-temp").val( parseInt(response.ext), {
	                	set: true,
	                	animate: true
	                });
	                
	            }
	            
	            if(response.bed != "" && response.bed != null){
	                $("#bed-actual-degrees").html(response.bed);
	                $("#bed-degrees").html(response.bed_target);
	                
	                $("#bed-temp").val( parseInt(response.bed), {
	                	set: true,
	                	animate: true
	                });
	            }
	            
	            write_to_console('M105', '[Ext: ' + response.ext + ' / ' + response.ext_target   + ' ---  Bed: ' + parseInt(response.bed) + ' / ' + response.bed_target +  ']\n');
	          
	        });
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
    
	    if(ticker_url != ''){
	        
	         $.get( ticker_url , function( data ) {
	            
	            if(data != ''){
	               $("#console").html(data);
	               $('#console').scrollTop(1E10);
	              
	            }
	       }).fail(function(){ 
	           
	        });
	    }
	}
	
	
	function make_call(func, value){
    
	    var timestamp = new Date().getTime();
	    
	    
	    if(func == 'home_all_axis' || func == 'bed-align'){
	    	$("#console").html('');
	         ticker_url = '/temp/jog_' + timestamp + '.trace'; 
	    }
	            
	  
	    $(".btn").addClass('disabled');
	
		$.ajax({
			type: "POST",
			url : "<?php echo module_url('jog').'ajax/exec.php' ?>",
			data : {function: func, value: value, time: timestamp},
			dataType: "json"
		}).done(function( data ) {
	        write_to_console(data.command, data.response);
	        $(".btn").removeClass('disabled');
	        enable_save_position();
	        ticker_url = '';
		});
		
	}
	
	
	function write_to_console(command, response){
	
	
	   	var text = '';
	   	
	   	if(command != ''){
	   		text += command + ' : ';
	   	}
	   	
	   	if(response != ''){
	   		text += response;
	   	}
	   
	   	$("#console").append(text);
	   	$('#console').scrollTop(1E10);
	   	
	   	
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
	    
	    
	    var timestamp = new Date().getTime();        
	    ticker_url = '/temp/pre_jog_' + timestamp + '.trace';
	    
	    $.ajax({
	              url : '<?php echo module_url('jog').'ajax/pre_jog.php' ?>',
				  dataType : 'json',
				  type: 'post',
				  async : true,
	              data: {time : timestamp}
			}).done(function(response) {
			  
	             ticker_url = '';
	             refresh_temperature();
	        });
	    
	}
	
	
	function save_value(key, value){
		
	}

	
	
	
</script>