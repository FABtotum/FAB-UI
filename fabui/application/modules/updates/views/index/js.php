<script type="text/javascript">
            
            
            var interval_monitor;
            var interval_refresh;
            var json_uri = '<?php echo $running == true ? $json_uri : "" ?>';
            
            var finished = false;
            
            var first_extracting = true;
            var first_installing = true;
            
            var timer_refresh = 0;
            var type = '<?php echo  $running == true ? $update_type : '' ?>';
            
            var id_task='<?php echo $running == true ? $id_task : '' ?>';    
        
			$(document).ready(function() {

				
				$("#progressbar").progressbar({value: 0});
                $('.download').on('click', ask_download);
                $('.delete').on('click', ask_delete);
				
				
			});
            
            
            <?php if($running == true): ?>
            
                <?php if($update_type == 'fabui'):  ?>
                    resume_myfab();
                <?php endif; ?> 
            
            
            <?php endif; ?>
            
            
            
            function ask_download (){

				type    = $(this).attr('download-item');
				version = $(this).attr('download-version');
                
                var button = $(this);
                
				$.SmartMessageBox({
					title : "Updates Center",
					content : "Do you want to download and install the new update?",
					buttons : '[No][Yes]'
				}, function(ButtonPressed) {
					if (ButtonPressed === "Yes") {
					   
                       $('.download').addClass('disabled');
                       button.next().show();
                       start_update();
                       button.parent().parent().addClass('warning');
					}
					if (ButtonPressed === "No") {
						
					}
		
				});
	
			}
            
             
            function ask_delete (){

				
                
				$.SmartMessageBox({
					title : "Updates Center",
					content : "Do you want to cancel the updating process?",
					buttons : '[No][Yes]'
				}, function(ButtonPressed) {
					if (ButtonPressed === "Yes") {

                       openWait('Cancellation in progress');
                       cancel_update();
                       
					}
					if (ButtonPressed === "No") {
						
					}
		
				});
	
			}
            
            
            function cancel_update(){
                

                 $.ajax({
					  url: "<?php echo module_url('updates') ?>ajax/cancel.php",
					  type: "POST",
                      dataType: 'json', 
                      data: {id_task: id_task}
				}).done(function( data ) {
						  
                        document.location.href = '<?php echo site_url("updates"); ?>';
				});
                
            }
            
            
            
            function start_update(){
                
                 $.ajax({
					  url: "<?php echo module_url('updates') ?>ajax/update.php",
					  type: "POST",
                      dataType: 'json', 
                      data: {type: type}
				}).done(function( data ) {
						  
                        json_uri = data.json_uri;
                        id_task = data.id_task;
                        $('.progress-container').show();
					    interval_monitor  = setInterval(monitor, 1000);
					    $('#download-container').slideDown('slow', function() {});
				});

                
            }
            
            
            function monitor(){
            	if(!SOCKET_CONNECTED){	
	                if(finished == false){
	                    json_call();
	                }else{
	                    finalize_download();
	                }
                }
            }
            
            
            function json_call(){
                
               	$.ajax({
					  url: json_uri,
					  dataType: 'json', 
					  cache: false
				}).done(function( response ) {
					manage_update(response);
				});
                
            }
            
            
            
            function _downloading(data){
            	if(typeof(data) != "undefined"){
            		$("#status").html("<i class='fa fa-download'></i> Downloading...");
                	$('.progress-container').slideDown('slow', function() {});   
                	var percent = data.percent;
					percent = number_format(precise_round(percent, 2), 2, ',', '.');					  
					$("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
					$("#percentuale").html(percent + "%");
                	$("#size").html(bytesToSize(data.downloaded) + " of " + bytesToSize(data.download_size));
					$("#velocita").html('(' + bytesToSize(data.velocita) + '/s)');
            	}
            }
            
            
            function _extracting(data){
                
                if(first_extracting){
                    $("#status").html("Download complete");
                    $("#progress-download").attr('style', 'width:100%');
    				$("#percentuale").html("<i class='fa fa-check'></i>");
    				$("#size").html("");
    				$("#velocita").html('');
                    first_extracting = false;
                }else{
                    $("#status").html("Extracting files..");
                    $("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
                    $("#percentuale").html(data.percent + "%"); 
                }

            }
            
            
            function _installing(data){
                
                
                if(first_installing){
                    $("#status").html("Extraction complete");
                    $("#progress-download").attr('style', 'width:100%');
                    $("#percentuale").html("<i class='fa fa-check'></i>");
                    $("#velocita").html('');
                    first_installing = false;
                }else{
                	
                	var text_status = type == 'marlin' ? 'Flashing firmware. Please don\'t turn off the printer until the operation is completed' : 'Installing new files';
                    $("#status").html(text_status);
                    $("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
                    $("#percentuale").html(data.percent + "%");
                }
                
            }
            
            
            function finalize_download(){
            	    
                clearInterval(interval_monitor);
                $("#status").html("Installation complete");
                $("#progress-download").attr('style', 'width:100%');
                $("#percentuale").html("<i class='fa fa-check'></i>");
                
                interval_refresh  = setInterval(refresh, 1000);
                
            }
            
            function reload_page(){
                clearInterval(interval_refresh);
                document.location.href = '<?php echo site_url("updates"); ?>';
            }
            
            function refresh(){
                
                if(timer_refresh == 3){
                    reload_page();
                }else{
                    timer_refresh++;
                }
                
            }
            
            function resume_myfab(){
                $('.download').addClass('disabled');
                json_call();
                $('.progress-container').slideDown('slow', function() {}); 
                interval_monitor  = setInterval(monitor, 1000);
            }


			function manage_task_monitor(obj){
				
				if(obj.content != ""){
					var monitor = jQuery.parseJSON(obj.content);
					manage_update(monitor);
				
				}
			}
			
			function manage_update(obj){
				
				
                finished   = parseInt(obj.completed) == 0 ? false : true;
                var status = obj.status;
                
                if(finished){
                	finalize_download();
                }
                
                /** IF FABUI MODE */
                if(type == 'fabui'){
                    
                    switch(status){
                        case 'downloading':
                            _downloading(obj.download);
                            break;
                        case 'extracting':
                            _extracting(obj.extract);
                            break;
                        case 'installing':
                            _installing(obj.install);
                            break;
                    }
                    
                }
                
                if(type == 'marlin'){
                    
                    switch(status){
                        case 'downloading':
                            _downloading(obj.download);
                            break;
                        case 'installing':
                            _installing(obj.install);
                            break;
                    }
                }
				
			}


			
		</script>