<script type="text/javascript">

    var monitor_uri = '';
    var interval_monitor;
    
    var trace_uri = '';
    var interval_trace;
    
    var interval_timer;
    var elapsed_time   = 0;
    var time_left      = 0;
    var estimated_time = 0;
    
    var slice_finished = false;
    
    var time_left_saved = 0;
    
    var output_file_id;
    
    var editor;
    var task_id = <?php echo $_task ? $_task['id'] : '""' ?>;
    
    var myDropzone;
    var upload_file_path = "";
    
      $(function () {
      	
      	editor = ace.edit("slicer-config");
      	editor.getSession().setMode("ace/mode/ini");
      	editor.renderer.setShowPrintMargin(false);
      	
      	$("#preset-file").on('change', change_config);
      	$(".stop").on('click', ask_stop);
      	$("#save-config").on('click', save_config);
      	$("#download-slicer-config-button").on('click', function(){
      		
      		$("#dsc").val(editor.getValue());
      		$("#nsc").val($("#preset-file option:selected").text());
      		$("#download-slicer-config-form").submit();
      		
      	});
      	
      	$("#delete-slicer-config-button").on('click', ask_delete_config);
      	
      	
      	Dropzone.options.mydropzone = {
            
            url: "<?php echo site_url('objectmanager/slicer_config_upload'); ?>",
            dictResponseError: 'Error uploading file!',
            acceptedFiles : '.ini',
            autoProcessQueue: false,
	        parallelUploads: 1,
	        uploadMultiple: false,
	        addRemoveLinks: true,
            maxFiles: 1,
            /** INIT FUNCTION */
            init: function(){

           		myDropzone = this;
           		myDropzone.on("addedfile", function(file) {
				   
				});
				 
				myDropzone.on("complete", function(file) {
				    
				    var response = jQuery.parseJSON( file.xhr.response);
				    
				    $("#upload-config").removeClass("disabled");
            		$("#upload-config").html('<i class="fa fa-save"></i> Save');
				    
				    if(response.status == 'ok'){
				    	
				    	upload_file_path = response.file_path;
				    	myDropzone.removeAllFiles();
				    	add_new_config();	
				    }	    
				    
				});
               
            }
       }
       
       $("#upload-config").on('click', function(){
       		
       		if(myDropzone.getQueuedFiles().length > 0 && $("#config-name").val() != ''){
       			
	       		$("#upload-config").addClass("disabled");
	            $("#upload-config").html('<i class="fa fa-save"></i> Saving');
	       		
	       		if(new_config_form_ok()){
	       			    			
	       			if(myDropzone.getQueuedFiles().length > 0){
		                
		                myDropzone.processQueue(); 
	                 
	                 }
	       			
	       		}
       		
       		}
       		
      	});
       
      });
    
    
    
    <?php if($_task): ?>
    	
    	resume();
    	
    	function resume(){
    		
    		
    		<?php $_task_attributes = json_decode($_task['attributes'], true) ; ?>
    		
    		<?php $_json_monitor = json_decode(file_get_contents($_task_attributes['monitor']), true); ?>
    		
    		monitor_uri = '<?php echo str_replace('/var/www', '',  $_task_attributes['monitor']); ?>';
    		trace_uri = '<?php echo str_replace('/var/www', '',  $_task_attributes['trace']); ?>';
    		
    		var now = new Date().getTime();
    		now = (now / 1000);

    		var started = <?php echo str_replace('', '', $_json_monitor['slicing']['started']) ?>;
    		elapsed_time = (now - started);

    		interval_monitor   = setInterval(monitor, 1000);
            interval_trace     = setInterval(trace, 3000);
            interval_timer     = setInterval(timer, 1000);
    	}
    
    <?php endif; ?>
 
    $('#procees-button').on('click', function(){
        
        $.SmartMessageBox({
    				title: "This operation would take few minutes",
    				content: "Continue?",
    				buttons: '[No][Yes]'
    	}, function(ButtonPressed) {
    		if (ButtonPressed === "Yes") {
                        
                        process();
    					
    		}
    		if (ButtonPressed === "No") {
    
    		}
    
    	});
        
    });
    
    
    
    function process(){
            
            
            openWait('Initializing Slic3r');
            $("#procees-button").find("i").addClass('fa-spin');
            $("#procees-button").addClass('disabled');
            $("#procees-button").html($("#procees-button").html().replace('Process', 'Processing'));
            
            var file = '<?php echo $_file->full_path; ?>';
            
        	$.ajax({
    			type: "POST",
    			url: "<?php echo module_url('objectmanager').'ajax/process.php' ?>/",
                data: {type: 'gcode', file: file,  preset: editor.getValue(), output: $("#output").val(), output_type: $("#output_type").val(), object : <?php echo $_object; ?>, id_file : <?php  echo $_file->id;?>},
                dataType: 'json'
    		}).done(function(response) {
    		       
                   $("#procees-button").removeClass('disabled');
                   $("#procees-button").html($("#procees-button").html().replace('Processing', 'Process'));
                   $("#procees-button").find("i").removeClass('fa-spin');
                   
                   $( ".setting" ).slideUp( "slow", function() {
                   
                        $( ".monitor" ).slideDown( "slow", function() {});
                   
                   
                   });
                   
                   var monitor_json = JSON.parse(response.monitor_json);
                   monitor_json     = jQuery.parseJSON(monitor_json);
                   
                   trace_uri   = response.trace_uri;
                   monitor_uri = response.monitor_uri;
                   time_left   = parseInt(monitor_json.slicing.stats.time_left);
                   
                   task_id = response.task_id;
                   
                   output_file_id = parseInt(response.id_new_file);
                   
                   interval_monitor   = setInterval(monitor, 1000);
                   interval_trace     = setInterval(trace, 3000);
                   interval_timer     = setInterval(timer, 1000);
                   $(".fab-buttons").addClass('disabled');
                   
                   closeWait();
                   
    		});
        
        
    }
    
    
    function monitor(){
        
        if(slice_finished == false){
            monitor_get();
        }else{
            
            clearInterval(interval_monitor);
            clearInterval(interval_trace);
            clearInterval(interval_timer);

            setTimeout(function (){
            	
            	$( ".monitor" ).slideUp( "slow", function() {
		                 $( ".complete" ).slideDown( "slow", function() {});
		            });   
		        }
            	
            , 5000);
    	}
    
    }
    
    
    function monitor_get(){
        
        
        $.get( monitor_uri , function( data ) {
        
            if(data != ''){
                
                monitor = jQuery.parseJSON(data);
            
            
                $('#lines-progress').attr('style', 'width:' + parseInt(monitor.slicing.stats.percent) + '%');
                $('#lines-progress').attr('aria-valuetransitiongoal',  parseInt(monitor.slicing.stats.percent));
                $('#lines-progress').attr('aria-valuenow', parseInt(monitor.slicing.stats.percent));
                
                $('#lines-progress').html(number_format(parseInt(monitor.slicing.stats.percent), 1, ',', '.') + ' %');
    			$('.progress-status').html(	number_format(parseInt(monitor.slicing.stats.percent),1, ',', '.') + ' %');
                $('#label-progress').html('(' +	number_format(parseInt(monitor.slicing.stats.percent), 1, ',', '.') + ' % )');
                
                if(time_left_saved != parseInt(monitor.slicing.stats.time_left)){
                    time_left       = parseInt(monitor.slicing.stats.time_left);
                    time_left_saved = time_left;
                }
                
                
                $('.estimated-time').html(_time_to_string(parseInt(monitor.slicing.stats.time_total)));
                
                slice_finished = parseInt(monitor.slicing.completed) == 1 ? true : false;
    
            }
            
        }).fail(function(){ 
                
        });
        
    }
    
    
    function trace(){
        
        $.get( trace_uri , function( data ) {
            
            if(data != ''){

               $("#editor").html(data);
               var $t = $('#editor');
               $t.animate({"scrollTop": $('#editor')[0].scrollHeight}, "slow");
                
            }
        }).fail(function(){ 
                
        });
        
        
    }
    
    
    function timer() {
    
    	/**
    	 * ELAPSED TIME
    	 */
    	elapsed_time = (parseInt(elapsed_time) + 1);
    	$('.elapsed-time').html(_time_to_string(elapsed_time));
    
    	/**
    	 * TIME LEFT
    	 */
        time_left = (parseInt(time_left) - 1 );
        if(time_left >= 0){
            $('.estimated-time-left').html(_time_to_string(time_left));
        }
    
    }
    
    
    
    function print(){
        
        document.location.href='<?php echo site_url('create'); ?>?obj=<?php echo $_object; ?>&file=' + output_file_id;
        
    }
    
    
    
    function change_config(){
    	
    	
    	var file = $("#preset-file").val();
    	
    	file = 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/'+ file.replace('/var/www/', '') + '?t=' + $.now();
    	
    	$.get( file, function( data ) {
             
             
            editor.setValue(data);
            editor.gotoLine(0);
           
             
             
         });
    	
    }
    
    
    function ask_stop(){
    	
    	$.SmartMessageBox({
    				title: "<i class='fa fa-warning'></i> Warning",
    				content: "Do you want to stop the process?",
    				buttons: '[No][Yes]'
    	}, function(ButtonPressed) {
			if (ButtonPressed === "Yes") {
                stop();
			}
			if (ButtonPressed === "No") {

			}
    
    	});
    	
    	
    }
    
    
    function stop(){
    	
    		openWait('Stopping process');
    	
        	$.ajax({
    			type: "POST",
    			url: "<?php echo module_url('objectmanager').'ajax/stop_process.php' ?>/",
                data: {task_id : task_id},
                dataType: 'json'
    		}).done(function(response) {
    		     
    		     
    		     waitTitle('Process stopped. Refreshing page');
    		     setTimeout(function(){
    		     	document.location.href=document.location.href;
    		     }, 5000);
    		    
    		     
    		});
    	
    }
    
    
    function new_config_form_ok(){
    	
    	return $.trim($("#config-name").val()).length > 0;
    	
    }
    
    
    function add_new_config(){
    	
    	
    	$.ajax({
    			type: "POST",
    			url: "<?php echo module_url('objectmanager').'ajax/add_slice_config.php' ?>/",
                data: {file : upload_file_path, name: $("#config-name").val(), description: $("#config-description").val()},
                dataType: 'html'
    		}).done(function(response) {
    		     
    		     
    		    $("#preset-file").html(response);
    		    $("#preset-file").trigger('change');
    		    
    		    $(".add-config-modal").modal("hide");
    		    $("#config-name").val('');
    		    $("#config-description").val('');
    		    
    		    
    		    $.smallBox({
    				title : "Success",
    				content : "<i class='fa fa-check'></i> File config added",
    				color : "#659265",
    				iconSmall : "fa fa-thumbs-up bounce animated",
                    timeout : 4000
            	});
    		     
    		});
    	
    }
    
    
    
    function save_config(){
    	
    	
    	$("#save-config").addClass('disabled');
    	
    	$.ajax({
    			type: "POST",
    			url: "<?php echo module_url('objectmanager').'ajax/save_slice_config.php' ?>/",
                data: {file : $("#preset-file option:selected").val(), value: editor.getValue() },
                dataType: 'html'
    		}).done(function(response) {
    		     
    		     if(response == 1){
    		     	$.smallBox({
	    				title : "Success",
	    				content : "<i class='fa fa-check'></i> File config saved",
	    				color : "#659265",
	    				iconSmall : "fa fa-thumbs-up bounce animated",
	                    timeout : 4000
	            	});
    		     }
    		     
    		     $("#save-config").removeClass('disabled');
    		});

    }
    
    
    
    function ask_delete_config(){
    	
    	$.SmartMessageBox({
    		title: "<i class='fa fa-warning'></i>  Are you sure you want to delete the selected config?",
    		content: "Note: there is no undo function",
    		buttons: '[No][Yes]'
    	}, function(ButtonPressed) {
    		if (ButtonPressed === "Yes") {           
             	delete_slicer_config();        
    		}
    		if (ButtonPressed === "No") {
    
    		}
    	});
    	
    }
    
    
    function delete_slicer_config(){
    	
    	openWait('Deleting file..');
    	
    	$.ajax({
    		type: "POST",
    		url: "<?php echo module_url('objectmanager').'ajax/delete_slice_config.php' ?>/",
            data: {file : $("#preset-file option:selected").val()},
            dataType: 'html'
    	}).done(function(response) {
    			
    		$("#preset-file").html(response);
    		$("#preset-file").trigger('change');
    		    
    		closeWait();
    		    
    		$.smallBox({
    			title : "Success",
    			content : "<i class='fa fa-check'></i> File config deleted",
    			color : "#659265",
    			iconSmall : "fa fa-thumbs-up bounce animated",
                timeout : 4000
            });
    		     
    	});
    	
    }
    
</script>