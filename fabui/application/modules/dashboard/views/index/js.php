<script type="text/javascript">
	
	
	$(function () {
	<?php if(isset($wizard_complete) && $wizard_complete == true): ?>
	
		$.smallBox({
			title : "Wizard setup",
			content : "Congratulations you completed with success the setup wizard. Now your FABtotum is ready",
			color : "#5384AF",		
			icon : "fa fa-check",
			timeout : 10000

		});
	
	<? endif; ?>
	
	});	


</script>