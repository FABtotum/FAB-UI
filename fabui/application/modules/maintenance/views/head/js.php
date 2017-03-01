<script>
	 $(function () {
	 	
	 	$("#heads").on('change', set_head_img);
	 	$("#set-head").on('click', set_head);
	 	
	 	
	 	<?php if(isset($_REQUEST['head_installed']) &&  !in_array($units['hardware']['head']['type'], $no_calibration_heads)): ?>
	 		
	 		$.SmartMessageBox({
				title : "<i class='fa fa-warning'></i> New head has been installed, it is recommended to repeat the Probe Calibration operation",
				buttons : '[<i class="fa fa-crosshairs"></i> Calibrate][Ignore]'
			}, function(ButtonPressed) {
				if(ButtonPressed === "Calibrate") {
						document.location.href="<?php echo site_url('maintenance/nozzle/height-calibration'); ?>";
				}
				if (ButtonPressed === "Ignore") {
					
				}
		
			});
	 	
	 	<?php endif; ?>
	 	
	 	
	 });
	 
	 
	 function set_head_img(){
	 	
	 	$(".jumbotron").html('');
	 	
	 	$("#head_img").parent().attr('href', 'javascript:void(0);');
	 	$("#head_img").css('cursor', 'default');
	 	$("#set-head").prop("disabled",false);
	 	
		$("#head_img").attr('src', '<?php echo module_url('maintenance') ?>assets/img/head/' + $(this).val() + '.png');
		
		if($("#" + $(this).val() + "_description").length > 0){
			$(".jumbotron").html($("#" + $(this).val() + "_description").html());
		}
		
		if($(this).val() == 'more_heads'){
			$("#head_img").parent().attr('href', 'http://www.fabtotum.com/3d-printers/heads/?from=fabui&module=maintenance&section=head');
	 		$("#head_img").css('cursor', 'pointer');
	 		$("#set-head").prop("disabled",true);
		}
		
		if($(this).val() == 'head_shape'){
			$("#set-head").prop("disabled",true);	
		} 
			 
	 }
	 
	 
	 function set_head(){
	 	
	 	if($("#heads").val() == 'head_shape'){
	 		alert('Please select a Head');
	 		return false;
	 	}
	 	
	 	IS_MACRO_ON = true;
	 	openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Installing head');
	 	
	 	$.ajax({
			type: "POST",
			url : "<?php echo module_url('maintenance').'ajax/set_head.php' ?>",
			data : {head:$("#heads").val()},
			dataType: "json"
		}).done(function( data ) {
			
			$(".alerts-container").find('div:first-child').remove();
			$(".alerts-container").append('<div class="alert alert-success animated  fadeIn" role="alert"><i class="fa fa-check"></i> Well done! Now your <strong>FABtotum Personal Fabricator</strong> is setted for the <strong>'+ data.description +'</stron></div>');
			IS_MACRO_ON = false;
			
			waitContent('Well done! Now your <strong><i>FABtotum Personal Fabricator</i></strong> is configured to use <strong><i>'+ data.description+'</i></strong>');
			
			setTimeout(function(){document.location.href =  '<?php echo site_url('maintenance/head'); ?>?head_installed';}, 2000);
			
		});
	 	
	 }
	 
</script>