<script type="text/javascript">


    var monitor_uri = '';
    var interval_monitor;
    
    var trace_uri = '';
    var interval_trace;
    
    var interval_timer;
    var elapsed_time   = 0;
    var time_left      = 0;
    var estimated_time = 0;
    
    var mesh_finished = false;
    
    var time_left_saved = 0;
    
    var output_file_id;


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
                data: {type: 'stl', file: file,  output: $("#output").val(), object : <?php echo $_object; ?>, id_file : <?php  echo $_file->id;?> },
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
                    time_left   = parseInt(monitor_json.Meshing.stats.time_left);
                   
                    output_file_id = parseInt(response.id_new_file);
                   
                    interval_monitor   = setInterval(monitor, 1000);
                    interval_trace     = setInterval(trace, 3000);
                    interval_timer     = setInterval(timer, 1000);
                    
                    
                    
                   
    		});
        
        
    }
    
    
    
    function monitor(){
        
        if(mesh_finished == false){
            monitor_get();
        }else{
            
            clearInterval(interval_monitor);
            clearInterval(interval_trace);
            clearInterval(interval_timer);
               
            $( ".monitor" ).slideUp( "slow", function() {
                 $( ".complete" ).slideDown( "slow", function() {});
            });   
        }
        
    }
    
    
    
    
    function monitor_get(){
        
        
        $.get( monitor_uri , function( data ) {
        
            if(data != ''){
                
                
                monitor = data;
            
            
                $('#lines-progress').attr('style', 'width:' + parseInt(monitor.Meshing.stats.percent) + '%');
                $('#lines-progress').attr('aria-valuetransitiongoal',  parseInt(monitor.Meshing.stats.percent));
                $('#lines-progress').attr('aria-valuenow', parseInt(monitor.Meshing.stats.percent));
                
                $('#lines-progress').html(number_format(parseInt(monitor.Meshing.stats.percent), 1, ',', '.') + ' %');
    			$('.progress-status').html(	number_format(parseInt(monitor.Meshing.stats.percent),1, ',', '.') + ' %');
                $('#label-progress').html('(' +	number_format(parseInt(monitor.Meshing.stats.percent), 1, ',', '.') + ' % )');
                
                if(time_left_saved != parseInt(monitor.Meshing.stats.time_left)){
                    time_left       = parseInt(monitor.Meshing.stats.time_left);
                    time_left_saved = time_left;
                }
                
                
                $('.estimated-time').html(_time_to_string(parseInt(monitor.Meshing.stats.time_total)));
                
                mesh_finished = parseInt(monitor.Meshing.completed) == 1 ? true : false;
    
            }
            
        }).fail(function(){ 
                
        });
        
    }
    
    
    
    function trace(){
        
        $.get( trace_uri , function( data ) {
            
            if(data != ''){
                
                var trace = data;
                
                trace = trace.replace('\n', '<br>');
                trace = trace.replace('<?php echo PHP_EOL; ?>', '<br>');   
                $("#editor").html('<p>' + trace + '</p>');
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
    
    
    



</script>