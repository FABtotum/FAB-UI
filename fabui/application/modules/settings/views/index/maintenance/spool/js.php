<script type="text/javascript">


    var choice = '';
    
    var interval_trace;
    var interval_response;
    var trace_file;
    var response_file;
    
    var finished = false;
    var editor;

    $(function () {
        
        /*
        editor = ace.edit("console");
        editor.getSession().setMode("ace/mode/text");
        editor.renderer.setShowPrintMargin(false);
        editor.setReadOnly(true);        
        */
        
        $(".choice-button").on('click', function (){
                
            choice = $(this).attr('data-action');
            
            $( ".choice" ).slideUp( "slow", function() {});
            $("." + choice + "-choice").slideDown('slow');
            $(".re-choice").slideDown('slow');
            $(".start").slideDown('slow');
                
        });
        
        
        $(".re-choice-button").on('click', function(){
            
            $("." + choice + "-choice").slideUp('slow');
            $( ".choice" ).slideDown( "slow", function() {});
            $(".re-choice").slideUp('slow');
            $(".start").slideUp('slow');
            
        });
        
        
        $(".start-button").on('click', do_macro);
        
        
    });
    
    
    
    
    function do_macro(){
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("settings").'ajax/spool.php' ?>",
              data: { action: choice},
              dataType: 'json'
        }).done(function( response ) { 
            
            response_file = response.uri_response;
            trace_file    = response.uri_trace;
            
            interval_response = setInterval(do_monitor, 1000);
            interval_trace    = setInterval(do_trace, 1000);
            
            
            $("." + choice + "-choice").slideUp('slow');
            $(".start").slideUp('slow');
            $(".start-button").addClass('disabled');
            $(".re-choice").slideUp('slow');
            $(".title").find("h2").html(choice.charAt(0).toUpperCase() + choice.slice(1) + 'ing Filament');
            $(".title").slideDown('slow', function () {});
            $("#console").slideDown('slow', function () {});
            
            
        });
          
    }
    
    
    
    function do_monitor(){
        
        
        if(finished == false){
            monitor();
        }else{
            clearInterval(interval_response);
            clearInterval(interval_trace);
        }
        
    }
    
    function do_trace(){
        
        if(finished == false){
            trace();
        }else{
            clearInterval(interval_response);
            clearInterval(interval_trace);
        }
    }
    
    
    
    function monitor(){
        
        $.ajax({
		      url: response_file,
			  dataType: 'json'
        }).done(function( response ) {
              
              var string_response = response.replace("<br>", "");
              string_response = string_response.replace(new RegExp('<br>', 'g'), '');
              finished = string_response == 'true' ? true : false;
              
               
                
        });
        
    }
    
    
    function trace(){
        
        $.ajax({
		      url: trace_file,
			  dataType: 'text'
        }).done(function( response ) {
            
            if(response != ''){
                /*
                editor.setValue('');
                var string_trace = response.replace("<br>", "\n");
                string_trace = string_trace.replace(new RegExp('<br>', 'g'), '\n');
                editor.setValue(string_trace);
                editor.navigateLineEnd();
                */
                
                $("#console").html('<p>' + response + '</p>');
                
                
                var $t = $('#console');
                $t.animate({"scrollTop": $('#console')[0].scrollHeight}, "slow");
            }
            

        });
        
    }
    
    

</script>