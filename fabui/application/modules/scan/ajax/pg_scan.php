<div class="row">

	<div class="col-sm-6">
		<h2 class="text-primary">Ready to scan?</h2>
	</div>

</div>

<div class="row interstitial" id="row_1">
	<div class="col-sm-12">
            <div class="well">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/scan/assets/img/rotating/1.png" />
                    </div>
                    <div class="col-sm-6 text-center">
                        <h1>
            				<span class="badge">1</span>
            			</h1>
                        <h1 class="text-primary">Remove the platform</h1>
            			<h2>
            				Check if the LED light on the platform inside is off. Then remove the building platform, exposing the A axis chuck
            			</h2>
                        <a id="check-pre-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
                    </div>
                </div>
        </div>
    </div>
</div>

<div id="row_2" class="row interstitial" style="display: none;">

    <div class="col-sm-12">
        <div id="check_pre_print"  class="well well-light text-center">
            
            <h1>Checking printer</h1>
            <h2 id="res-icon" class="fa fa-spinner"></h2>
            
            <p class="check_result"></p>
        
        </div>
    
    </div>

</div>



<div id="row_3" class="row interstitial" style="display: none;">
    
    <div class="col-sm-6">
        <div class="well">
        
            <div class="row">
                    
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/scan/assets/img/rotating/2.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge">2</span>
            			</h1>
                        
                        <h1 class="text-primary">Attach the object</h1>
                        
            			<h2>
            				Attach the object to the chuck with screws, duct tape, wire etc.
            			</h2>
                    
                    </div>
                
                </div>
            
        </div>
    </div>
    
    <div class="col-sm-6">
        <div class="well">
        
            <div class="row">
                    
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/scan/assets/img/rotating/3.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge">3</span>
            			</h1>
                        
                        <h1 class="text-primary">Close</h1>
                        
            			<h2>
            				Close the front
            			</h2>
                    
                    </div>
                
                </div>
            
        </div>
    </div>


    <div class="col-sm-12 text-center">
        <a id="check-r-scan" href="javascript:void(0);" class="btn btn-primary btn-lg ">Click here when ready</a>
    </div>
    
</div>


<div id="row_4" class="row interstitial" style="display: none;">

    <div class="col-sm-12 text-center">
       
       <a id="start-r-scan" href="javascript:void(0);" class="btn btn-primary btn-lg ">Click to start scan</a>
    
    </div>

</div>


<script type="text/javascript">

/* START SCAN E PPROCESS BUTTON */


$("#check-pre-scan").on('click', check_pre_scan);

$("#check-r-scan").on('click', check_r_scan);

$("#start-r-scan").on('click', start);



$(document).ready(function(){
	
	
	/*$('#pc-host-address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
    translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      }
    }
  });
  
  
  
  $('#pc-host-address').mask('099.099.099.099');
  $("#pc-host-port").mask("00000");
	*/
	
});




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
                      
            setTimeout(function(){ $("#row_1").slideUp('slow', function(){
                  closeWait();
                 $("#row_3").slideDown('slow', function(){
                 	
                 	
                 	
                 	
                 });
                 
             });}, 3000);
            
            
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
        IS_MACRO_ON = false;
	});
 
}

function check_r_scan(){
    
    
    IS_MACRO_ON = true;
    $(".SmallBox").remove();
    
    openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Preparing printer');
    var timestamp = new Date().getTime();
    /*ticker_url    = '/temp/check_r_scan_' + timestamp + '.trace';*/
    
    ticket_url    = '/temp/macro_trace';
    
    $.ajax({
		  type: "POST",
		  url: macro_pg_scan_url,
		  dataType: 'json',
		  asynch: true,
          data: {time: timestamp}
	}).done(function( response ) {

        if(response.response == true){
        		
        	$("#row_3").slideUp('fast', function () {
        		
        		$("#row_4").slideDown('fast', function () {
        			
        			$("#start-r-scan").trigger("click");
        			
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



function r_scan(){
    
    IS_MACRO_ON = true;
    $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
    $("#res-icon").addClass('fa-spinner fa-spin');
    $('#do-scan').addClass('disabled');
    
    $.ajax({
		  type: "POST",
		  url: macro_pg_scan_url,
		  dataType: 'json',
		  asynch: true,
	}).done(function( response ) {
	   
         var status = response.status;
         
         if(status == 200){
                    
            $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
            $("#do-scan").html('Continue');
            $("#do-scan").attr('data-action', '');
            $('.check_result').html('');
                               
                    
         }else{
            $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
            $('.check_result').html(response.trace);
            $("#do-scan").html('Oops.. try again');
            $("#do-scan").attr('data-action', 'check');    
         }
         $('#do-scan').removeClass('disabled');
         IS_MACRO_ON = false;
	   
       
	});   
}



</script>