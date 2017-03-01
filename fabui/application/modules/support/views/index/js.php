<script type="text/javascript">
	$(document).ready(function() {
		
		$("#help").on('change', show_faq);
		
		
	});
	
	
	function show_faq(){
		
		$("#faq-content").html($("#faq-"+$(this).val()).html());
	}
	
</script>