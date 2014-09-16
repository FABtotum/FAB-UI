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
    
    
    <?php if($_task): ?>
    	
    	resume();
    	
    	function resume(){
    	
    		<?php  $_task_attributes = json_decode($_task['attributes'], true) ; ?>
    		
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
            
            $("#procees-button").find("i").addClass('fa-spin');
            $("#procees-button").addClass('disabled');
            $("#procees-button").html($("#procees-button").html().replace('Process', 'Processing'));
            
            var file = '<?php echo $_file->full_path; ?>';
            
        	$.ajax({
    			type: "POST",
    			url: "<?php echo module_url('objectmanager').'ajax/process.php' ?>/",
                data: {type: 'gcode', file: file,  preset: $("#preset-file").val(), output: $("#output").val(), output_type: $("#output_type").val(), object : <?php echo $_object; ?>, id_file : <?php  echo $_file->id;?>},
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
                   
                   output_file_id = parseInt(response.id_new_file);
                   
                   interval_monitor   = setInterval(monitor, 1000);
                   interval_trace     = setInterval(trace, 3000);
                   interval_timer     = setInterval(timer, 1000);
                   $(".fab-buttons").addClass('disabled');
                   
                   
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
    
    
    
    
    
    

</script>