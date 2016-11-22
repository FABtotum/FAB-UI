<script type="text/javascript">

$("input[name='theme_skin']").click(function() {

	$.root_.removeClassPrefix('smart-style').addClass($(this).attr("value"));

	var new_image = $(this).attr("value") == 'smart-style-0' ? 'logo-0.png' : 'logo-3.png';
	var src = $('#logo').find('img').attr('src');

	var t = src.split('/');

	var old_image = t[t.length - 1];

	if (new_image != old_image) {

		$('#logo').find('img').attr('src', src.replace(old_image, new_image));

	}
});




$('.standby-red').noUiSlider({
    /*range: [0, 255],*/    
	start: <?php echo $_standby_color['r'] != '' ? $_standby_color['r'] : 0 ?>,
	connect : "lower",
    handles: 1,
        
	range : {
		'min': 0,
		'max': 255
	},
    
    serialization: {
		format: wNumb({
			decimals: 0
		})
	}
    
            

});


$('.standby-green').noUiSlider({
        
	/*range: [0, 255],*/
    start: <?php echo $_standby_color['g'] != '' ? $_standby_color['g'] : 0 ?>,
	connect : "lower",
    handles: 1,
    
	range : {
		'min': 0,
		'max': 255
	},
    serialization: {
		format: wNumb({
			decimals: 0
		})
	}
});

$('.standby-blue').noUiSlider({
    /*range: [0, 255],*/   
	start: <?php echo $_standby_color['b'] != '' ? $_standby_color['b'] : 0 ?>,
	connect : "lower",
    handles: 1,    

    
	range : {
		'min': 0,
		'max': 255
	},
    serialization: {
		format: wNumb({
			decimals: 0
		})
	}
       
        

});



function setColor() {
	
	var color = 'rgb(' + parseInt($("#red").val()) + ',' + parseInt($("#green").val()) + ',' + parseInt($("#blue").val()) + ')';
	
	$("#standby-color-red").val(parseInt($("#red").val()));
	$("#standby-color-green").val(parseInt($("#green").val()));
	$("#standby-color-blue").val(parseInt($("#blue").val()));

	$(".result").css({
		"background": color,
		"color:": color
	});
    
}


$('.standby-color').on('slide', setColor);
$('.standby-color').on('change', color);

function color(){
    
       $.ajax({
        url : '<?php echo module_url('settings').'ajax/color.php' ?>',
		  dataType : 'json',
		  type: 'post',
		  async : true,
          data: {red : parseInt($("#red").val()), green: parseInt($("#green").val()), blue: parseInt($("#blue").val())}
		}).done(function(response) {
		  
          
        });
    
}

$('#save-button').on('click', save);

function save(){
	
	$('#save-button').addClass('disabled');
	$('#save-button').html('<i class="fa fa-save"></i>&nbsp;Saving...');
	
	if(parseInt($("#print-preheating-extruder").val()) > <?php echo $max_temp; ?>)	$("#print-preheating-extruder").val(<?php echo $max_temp; ?>);
	if(parseInt($("#print-preheating-bed").val()) > 100) $("#print-preheating-bed").val(100);
	if($("#print-preheating-extruder").val() == '') $("#print-preheating-extruder").val(0);
	if($("#print-preheating-bed").val() == '') $("#print-preheating-bed").val(0);
	
	$.ajax({
        url : '<?php echo module_url('settings').'ajax/general.php' ?>',
		  dataType : 'json',
		  type: 'post',
          data: {red : parseInt($("#red").val()), green: parseInt($("#green").val()), blue: parseInt($("#blue").val()), 
          		safety_door: $('[name="safety-door"]:checked').val(), 
          		switch: $('[name="switch"]:checked').val(),
          		collision_warning : $('[name="collision-warning"]:checked').val(), 
          		feeder_disengage_feeder: $("#feeder-disengage-offset").val(),
          		milling_sacrificial_layer_offset: $("#milling-sacrificial-layer-offset").val(), 
          		feeder_extruder_steps_per_unit_e_mode: $("#feeder-extruder-steps-per-unit-e").val(), 
          		feeder_extruder_steps_per_unit_a_mode: $("#feeder-extruder-steps-per-unit-a").val(),
          		print_preheating_extruder : $("#print-preheating-extruder").val(),
          		print_preheating_bed : $("#print-preheating-bed").val(),
          		print_calibration: $("#print-calibration").val(),
          		both_y_endstops: $("#both-y-endstops").val(),
          		both_z_endstops: $("#both-z-endstops").val(),
          		upload_api_key: $("#upload-api-key").val(),
          		zmax:$('#zmax-homing').val(),
          		zprobe:$('[name="zprobe"]:checked').val()},
          dataType: 'json'
		}).done(function(response) {
			
			
			$.smallBox({
				title : "Success",
				content : "<i class='fa fa-check'></i> Settings saved",
				color : "#659265",
				iconSmall : "fa fa-thumbs-up bounce animated",
	            timeout : 4000
            });
		  
          
          	$('#save-button').removeClass('disabled');
          	$('#save-button').html('<i class="fa fa-save"></i>&nbsp;Save');
          
        });
	
}



