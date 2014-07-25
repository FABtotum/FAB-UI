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
	start: <?php echo $_standby_color['red'] != '' ? $_standby_color['red'] : 0 ?> ,
	connect : "lower",

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
	start: <?php echo $_standby_color['green'] != '' ? $_standby_color['green'] : 0 ?> ,
	connect : "lower",

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
	start: <?php echo $_standby_color['blue'] != '' ? $_standby_color['blue'] : 0 ?> ,
	connect : "lower",

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

$(document).ready(function() {
    

	$('.printing').minicolors({
		control: $(this).attr('data-control') || 'hue',
		defaultValue: $(this).attr('data-defaultValue') || '',
		inline: $(this).attr('data-inline') === 'true',
		letterCase: $(this).attr('data-letterCase') || 'lowercase',
		opacity: $(this).attr('data-opacity'),
		position: $(this).attr('data-position') || 'bottom left',
		change: function(hex, opacity) {
			if (!hex) return;
			if (opacity) hex += ', ' + opacity;
			try {
				console.log(hex);
			} catch (e) {}
		},
		theme: 'bootstrap'
	});
});

</script>