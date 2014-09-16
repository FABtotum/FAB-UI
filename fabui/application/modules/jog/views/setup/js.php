<script type="text/javascript">


$(document).ready(function() {
    
    
    $("#save-conf").on("click", save_configuration);
    
    
});


function make_call(func, value){
    
    
     
	$.ajax({
		type: "POST",
		url : "<?php echo module_url('jog') ?>ajax/exec.php", 
		data : {f: func, val: value},
		dataType: "html",
		beforeSend: function(msg){
		
		}
	}).done(function( data ) {
	});
	
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



</script>