<script type="text/javascript">
	
	
	var limit_start = <?php echo $limit_start; ?>;
	var limit_end   = <?php echo $limit_end; ?>;
	
	var tasks_url = '<?php echo widget_url('tasks'); ?>';
	
	$(".tasks-load-more").on('click', load_more);
	
	
	
	
	function load_more(){
		
		
		var button_html = $(".tasks-load-more").html()
		
		$(".tasks-load-more").html('loading..');
		
		limit_start = limit_start + limit_end;
		
		var data = {start : limit_start, end: limit_end, mode: 'lasts'};
		
		$.ajax({
			url: '<?php echo widget_url('tasks') ?>ajax/load_more.php',
		  	dataType : 'html',
          	type: "GET", 
		  	async: true,
          	data : data
		}).done(function(response) {
	        
	       if(response != ''){
	       		$(".tasks-load-more").parent().before(response);
	       		$(".tasks-load-more").html(button_html);
	       		
	       		
	       		$("#lasts-wrap").animate({ scrollTop: $('#lasts-wrap')[0].scrollHeight}, 1000);
	       		
	       		
	       }else{
	       		$(".tasks-load-more").hide();
	       		$(".tasks-load-more").html(button_html);
	       }
	       
	        
		});
		
	}
	
</script>