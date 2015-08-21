
<div class="row">
	<div class="col-sm-6">
		<h2 class="text-primary">Ready to scan?</h2>
	</div>
</div>


<div class="row" id="row_1">
    <div class="col-sm-12">
    
        <div class="well">
        
            <div class="row">
                <div class="col-sm-6 text-center">
                	 <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/scan/assets/img/probing/1.png" />
                </div>
                <div class="col-sm-6 text-center">
                	 <h1><span class="badge">1</span></h1>
                	<h1 class="text-primary ">Position the object</h1>
                	<h2>Position your object in the center of the platform when prompted.<br> Secure it with a double sided tape or use the fixture holes</h2>
                	
                	<a id="check-pre-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
                </div>
            </div>
            
           
            
        </div>
    
    </div>
</div>


<div class="row" id="row_2" style="display: none;">


    <div class="col-sm-12">
    
        <div class="well">
        
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                
                    
                    <a id="check-p-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
                
                </div>
            </div>
            
        </div>
    
    </div>

</div>




<div id="row_3" class="row interstitial" style="display: none;">

    <div class="col-sm-12 text-center">
       
       <a id="start-p-scan" href="javascript:void(0);" class="btn btn-primary btn-lg ">Click to start scan</a>
    
    </div>

</div>


<script type="text/javascript">


	

	
    $("#check-pre-scan").on('click', check_pre_scan);
    
    $("#check-p-scan").on('click', check_p_scan);
    
    $("#start-p-scan").on('click', start);
    

    function check_pre_scan(){
    
    
        $(".SmallBox").remove();
        $(".result-check-pre-scan").html('');
        openWait('Checking printer');
        var timestamp = new Date().getTime();
        /*ticker_url    = '/temp/check_pre_scan_' + timestamp + '.trace';*/
        ticker_url    = '/temp/macro_trace';
        
        
        $.ajax({
    		  type: "POST",
    		  url: check_pre_scan_url,
    		  dataType: 'json',
    		  asynch: true,
              data: {time: timestamp}
    	}).done(function( response ) {
    
            if(response.response == true){
              
              
              $("#row_1").slideUp('slow', function(){
              	
              	$("#row_2").slideDown('slow', function(){
              		
              		$("#check-p-scan").trigger('click');
              		
              	});
              	
              });
              
              
              /*
                setTimeout(function(){ $("#row_1").slideUp('slow', function(){
                      closeWait();
                     $("#row_2").slideDown('slow', function(){});
                     
                 });}, 3000);
                
                */
                /** STEP SUCCESSIVO */
                
                          
            }else{
                closeWait();
                $.smallBox({
    				title : "Warning",
    				content: response.trace,
    				color : "#C46A69",
    				icon : "fa fa-warning",
                    timeout: 15000
                });     
            }
            
            
            ticker_url = '';
    	});
     
    }
    
    
    
    
    function check_p_scan(){
    
    
        $(".SmallBox").remove();
        
        openWait('Checking printer');
        var timestamp = new Date().getTime();
        ticker_url    = '/temp/check_p_scan_' + timestamp + '.trace';
        
        
        $.ajax({
    		  type: "POST",
    		  url: macro_p_scan_url,
    		  dataType: 'json',
    		  asynch: true,
              data: {time: timestamp}
    	}).done(function( response ) {
    
            if(response.response == true){
            	
            	
            	
            	
            	$("#row_2").slideUp('fast', function(){
            		
            		$("#row_3").slideDown('fast', function(){
            			$("#start-p-scan").trigger('click');
            		});
            		
            	});
                
               /** STEP SUCCESSIVO 
                setTimeout(function(){ $("#row_2").slideUp('slow', function(){
                    closeWait();
                     $("#row_3").slideDown('slow', function(){
                     	
                     	/*
                     	$("#start-p-scan").trigger('click');
                     	
                     	
                     	
                     });
                 });}, 3000);
                 */
                          
            }else{
                closeWait();
                $.smallBox({
    				title : "Warning",
    				content: response.trace,
    				color : "#C46A69",
    				icon : "fa fa-warning",
                    timeout: 15000
                });     
            }
            
            ticker_url = '';        
    	});
         
       
    }

    
    
    
    
    

</script>