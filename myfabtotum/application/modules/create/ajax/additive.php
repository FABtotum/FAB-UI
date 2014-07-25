<!-- FIRST STEP WITH INFO TYPE PRINT - ADDITIVE OR SUB -->
    
    
    <!-- SECOND RESULT OF MACRO -->
    <!-- THIRD ASK FOR AUTO BED LEVELING -->
    
    <div id="row_1" class="row interstitial" style="">

        <div class="col-sm-6">
            
            <div class="well">
                
                <div class="row">
                    
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/create/assets/img/additive/1.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge">1</span>
            			</h1>
                        
            			<h2>
            				Make sure that the working plane is clean and free to use
            			</h2>
                    
                    </div>
                
                </div>
            
                
            </div>
            
        </div>
        
        <div class="col-sm-6">
            <div class="well">
                
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <img style="max-width: 50%; display: inline;" class="img-responsive" src="application/modules/create/assets/img/additive/2.png" />
                    </div>
                    
                    <div class="col-sm-6 text-center">
                    
                        <h1>
            				<span class="badge">2</span>
            			</h1>
                        
            			<h2>
            				Close the cover
            			</h2>
                    
                    </div>
                    
                </div>
            
            </div>
        </div>
    
    </div>
    
    
    <div id="row_2" class="row interstitial" style="display: none;">

        <div class="col-sm-12">
            <div id="check_pre_print"  class="well">
                
                
                
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-center">
                        <h1 class="">Checking printer</h1>
                        <h2 class=""><i id="res-icon" class="fa fa-spinner"></i></h2>
                    </div>
                    <div class="col-sm-4"></div> 
                
                </div>
                
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 check_result  text-center" ></div>
                    <div class="col-sm-4"></div>
                </div>
                
            
            </div>
        
        </div>
    
    </div>
    
    <div id="row_3" class="row interstitial" style="display: none;">

        <div class="col-sm-12">
            <div class="well text-center final-step">
                <h1>Auto bed leveling ?</h1>
                <div class="final-step-response"></div>
            </div>
        
        </div>
    
    </div>
    
    
    
    
    <div class="row button-print-container margin-bottom-10">
        <div class="col-sm-12 text-center ">
        
            <a id="modal_link" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here when ready</a>
            
            <a id="skip" style="display: none;" href="javascript:void(0);" class="btn btn-primary btn-lg">Skip & Print</a>
            
        </div>
    </div>
    
<script type="text/javascript">

    $('#modal_link').on('click', function(){
    
            
            var actual_row;
            var next_row;
            var action = $(this).attr('data-action');
            
            if(action == "exec"){
                print_object();
                return false; 
            }
            
            
            if(action == "check"){
                
                
                pre_print();
                return false; 
            }
            
            
            
            $( ".interstitial" ).each(function( index ) {
                
                if($(this).is(":visible") ){
                    actual_row = parseInt($(this).attr('id').replace('row_', ''));
                } 
            });
            
            next_row = actual_row + 1;
            
            if ($("#row_" + next_row).length > 0){
                
                $("#row_" + actual_row).slideUp('slow', function(){
                
                });
                
                $("#row_" + next_row).slideDown('slow', function(){
                    
                    
                    switch(next_row){
                        
                        case 2:
                            pre_print();
                            break;
                        
                        case 3:
                            $("#modal_link").html('Exec');
                            $("#modal_link").attr('data-action', 'exec');
                            $("#skip").show();
                            break;
                        
                    }
                });
                
                
            }
          
            
        });
        
        
        function pre_print(){
            
            
            openWait('Checking printer');
            $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
            $("#res-icon").addClass('fa-spinner fa-spin');
            $('#modal_link').addClass('disabled');
            
            var timestamp = new Date().getTime();
            
            ticker_url = '/temp/check_' + timestamp + '.trace';
                       
            $.ajax({
        		  url: ajax_endpoint + 'ajax/pre_print.php',
        		  dataType : 'json',
                  type: "POST", 
        		  async: true,
                  data : { file : file_selected.full_path, time : timestamp }
        	}).done(function(response) {
                
                var status = response.status;
                
                if(status == 200){
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
                    $("#modal_link").html('Continue');
                    $("#modal_link").attr('data-action', '');
                    $('.check_result').html('');
                }else{
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
                    $('.check_result').html(response.trace);
                    $("#modal_link").html('Oops.. try again');
                    $("#modal_link").attr('data-action', 'check');    
                }
                
                ticker_url = '';
                closeWait();
                $('#modal_link').removeClass('disabled');    
        	});
        }


        $("#skip").on('click', function() {
            
            skip = 1;
            print_object();
            
        });

</script>