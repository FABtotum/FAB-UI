<script type="text/javascript">


    var choice = '';
    
    var interval_trace;
    var interval_response;
    var trace_file;
    var response_file;
    
    var finished = false;
    var editor;

    $(function () {
         
        $(".choice-button").on('click', function (){
                
            choice = $(this).attr('data-action');
            
            var name_procedure = '';
            
            $( ".choice" ).slideUp( "slow", function() {});
            $("." + choice + "-choice").slideDown('slow');
            $(".re-choice").slideDown('slow');
            $(".start").slideDown('slow');
            
            if(choice == 'unload' || choice == 'pre_unload'){
            	name_procedure = 'Unload Filament';
            }else{
            	name_procedure = 'Load Filament';
            }
            
            $(".procedure-name").html('>  <strong>' + name_procedure +' </strong>');
                
        });
        
        
        $(".re-choice-button").on('click', function(){
            
            $("." + choice + "-choice").slideUp('slow');
            $( ".choice" ).slideDown( "slow", function() {});
            $(".re-choice").slideUp('slow');
            $(".start").slideUp('slow');
            
            $(".procedure-name").html("");
            
        });
        
        
        $(".start-button").on('click', do_macro);
        
        
    });
    
    
    
    
    function do_macro(){
    	
    	
    	
    	if(choice == 'pre_unload'){
    		pre_unload();
    		return;
    	}
    	
    	IS_MACRO_ON = true;
    	$(".trace").slideDown('slow');
    	$(".new-spool").remove();
        $("." + choice + "-choice").slideUp('slow');
        
        $.ajax({
              type: "POST",
              url: "<?php echo module_url("maintenance").'ajax/spool.php' ?>",
              data: { action: choice},
              dataType: 'json'
        }).done(function( response ) { 
            
            response_file = response.uri_response;
            trace_file    = response.uri_trace;
            
            interval_response = setInterval(do_monitor, 1000);
            interval_trace    = setInterval(do_trace, 1000);
            
            $(".start").slideUp('slow');
            $(".start-button").addClass('disabled');
            $(".re-choice").slideUp('slow');
            $(".title").find("h2").html(choice.charAt(0).toUpperCase() + choice.slice(1) + 'ing filament');
            $(".title").slideDown('slow', function () {});
            $(".console").slideDown('slow', function () {});
            
             
           
             
            
        });
        
       
          
    }
    
    function pre_unload(){
    	
    	IS_MACRO_ON = true;
    	openWait("<i class='fa fa-circle-o-notch fa-spin'></i> Plese wait");
    	
    	$.ajax({
    		type:"POST",
    		url: "<?php  echo module_url("maintenance").'ajax/spool.php' ?>",
    		data: {action: choice},
    		dataType: "json"
    		
    	}).done(function(response){
    		choice = 'unload';
    		
    		closeWait();
    		 
    		 
    		$(".pre_unload-choice").slideUp( "slow", function() {});
    		$( ".choice" ).slideUp( "slow", function() {});
            $("." + choice + "-choice").slideDown('slow');
            $(".re-choice").slideDown('slow');
    		
    	});
    	
    }
    
    function do_monitor(){
        
        if(!SOCKET_CONNECTED){
	        if(finished == false){
	            monitor();
	        }else{
	        	end();
	            
	        }
        }
        
    }
    
    function do_trace(){
        
        if(!SOCKET_CONNECTED){
	        if(finished == false){
	            getTrace(trace_file, 'GET', $('.console'));
	        }else{
	        	end();
	            
	        }
        }
    }
    
    
    
    function monitor(){
        
        
        $.ajax({
		      url: response_file,
			  dataType: 'text'
        }).done(function( response ) {
              
              var string_response = response.replace("<br>", "");
              string_response = string_response.replace(new RegExp('<br>', 'g'), '');
              finished = string_response == 'true' ? true : false;    
        });
        
    }
    
  
  
  	function manage_macro_response(response){
  		
  		if(response.indexOf("true") > -1){
  			finished = true;
  			end()
  		}
  		
  		
  		  		
  	}
    
     
    function end(){
    	
    	
    	clearInterval(interval_response);
        clearInterval(interval_trace);
    	
    	$(".title").find("h2").html('Spool ' +  choice.charAt(0).toUpperCase() + choice.slice(1) + ' completed <i class="fa fa-check text-success"></i>');
    	
    	var act = choice == 'unload' ? 'load' : 'unload';
    		
    	$(".title").find('h2').append('<h5 class="new-spool">Do you want to ' + act + ' spool? <a href="javascript:again(\''+act+'\');"> YES </a> </h5>');
    		
    	/** VUOI CARICARCARE FILO ? */
    	
    	
    	$(".trace").slideUp('slow');
    	
    	$(".console").html('');
    	
    	IS_MACRO_ON = false;
    }
    
    
    function again(action){
    	
    	
    	choice = action;
    	finished = false;
    	
    	$(".title").slideUp('fast');
    	$(".start-button").removeClass('disabled');
    	$(".re-choice").slideDown('fast');
    	
    	$("." + action + "-choice").slideDown('fast', function() {
    		
    		$(".start").slideDown('fast');
    		
    	});
    	
    	
    	
    	
    }
    
    

</script>