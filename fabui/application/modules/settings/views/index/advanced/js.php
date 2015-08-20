<script type="text/javascript">

    var editor;

    $(function () {
    	
    	
    	
    	$('input:radio[name="settings_type"]').filter('[value="<?php echo $settings_type; ?>"]').attr('checked', true);
    	
    	
    	if('<?php echo $settings_type; ?>' == 'custom'){
    		$(".custom-settings").show();
    	}
    	

       	$(".save").on('click', save_settings);
       
       
		$(':radio[name="settings_type"]').change(function() {
  			var type = $(this).filter(':checked').val();
  			if(type == 'custom'){
  				$(".custom-settings").show();
  			}else{
  				$(".custom-settings").hide();
  			}
		});
		
    });
    
    
    
    
    function save_settings(){
    	
    	var button = $(".save");
    	
    	$(".save").addClass('disabled');
    	
    	var action                           = $(this).val();
    	var settings_type                    = $(':radio[name="settings_type"]').filter(':checked').val();
    	var feeder_extruder_steps_per_unit_e = $("#feeder-extruder-steps-per-unit-e").val();
    	var feeder_extruder_steps_per_unit_a = $("#feeder-extruder-steps-per-unit-a").val();
    	var invert_x_endstop_logic           = $("#invert_x_endstop_logic").val();
    	var show_feeder                      = $("#show_feeder").val();
    	var custom_overrides                 = $("#custom_overrides").val();  	
    	
    	var data = {type: settings_type, feeder_extruder_steps_per_unit_a: feeder_extruder_steps_per_unit_a, feeder_extruder_steps_per_unit_e: feeder_extruder_steps_per_unit_e, show_feeder : show_feeder, custom_overrides:custom_overrides, invert_x_endstop_logic:invert_x_endstop_logic, action:action};
    	  	
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
    		
    		
    		
    	});
             

        
    }
    
    
    
    
</script>