$("#zmax-homing").spinner({
	step :0.05,
	numberFormat : "n",
	min: 150,
	max: 250
});


$("#feeder-disengage-offset").spinner({
				step :0.5,
				numberFormat : "N1",
				min: 0,
				max: 6,
				create: function () { $(this).number(true,1) },
				stop: function () { $(this).number(true,1) }
				
});


$("#milling-sacrificial-layer-offset").spinner({
 	step: 0.5,
 	numberFormat : "N1",
 	min: 0,
 	max: 25,
 	create: function () { $(this).number(true,1) },
   	stop: function () { $(this).number(true,1) }
 });
 
 $("#print-preheating-extruder").spinner({
 	step: 1,
 	numberFormat : "N",
 	min: 0,
 	max: <?php echo $max_temp; ?>,
 	create: function () { $(this).number(true,1) },
   	stop: function () { $(this).number(true,1) }
 });
 
 $("#print-preheating-bed").spinner({
 	step: 1,
 	numberFormat : "N",
 	min: 0,
 	max: 100,
 	create: function () { $(this).number(true,1) },
   	stop: function () { $(this).number(true,1) }
 });


/*
$("#feeder-extruder-steps-per-unit").spinner({
				step :0.1,
				numberFormat : "n",
				min: 0
		});
*/


$('#gen-key-button').on('click', newKey);

function newKey(){
	
	$("#upload-api-key").val(randomString(16));
	
}


function randomString(len, an){
   an = an&&an.toLowerCase();
    var str="", i=0, min=an=="a"?10:0, max=an=="n"?10:62;
    for(;i++<len;){
      var r = Math.random()*(max-min)+min <<0;
      str += String.fromCharCode(r+=r>9?r<36?55:61:48);
    }
    return str;
}


$("#general-tab li > a").on('click', function() {
	
	$(".widget-footer .btn").hide();
	
	if($(this).attr('href') == '#hardware'){
		$(".hardware-save").show();
	}else{
		$("#save-button").show();
	}
	
});


$('input:radio[name="settings_type"]').filter('[value="<?php echo $settings_type; ?>"]').attr('checked', true);

if('<?php echo $settings_type; ?>' == 'custom'){
	$(".custom-settings").show();
}else{
	$(".custom-settings").hide();
}

$(':radio[name="settings_type"]').change(function() {
	var type = $(this).filter(':checked').val();
	if(type == 'custom'){
		$(".custom-settings").show();
	}else{
		$(".custom-settings").hide();
	}
});


$(".hardware-save").on('click', save_hardware_settings);

function save_hardware_settings(){
    	
	IS_MACRO_ON = true;
	var button = $(".save");
	$(".save").addClass('disabled');
	
	var action                           = $(this).val();
	var settings_type                    = $(':radio[name="settings_type"]').filter(':checked').val();
	var feeder_extruder_steps_per_unit_e = $("#hw-feeder-extruder-steps-per-unit-e").val();
	var feeder_extruder_steps_per_unit_a = $("#hw-feeder-extruder-steps-per-unit-a").val();
	var invert_x_endstop_logic           = $("#invert_x_endstop_logic").val();
	var show_feeder                      = $("#show_feeder").val();
	var custom_overrides                 = $("#custom_overrides").val();  	

	var data = {type: settings_type, feeder_extruder_steps_per_unit_a: feeder_extruder_steps_per_unit_a, feeder_extruder_steps_per_unit_e: feeder_extruder_steps_per_unit_e, show_feeder : show_feeder, custom_overrides:custom_overrides, invert_x_endstop_logic:invert_x_endstop_logic, action:action};
	  
	  
	openWait("Please wait");
	  	
	$.ajax({
		type: 'POST',
		url : '<?php echo module_url('settings').'ajax/advanced.php' ?>',
		data: data,
		dataType: 'json'
	}).done(function (response) {
		
		$(".save").removeClass('disabled');
		
		
		$.smallBox({
			title : "Success",
			content : "<i class='fa fa-check'></i> " + response.message,
			color : "#659265",
			iconSmall : "fa fa-thumbs-up bounce animated",
            timeout : 4000
        });

        IS_MACRO_ON = false;
        closeWait();
	});
}


</script>