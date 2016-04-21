<script type="text/javascript">
	
	var interval_monitor;
	
	$(document).ready(function() {
		
		$("#update").on('click', confirm_update);
		$("#cancel").on('click', confirm_cancel);
		<?php if($task != false): ?>
		resume();
		<?php endif; ?>
		
		
	});
	
	function do_update(){
		IS_TASK_ON = true;
		$(".error").addClass('fa-spin');
		disable_button('#update');
		$("#update").html('Now updating');
		freeze_menu('updates');
		openWait("Starting");
		
		$.ajax({
			  url: "<?php echo site_url('updates/doit') ?>",
			  type: "POST",
              dataType: 'json',
		}).done(function( data ) {
			interval_monitor  = setInterval(retrieve_monitor, 1000);
			$('.title').html('<strong>Updating FABtotum Software</strong>');
			$("#update").addClass('animated fadeOut').remove();
			$(".off-message").removeClass('hidden').addClass('animated fadeIn');
			$(".mini").show();
			$("#cancel").show();
			closeWait();
		});
		
	}
	
	function do_cancel(){
		openWait("Canceling");
		$.ajax({
			  url: "<?php echo site_url('updates/cancel') ?>",
			  type: "POST",
              dataType: 'json',
		}).done(function( data ) {
			document.location.href = document.location.href;
			
		});
	}
	
	function confirm_update(){
		$.SmartMessageBox({
				title : "<i class='fa fa-refresh'></i> Do you want to download and install the new update?",
				buttons : '[No][Yes]'
		}, function(ButtonPressed) {
			if(ButtonPressed === "Yes") {
				do_update();		
			}
			if (ButtonPressed === "No") {}		
		});
	}
	
	function confirm_cancel(){
		$.SmartMessageBox({
				title : "<i class='fa fa-refresh'></i> Do you really want to cancel the update?",
				buttons : '[No][Yes]'
		}, function(ButtonPressed) {
			if(ButtonPressed === "Yes") {
				do_cancel();		
			}
			if (ButtonPressed === "No") {}		
		});
	}
	
	function manage_task_monitor(obj){
		if(obj.content != ''){
			monitor(jQuery.parseJSON(obj.content));
		}
	}
	
	function monitor(data){
		
		
		var download_percent = parseFloat((data.download.downloaded / data.download.size) * 100);
		$(".download-progress").attr('style', 'width:' + download_percent+'%');
		
		if(data.download.complete == false){
			$(".percent").html(precise_round(download_percent, 0) + '%');
		}else{
			$(".percent").html('<i class="fa fa-check"></i>');
			$(".download-info").html('Download complete');
			$(".progress").hide();
			
			if(data.installation.complete == true){
				$(".waiting-installation").remove();
				$(".mini").append('<p class="text-left waiting-installation"><span class="installation-info">Installation complete</span> <span class="pull-right"><i class="fa fa-check"></i></span></p>');
			}else{
				if($(".installation-info").length < 1) $(".mini").append('<p class="text-left waiting-installation"><span class="installation-info">Installing update</span> <span class="pull-right"><i class="fa fa-cog fa-spin"></i></span></p>');
			}
			
		}
		
		if(data.status == 'completed'){
			clearInterval(interval_monitor);
			console.log("Update terminato");
			openWait('<i class="fa fa-check"></i> Update complete', '', false);
			setTimeout(function(){
				document.location.href=document.location.href;
			}, 3000);
			
		}
			
	}
	
	function get_monitor(){
		$.get("/temp/task_monitor.json", function(data, status){
			monitor(data);
        });
	}
	
	function retrieve_monitor(){
		if(!SOCKET_CONNECTED) get_monitor();
	}
	
	function resume(){
		closeWait();
		get_monitor();
		interval_monitor  = setInterval(retrieve_monitor, 1000);
		$("#update").addClass('animated fadeOut').remove();
		$('.title').html('<strong>Updating FABtotum Software</strong>');
		$(".off-message").removeClass('hidden').addClass('animated fadeIn');
		$(".mini").show();
		$("#cancel").show();
		$(".error").addClass('fa-spin');
	}
	
</script>