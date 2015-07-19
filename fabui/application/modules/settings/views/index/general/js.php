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
        format: {
            decimals: 0
        }
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
		    format: {
            decimals: 0
        }
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
		    format: {
            decimals: 0
        }
		}

});

function setColor() {

    var color = 'rgb(' + $("#red").val() + ',' + $("#green").val() + ',' + $("#blue").val() + ')';
    $("#standby-color-red").val($("#red").val());
    $("#standby-color-green").val($("#green").val());
    $("#standby-color-blue").val($("#blue").val());

    $(".result").css({
        background: color,
        color: color
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
               data: {red : $("#red").val(), green: $("#green").val(), blue: $("#blue").val()}
		   }).done(function(response) {

       });

}

$('#save-button').on('click', save);

function save(){

	$('#save-button').addClass('disabled');
	$('#save-button').html('<i class="fa fa-save"></i>&nbsp;Saving...');

	$.ajax({
      url : '<?php echo module_url('settings').'ajax/general.php' ?>',
		  dataType : 'json',
		  type: 'post',
      data: {
					red : $("#red").val(), green: $("#green").val(), blue: $("#blue").val(),
          safety_door: $('[name="safety-door"]:checked').val(), switch:$('[name="switch"]:checked').val(),
          feeder_disengage_feeder: $("#feeder-disengage-offset").val(),
          milling_sacrificial_layer_offset: $("#milling-sacrificial-layer-offset").val(),
          /*
					feeder_extruder_steps_per_unit_e_mode: $("#feeder-extruder-steps-per-unit-e").val(),
          feeder_extruder_steps_per_unit_a_mode: $("#feeder-extruder-steps-per-unit-a").val(),
					*/
          both_y_endstops: $("#both-y-endstops").val(),
          both_z_endstops: $("#both-z-endstops").val(),
          upload_api_key: $("#upload-api-key").val()},
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

$("#feeder-disengage-offset").spinner({
	  step: 0.5,
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

/*
$("#feeder-extruder-steps-per-unit-e").spinner({
	  step: 0.1,
	  numberFormat : "N6",
	  min: 0,
	  create: function () { $(this).number(true,6,'.','') },
  	stop: function () { $(this).number(true,6,'.','') }
});

$("#feeder-extruder-steps-per-unit-a").spinner({
	  step :0.1,
	  numberFormat : "N6",
	  min: 0,
	  create: function () { $(this).number(true,6,'.','') },
  	stop: function () { $(this).number(true,6,'.','') }
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

</script>
