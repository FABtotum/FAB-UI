<script type="text/javascript">
	$(function() {
		
		
		$("#plugin-file").on('change', function(){
			$("#install-button").removeClass("disabled");
			
			
			var files = !!this.files ? this.files : [];
			
			var explode = files[0].name.split(".");
			
			var extension = explode[explode.length-1];
			
			if(extension.toLowerCase() != 'zip'){
				alert("invalid file");
				$(this).val("");
				$("#install-button").addClass("disabled");
			}
			
			
		});
		
	});
</script>