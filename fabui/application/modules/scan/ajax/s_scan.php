<div class="row">
	<div class="col-sm-6">
		<h2 class="text-primary">Ready to scan?</h2>
	</div>
</div>


<div class="row" id="row_1">
    <div class="col-sm-12">
    
        <div class="well text-center">
			<a id="check-pre-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
        </div>
    
    </div>
</div>


<div class="row" id="row_2" style="display: none;">


    <div class="col-sm-12">
    
        <div class="well">
        
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                
                    
                    <a id="check-s-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
                
                </div>
            </div>
            
        </div>
    
    </div>

</div>




<div id="row_3" class="row interstitial" style="display: none;">

    <div class="col-sm-12 text-center">
       
       <a id="start-s-scan" href="javascript:void(0);" class="btn btn-primary btn-lg ">Click to start scan</a>
    
    </div>

</div>


<script type="text/javascript">

    $("#check-pre-scan").on('click', check_pre_scan);
    
    $("#check-s-scan").on('click', check_s_scan);
    
    $("#start-s-scan").on('click', start);
    
    
    
    
    function start_s_scan(){
    	
    }
    
    
    function check_pre_scan(){
    
    	IS_MACRO_ON = true;
        $(".SmallBox").remove();
        $(".result-check-pre-scan").html('');
        openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Preparing printer');
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
                     	
                     	
                     	$("#check-s-scan").trigger("click");
                     	
                     });
                	
                });
                
               
                          
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
            IS_MACRO_ON = false;
    	});
     
    }
    
    
    
    
    function check_s_scan(){
    
    	
    	IS_MACRO_ON = true;
        $(".SmallBox").remove();
        
        openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Preparing printer');
        var timestamp = new Date().getTime();
        ticker_url    = '/temp/check_r_scan_' + timestamp + '.trace';
        
        
        $.ajax({
    		  type: "POST",
    		  url: macro_s_scan_url,
    		  dataType: 'json',
    		  asynch: true,
              data: {time: timestamp}
    	}).done(function( response ) {
    
            if(response.response == true){
            	
            	$("#row_2").slideUp('slow', function(){
            		
            		$("#row_3").slideDown('slow', function(){
            			
            			$("#start-s-scan").trigger("click");
            			
            		});
            		
            	}); 
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
            IS_MACRO_ON = false;        
    	});
         
       
    }

    
    
    
    
    

</script>