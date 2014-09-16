<script type="text/javascript">
/*make_call("coordinates", 'relative', false);*/
$('#take_photo').on('click', take_photo);
$(".directions").on("click", mdi);

function take_photo (){
    
    $('#take_photo').addClass('disabled');
    $('#take_photo').html('<i class="fa fa-spinner fa-spin"></i> Taking picture...');
    $("#raspi_picture").addClass('sfumatura');
    $.ajax({
		  url: '<?php echo widget_url('cam') ?>ajax/picture.php',
		  dataType : 'json',
          type: "POST", 
		  async: true,
          data : {}
	}).done(function(response) {
        d = new Date();
        $("#raspi_picture").attr('src', $('#raspi_picture').attr('src')+ "?time=" + d.getTime());
	    $('#take_photo').removeClass('disabled');
        $("#raspi_picture").removeClass('sfumatura');
        $('#take_photo').html('<i class="fa fa-camera"></i> Take a pic');   
	});
}


function directions(){
   	var value = $(this).attr("data-attribue-direction");
   	make_call("directions", value);	
}



function mdi(){
	var gcode = jQuery.trim($(this).attr("data-value"));
    make_call('mdi', gcode, true);
}


function make_call(func, value, take){

    	$.ajax({
    		type: "POST",
    		url :'<?php echo module_url('jog')?>ajax/exec.php',
    		data : {function: func, value: value},
    		dataType: "json"
    	}).done(function( data ) {
            if(take){
				take_photo();
			}
    	});
	
    }

</script>