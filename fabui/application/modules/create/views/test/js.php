<script type="text/javascript">


$(document).ready(function() {
	
	
	var range_all_sliders = {
	'min': [     0 ],
	'max': [   100 ]
};

	
	
	$("#pips-range").noUiSlider({
		range: range_all_sliders,
		start: 0,
	 	connect: 'lower'
	});


	$(".pips-range").noUiSlider_pips({
		mode: 'positions',
		values: [0,25,50,75,100],
		density: 10,
		format: wNumb({
			prefix: '&deg;'
		})
	});
	
	
});
	
</script>