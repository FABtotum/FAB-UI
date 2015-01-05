<script type="text/javascript">

    var editor;
    
    var interval_monitor;
    var interval_trace;
    var finished = false;
    
    var trace_file;
    var monitor_file;

    $(function () {       
        $("#start").on('click', self_test);
        
        
        <?php if($running): ?>
        
       	resume();
        
        <?php endif; ?>
        
    });
    
    
    
    function self_test(){
        
        
        var remote = $('#send-report').is(':checked') ? 1 : 0 ;
        $("#editor").html(''); 
        
        trace_file = '';
        monitor_file = '';
        
        finished = false;
        
        $("#start").addClass("disabled");
        $("#send-report").attr("disabled", 'disabled');
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/self_test.php' ?>",
              data: { remote : remote },
              dataType: 'json'
        }).done(function( response ) {
            
            monitor_file     = response.json_uri;
            trace_file       = response.trace_uri;
            interval_monitor = setInterval(do_monitor, 250);
            interval_trace   = setInterval(do_trace, 250);
            $("#editor").slideDown('slow', function () {});
               
        });
        
        
    }
    
    
    
    function do_monitor(){
        
        
        if(finished == false){
            monitor();
        }else{
            clearInterval(interval_monitor);
            clearInterval(interval_trace);
            
            
            $("#start").removeClass("disabled");
            $("#send-report").removeAttr("disabled");
            
        }
        
    }
    
    
    function do_trace(){
        
        if(finished == false){
            trace();
        }else{
            clearInterval(interval_monitor);
            clearInterval(interval_trace);
            
            $("#start").removeClass("disabled");
            $("#send-report").removeAttr("disabled");
        }
    }
    
    
    
    
    function monitor(){
        
        $.ajax({
		      url: monitor_file,
			  dataType: 'json',
			  cache: false
        }).done(function( response ) {
               
              finished = parseInt(response.finish) == 1 ? true : false; 
                
        });
        
    }
    
    
    function trace(){
        
        $.ajax({
		      url: trace_file,
			  dataType: 'text',
			  cache: false
        }).done(function( response ) {
            
          
            
            $("#editor").html(response);
            $('#editor').scrollTop(1E10);
            
        });
        
    }
    
    <?php if($running): ?>
	    function resume(){
	        monitor_file     = "<?php echo $monitor_file; ?>";
	        trace_file       = "<?php echo $trace_file; ?>";
	        interval_monitor = setInterval(do_monitor, 250);
	        interval_trace   = setInterval(do_trace, 250);
	        $("#editor").slideDown('slow', function () {});
	    } 
	<?php endif; ?>
</script>