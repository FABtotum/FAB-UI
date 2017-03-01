<script type="text/javascript">
	
	$(function() {
		
		
		<?php if(isset($message)): ?>
		
			
			var type = "<?php echo isset($message_type) &&  $message_type != '' ? $message_type : 'info'?>";
			
			var color;
			var icon;
			var title;
			
			switch(type){
				case 'info':
					title= "Info";
					color = "#296191";
					icon = "fa fa-thumbs-up";
					break;
				case 'warning':
					title= "Warning";
					color = "#C79121";
					icon = "fa fa-warning";
					break;
			}
			
			$.smallBox({
				title : title,
				content: "<?php echo $message ?>",
				color : color,
				icon : icon,
				timeout : 4000
			});

		<?php endif; ?>
		
		
		
		
		$(".remove").on('click', function(){
			
			
			var href = $(this).attr("data-href");
			var title = $(this).attr("data-title");
			
			
			$.SmartMessageBox({
				title : "<i class='fa fa-warning txt-color-orange'></i> You are about to remove " + title + " plugin",
				content : "Are you sure you wish to delete these files?",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") {
					document.location.href = href;	
				}
			});

			
			
			
			
		});
		
		
	});
	
	
</script>