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
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/scan/assets/img/rotating/1.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge">1</span>
            			</h1>
                        
                        <h1 class="text-primary">Remove the platform</h1>
                        
            			<h2>
            				Check if the LED light on the platform inside is off. Then remove the building platform, exposing the A axis chuck
            			</h2>
                    
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
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/scan/assets/img/rotating/2.png" />
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
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/scan/assets/img/rotating/3.png" />
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

</div>


<div id="row_4" class="row interstitial" style="display: none;">

    <div class="col-sm-12">
        <div id="check_pre_print"  class="well well-light text-center">
            
            <h1>Checking printer</h1>
            <h2 id="res-icon-2" class="fa fa-spinner"></h2>
            
            <p class="check_result-2"></p>
        
        </div>
    
    </div>

</div>

<div class="row">
    <div class="col-sm-12 text-center">
        <a id="do-scan" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
    </div>
</div>

<script type="text/javascript">

/* START SCAN E PPROCESS BUTTON */
$("#do-scan").on('click', get_ready);


function get_ready(){
    
    
    
    var actual_row;
    var next_row;
    var action = $(this).attr('data-action');
    
    if(action == "check"){
        r_scan();
        return false; 
    }
    
    if(action == "check-pre"){
        pre_scan();
        return false; 
    }
    
    if(action == "exec"){
        start();
        return false; 
    }
    
    
    
    $( ".interstitial" ).each(function( index ) {
                
        if($(this).is(":visible") ){
            actual_row = parseInt($(this).attr('id').replace('row_', ''));
        } 
    });
    
    
    next_row = actual_row + 1;
    
    console.log(next_row);
    
    if ($("#row_" + next_row).length > 0){
        
        $("#row_" + actual_row).slideUp('slow', function(){
                
        });
                
        $("#row_" + next_row).slideDown('slow', function(){
                    
                    
            switch(next_row){
                        
                case 2:
                    r_scan();
                    break;
                case 3:
                    break;
                case 4:
                    pre_scan();
                    break;
            }
        });

    }
  
}






function r_scan(){
    
    $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
    $("#res-icon").addClass('fa-spinner fa-spin');
    $('#do-scan').addClass('disabled');
    
    $.ajax({
		  type: "POST",
		  url: macro_r_scan_url,
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
	   
       
	});
    
}



function pre_scan(){
    
    $("#res-icon-2").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
    $("#res-icon-2").addClass('fa-spinner fa-spin');
    $('#do-scan').addClass('disabled');
    
    $.ajax({
		  type: "POST",
		  url: pre_scan_url,
		  dataType: 'json',
		  asynch: true,
	}).done(function( response ) {
	   
         var status = response.status;
         
         if(status == 200){
                    
            $("#res-icon-2").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
            $("#do-scan").html('Continue');
            $("#do-scan").attr('data-action', 'exec');
            $('.check_result-2').html('');
                               
                    
         }else{
            $("#res-icon-2").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
            $('.check_result-2').html(response.trace);
            $("#do-scan").html('Oops.. try again');
            $("#do-scan").attr('data-action', 'check-pre');    
         }
         
                
         $('#do-scan').removeClass('disabled');
	   
       
	});
    
}


</script>