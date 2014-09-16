<script type="text/javascript">

$( "input[name='theme_skin']" ).click(function () {
    
    
    $.root_.removeClassPrefix('smart-style').addClass($(this).attr("value"));
    
    var image = $(this).attr("value") == 'smart-style-0' ? 'img-0.png' : 'img-3.png';
    var src   = $('#logo').find('img').attr('src');
    
    console.log(src);
    
    $('#logo').find('img').attr('src'); 
    
});


$('.nouislider').noUiSlider({
	start: 127,
	connect: "lower",

	range: {
		'min': 0,
		'max': 255
	},
	serialization: {
		format: {
			decimals: 0 
		}
	}
});


function setColor(){
        
	var color = 'rgb(' +
		$("#red").val() + ',' +
		$("#green").val() + ',' +
		$("#blue").val() +
	')';
    
    
    
    
    $("#on-color-red").val($("#red").val());
    $("#on-color-green").val($("#green").val());
    $("#on-color-blue").val($("#blue").val());

 
       
	// Fill the color box.
	$(".result").css({
		background: color,
		color: color
	});
}

$('.nouislider').on('slide', setColor);
</script>