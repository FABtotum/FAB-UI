<script>
	 $(function () {
	 	
	 	$("#heads").on('change', set_head_img);
	 	$("#set-head").on('click', set_head);
	 	
	 });
	 
	 
	 function set_head_img(){
	 	
	 	$("#description-container").html('');
	 	
	 	$("#head_img").parent().attr('href', 'javascript:void(0);');
	 	$("#head_img").css('cursor', 'default');
	 	$("#set-head").prop("disabled",false);
	 	
		$("#head_img").attr('src', '<?php echo module_url('maintenance') ?>assets/img/head/' + $(this).val() + '.png');
		
		if($("#" + $(this).val() + "_description").length > 0){
			
			$("#description-container").html($("#" + $(this).val() + "_description").html());
		}
		
		if($(this).val() == 'more_heads'){
			$("#head_img").parent().attr('href', 'https://store.fabtotum.com?from=fabui&module=maintenance&section=head');
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
	 	openWait('Installing head');
	 	
	 	$.ajax({
			type: "POST",
			url : "<?php echo module_url('maintenance').'ajax/set_head.php' ?>",
			data : {head:$("#heads").val()},
			dataType: "json"
		}).done(function( data ) {
			
			$(".alerts-container").find('div:first-child').remove();
			$(".alerts-container").append('<div class="alert alert-success animated  fadeIn" role="alert"><i class="fa fa-check"></i> Well done! Now your <strong>FABtotum Personal Fabricator</strong> is setted for the <strong>'+ data.description +'</stron></div>');
			
			closeWait();
			IS_MACRO_ON = false;
		});
	 	
	 }
	 
</script>