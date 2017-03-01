<script type="text/javascript">
	$(function() {
		
		
		$("#plugin-file").on('change', function(){
			
			$(".zip-warning").remove();
			$("#install-button").removeClass("disabled");
			
			
			var files = !!this.files ? this.files : [];
			
			var explode = files[0].name.split(".");
			
			var extension = explode[explode.length-1];
			
			if(extension.toLowerCase() != 'zip'){
				
				$(".well").after('<div class="alert alert-warning zip-warning"><i class="fa-fw fa fa-warning"></i><strong>Warning</strong> Only .zip files are allowed</div>');
				$(this).val("");
				$("#install-button").addClass("disabled");
			}
			
			
		});
		
	});
</script>