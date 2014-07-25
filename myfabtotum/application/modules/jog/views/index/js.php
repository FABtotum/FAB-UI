<script type="text/javascript">

var counter = 0;
var interval_temperature;
var mdi_editor;
var console_editor;
var positions = new Array();
var ticker_url = '';
var interval_ticker;

$(document).ready(function() {

	$('.slider').slider();	
			
	/*$( "input[name='motors']" ).on( "click", motors );*/
    
    
    $("#motors").on('change', function(){
        if ($(this).prop('checked')) {
            motors("on");    
        } else {
		    motors("off");         
        }
    });
    
    
    
    $("#coordinates").on('change', function(){
        if ($(this).prop('checked')) {
            coordinates("relative");    
        } else {
		    coordinates("absolute");         
        }
    });
    
    $("#lights").on('change', function(){
        if ($(this).prop('checked')) {
            lights("on");    
        } else {
		    lights("off");         
        }
    });

    
    <?php echo $_motors == "on" ? ' $("#motors").prop("checked", true);' : ''; ?>
    
    <?php echo $_coordinates == "relative" ? ' $("#coordinates").prop("checked", true);' : ''; ?>
    
    <?php echo $_lights == "on" ? ' $("#lights").prop("checked", true);' : ''; ?>
    
    
    /*$( "input[name='lights']" ).on( "click", lights );
	
	$( "input[name='coordinates']" ).on( "click", coordinates );
    */
    
    $( ".axisz" ).on( "click", axisz );
    
	$(".directions").on("click", directions);

	$("#zero-all").on("click", zero_all);
    
    $("#position").on("click", position);
    
    $("#get-temp-ext").on("click", get_temp);
    
    $("#bed-align").on("click", bed_align);
    
    $('#run').on('click', mdi);
    
    
    $('#save-position').on('click', save_position);
    
    $('.saved-position').on('click', saved_position);
    
    
    $("#home-all-axis").on('click', home_all_axis);
    
    

	$('.directions-container').on('keydown',function(e) {
	      keyboard(e);
	 });
     
     $('.directions-container').on('keypress',function(e) {
	      keyboard(e);
	 });

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

	  $("#save-conf").on("click", save_configuration);
      

      $("#ext-temp").noUiSlider({
		        range: {'min': 0, 'max' : 250},
		        start: <?php echo $_ext_temp ?>,
		        handles: 1,
                connect: 'lower'
		    });
            
     $("#ext-temp").on({
		 slide: extTempSlide,
         change: extTempChange
	 });
            
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
        
        console.log($("#mode-" + mode ));
        
        
     });
     
     
     $(".extruder-e-action").on('click', function(){
        extruder_e_action($(this).attr("data-action"));
     });
     
     
     
    /*check_temperature();*/
    
    
    mdi_editor = ace.edit("mdi");
    mdi_editor.setTheme("ace/theme/chrome");
    mdi_editor.getSession().setMode("ace/mode/text");
    mdi_editor.renderer.setShowPrintMargin(false);
     
    console_editor = ace.edit("console");
    /*console_editor.setTheme("ace/theme/kr_theme");*/
    console_editor.getSession().setMode("ace/mode/text");
    console_editor.renderer.setShowPrintMargin(false);
    console_editor.setReadOnly(true);
    
    enable_save_position();
    
    
    /** TICKER */
    interval_ticker   = setInterval(ticker, 2000);
    
    				
});



