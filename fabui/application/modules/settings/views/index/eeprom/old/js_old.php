<script type="text/javascript">
	
	
	$("#save").on('click', save_eeprom);
	$("#default").on('click', restore_eeprom);
	
	
	function restore_eeprom(){
		
		$("#default").html('Restoring...');
		
		$('.btn').addClass('disabled');
		$.ajax({
    		type: 'POST',
    		url : '<?php echo module_url('settings').'ajax/eeprom.php' ?>',
    		data: { action:'restore'},
    		dataType: 'json'
    	}).done(function (response) {
    		
    		
    		jQuery("#fieldset").html(response.html);
    		$('.btn').removeClass('disabled');
    		$("#default").html('Restore');
    		
    		$.smallBox({
				title : "Success",
				content : "<i class='fa fa-check'></i> " + response.message,
				color : "#659265",
				iconSmall : "fa fa-thumbs-up bounce animated",
	            timeout : 4000
            });
    		
    	});
		
	}
	
	
	function save_eeprom(){
		
		
		$('.btn').addClass('disabled');
		$("#save").html('<i class="fa fa-save"></i> Saving...');
		
		
		$eeprom = $('.eeprom_comamnd');
		
		var eeprom_data = [];
		
		
		$eeprom.each(function(){
			
			var item = {'comment' : $(this).find('.label').html(), 'command' : $(this).find('input:text').val() };
			eeprom_data.push(item);
			
		});
		

		$.ajax({
    		type: 'POST',
    		url : '<?php echo module_url('settings').'ajax/eeprom.php' ?>',
    		data: {eeprom: eeprom_data, action:'save'},
    		dataType: 'json'
    	}).done(function (response) {
    		

    		$('.btn').removeClass('disabled');
    		$("#save").html('<i class="fa fa-save"></i> Save');
    		
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