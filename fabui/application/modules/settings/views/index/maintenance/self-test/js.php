<script type="text/javascript">

    var editor;
    
    var interval_monitor;
    var interval_trace;
    var finished = false;
    
    var trace_file;
    var monitor_file;

    $(function () {
        
        /*
        editor = ace.edit("editor");
        editor.getSession().setMode("ace/mode/text");
        editor.setTheme("ace/theme/terminal");
        editor.renderer.setShowGutter(false);
        editor.renderer.setShowPrintMargin(false);
        editor.setHighlightActiveLine(false);
        editor.setReadOnly(true);
        */
        
        $("#start").on('click', self_test);
          
    });
    
    
    
    function self_test(){
        
        
        
        
        var remote = $('#send-report').is(':checked') ? 1 : 0 ;
        
        $("#start").addClass("disabled");
        $("#send-report").attr("disabled", 'disabled');
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("settings").'ajax/self_test.php' ?>",
              data: { remote : remote },
              dataType: 'json'
        }).done(function( response ) {
            
            monitor_file     = response.json_uri;
            trace_file       = response.trace_uri;
            interval_monitor = setInterval(do_monitor, 1000);
            interval_trace   = setInterval(do_trace, 1000);
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
            $("#send-report").removeAttr("disabled")
            
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
			  dataType: 'json'
        }).done(function( response ) {
               
              finished = parseInt(response.finish) == 1 ? true : false; 
                
        });
        
    }
    
    
    function trace(){
        
        $.ajax({
		      url: trace_file,
			  dataType: 'text'
        }).done(function( response ) {
            
            /*
            editor.getSession().setValue(response);
            editor.navigateLineEnd();
            */
            
            $("#editor").html(response);
            $('#editor').scrollTop(1E10);
            
        });
        
    }

</script>