function ticker(){
    
    if(ticker_url != ''){
        
         $.get( ticker_url , function( data ) {
            
            if(data != ''){
                
                var string_trace = data.replace("<br>", "\n");
                
                string_trace = string_trace.replace(new RegExp('<br>', 'g'), '\n');
                
                
                console_editor.setValue(string_trace);
                console_editor.navigateLineEnd();
            }
       }).fail(function(){ 
            console.log(ticker_url + "doesn't exists ");
        });
    }
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



function saved_position(){
    
    /*$('input:radio[name=coordinates]').prop('checked', true);*/
    
    var gcode = jQuery.trim($(this).attr("data-code"));
    
    make_call('mdi', gcode);
    
}

function set_positions(){
    
    for(var i=0; i < positions.length; i++){
        
        var coord = positions[i];
        
        var x = coord.x.toString().charAt(0) == '-' ? coord.x : '+' +coord.x;
        var y = coord.y.toString().charAt(0) == '-' ? coord.y : '+' +coord.y;
        var z = coord.z.toString().charAt(0) == '-' ? coord.z : '+' +coord.z;

        var gcode = 'G0 X' + x + ' Y'+ y + ' Z' + z;
        $("#pos-" + (i+1)).attr('data-code', gcode);
        $("#pos-" + (i+1)).show();
        
    }
}


function check_temperature(){
    
    interval_temperature     = setInterval(refresh_temperature, 1000); 
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
            }
            
            if(response.bed != "" && response.bed != null){
                $("#bed-actual-degrees").html(response.bed);
            }
          
        });
}


function home_all_axis(){
    make_call("home_all_axis", true);
}


function extruder_e_action(action){
     make_call("extruder-e", action + $("#extruder-e-value").val());
}


function extruder_mode(mode){
    make_call("extruder_mode", mode);
}


function bed_align(e){
    
    make_call("bed-align", true);
}

function bedTempChange(e){
    make_call("bed-temp", parseInt($(this).val()));
}

function extTempChange(e){
   
   make_call("ext-temp", parseInt($(this).val()));
    
}


function extTempSlide(e){
    var slide_val = parseInt($(this).val());
    
    $("#ext-degrees").html(slide_val);
    
}


function bedTempSlide(e){
    var slide_val = parseInt($(this).val());
    
    $("#bed-degrees").html(slide_val);
}


function motors(value){
	
	/*var value = $( "input[name='motors']:checked" ).val();*/
	make_call("motors", value);
	save_value("motors", value);
	
	
}


function lights(value){
	
	/*var value = $( "input[name='lights']:checked" ).val();*/
	make_call("lights", value);
	save_value("lights", value);
	
	
}



function coordinates(value){
	/*var value = $( "input[name='coordinates']:checked" ).val();*/
	make_call("coordinates", value);
	save_value("coordinates", value);
    enable_save_position();
}


function axisz(){
    
    var func = $(this).attr("data-attribute-function");
    var step = $(this).attr("data-attribute-step");
    make_call(func, step);
    
}


function get_temp(){
    make_call("get-temp", true);
}

function position(){
    
    make_call("position", true);
    
}


function directions(){
	var value = $(this).attr("data-attribue-direction");
	make_call("directions", value);	
}




function rotation(value){

	make_call("rotation", value);
	
}


function make_call(func, value){
    
    var timestamp = new Date().getTime();        
    ticker_url = '/temp/jog_' + timestamp + '.trace';
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
    console_editor.insert(command + ' : ' + response);
    console_editor.navigateLineEnd();
}

function mdi(){

	var gcode = jQuery.trim(mdi_editor.getSession().getValue());
    if(gcode != ''){
        make_call('mdi', gcode);
    }	
}

function feed(feed){
	make_call('feed', feed)
}



function save_value(key, value){

	var data = {};

	data[key] = value;
	
	$.ajax({
		type: "POST",
		url : "<?php echo site_url('jog/save') ?>",
		data : data,
		dataType: "json",
		beforeSend: function(msg){
		}
	}).done(function( data ) {
	});
}



function save_configuration(){

	var unit =     $('#unit').val();
	var step =     $("#step").val();
	var feedrate = $("#feedrate").val();

	$.ajax({
		type: "POST",
		url : "<?php echo site_url('jog/save') ?>",
		data : {unit: unit, step: step, feedrate:feedrate},
		dataType: "json",
		beforeSend: function(msg){
		}
	}).done(function( data ) {
	});
	make_call("unit", unit);
	
}


function zero_all(){
	make_call("zero_all", true);
}



function keyboard(e){

    console.log('prova');
    
	$( ".directions-container" ).each(function( index ) {

		  var key = $( this ).attr("data-attribute-keyboard");
		  var dir = $( this ).attr("data-attribue-direction");
		  
		  if(key == e.which){
			  make_call("directions", dir);
			  $(this).focus();
		  }
	});
	
}


</script>
