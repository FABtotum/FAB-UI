<div id="row_1" class="row interstitial">

    <div class="col-sm-12">
    
        <div class="well text-center">
        
            <h1>Instructions</h1>
        
        </div>
    
    </div>

</div>


<div id="row_2" class="row interstitial" style="display: none;">

    <div class="col-sm-12">
    
        <div class="well text-center">
        
            <h1>Checking printer</h1>
            <h2 id="res-icon" class="fa fa-spinner"></h2>
                
            <p class="check_result"></p>
        
        </div>
    
    </div>

</div>


<div id="row_3" class="row interstitial" style="display: none;">

    <div class="col-sm-12">
    
        <div class="well text-center">
        
            <h1>Set origin</h1>
            <div class="row  margin-bottom-10 ">
				<div class="btn-group-vertical">
					<a href="javascript:void(0)" data-attribue-direction="up-left" data-attribute-keyboard="103" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="fa fa-arrow-left fa-1x fa-rotate-45">
						</i>
					</a>
					<a href="javascript:void(0)" data-attribue-direction="left" data-attribute-keyboard="100" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<span class="glyphicon glyphicon-arrow-left ">
						</span>
					</a>
					<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="fa fa-arrow-down fa-rotate-45 ">
						</i>
					</a>
				</div>
				<div class="btn-group-vertical">
					<a href="javascript:void(0)" data-attribue-direction="up" data-attribute-keyboard="104" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="fa fa-arrow-up fa-1x">
						</i>
					</a>
					<a id="zero-all" href="javascript:void(0)"  class="btn btn-default btn-lg btn-circle btn-xl rotondo">
						<i class="fa fa-bullseye">
						</i>
					</a>
					<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="glyphicon glyphicon-arrow-down ">
						</i>
					</a>
				</div>
				<div class="btn-group-vertical">
					<a href="javascript:void(0)" data-attribue-direction="up-right" data-attribute-keyboard="105" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="fa fa-arrow-up fa-1x fa-rotate-45">
						</i>
					</a>
					<a href="javascript:void(0)" data-attribue-direction="right" data-attribute-keyboard="102" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<span class="glyphicon glyphicon-arrow-right">
						</span>
					</a>
					<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99" class="btn btn-default btn-lg directions btn-circle btn-xl rotondo">
						<i class="fa fa-arrow-right fa-rotate-45">
						</i>
					</a>
				</div>
                
                
                <div class="btn-group-vertical" style="margin-left: 20px;">
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zdown">
						<i class="fa fa-angle-double-up">
						</i>
						10
					</a>
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zdown">
						<i class="fa fa-angle-double-up">
						</i>
						5
					</a>
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zdown">
						<i class="fa fa-angle-double-up">
						</i>
						1
					</a>
                    <hr />
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="1" data-attribute-function="zup">
						<i class="fa fa-angle-double-down">
						</i>
						1
					</a>
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="5" data-attribute-function="zup">
						<i class="fa fa-angle-double-down">
						</i>
						5
					</a>
					<a href="javascript:void(0)" class="btn btn-default axisz" data-attribute-step="10" data-attribute-function="zup">
						<i class="fa fa-angle-double-down">
						</i>
						10
					</a>
				</div>
                
                
			</div>
        
        </div>
    
    </div>

</div>


<div class="row button-print-container margin-bottom-10">
        <div class="col-sm-12 text-center ">
            <a id="exec_button" href="javascript:void(0);" class="btn btn-primary btn-lg">Click here if you are ready</a>
        </div>
</div>


<script type="text/javascript">

    $("#zero-all").on("click", zero_all);
    
    $( ".axisz" ).on( "click", axisz );
    
	$(".directions").on("click", directions);

    $('#exec_button').on('click', function(){
        
        
        var actual_row;
        var next_row;
        var action = $(this).attr('data-action');
        
        
        $( ".interstitial" ).each(function( index ) {
                
            if($(this).is(":visible") ){
                actual_row = parseInt($(this).attr('id').replace('row_', ''));
            } 
        });
        
        if(actual_row == 3){
            
            print_object();
            return false;
            
        }
        
        if(action == "check"){
                pre_print();
                return false; 
        }
        
        
        next_row = actual_row + 1;
        
        if ($("#row_" + next_row).length > 0){
            
            $('#exec_button').addClass('disabled');
            
            $("#row_" + actual_row).slideUp('slow', function(){
                
            });
            
            $("#row_" + next_row).slideDown('slow', function(){
                
                switch(next_row){
                    
                    case 2:
                        pre_print();
                        break;
                    
                    case 3:
                        $("#exec_button").html('Print');
                        $('#exec_button').removeClass('disabled');
                        break;
                    
                }
            });
        }
        
    });
    
    
    function pre_print(){
        
        $('#exec_button').addClass('disabled');
        $("#res-icon").removeClass('fa-warning fa-check txt-color-green txt-color-red fa-spinner fa-spin');
        $("#res-icon").addClass('fa-spinner fa-spin');
        $('#modal_link').addClass('disabled');
                        
            $.ajax({
        		  url: ajax_endpoint + 'ajax/pre_print.php',
        		  dataType : 'json',
                  type: "POST", 
        		  async: true,
                  data : { file : file_selected.full_path},
        		  beforeSend: function( xhr ) {
        		  }
        	}).done(function(response) {
                
                var status = response.status;
                
                if(status == 200){
                    
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check').addClass('txt-color-green');
                    $("#exec_button").html('Continue');
                    $('.check_result').html('');           
                    $("#exec_button").attr('data-action', '');
                    
                }else{
                    $("#res-icon").removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-warning').addClass('txt-color-red');
                    $('.check_result').html(response.trace);
                    $("#exec_button").html('Oops.. try again');
                    $("#exec_button").attr('data-action', 'check');
                }
                
                $('#exec_button').removeClass('disabled');
                
                
                
        	});
        
    }
    
    function axisz(){
    
        var func = $(this).attr("data-attribute-function");
        var step = $(this).attr("data-attribute-step");
        make_call(func, step);
    
    }
    
    
    function directions(){
    	var value = $(this).attr("data-attribue-direction");
    	make_call("directions", value);	
    }
    
    function zero_all(){
    	make_call("zero_all", true);
    }
    
    
    function make_call(func, value){

    	$.ajax({
    		type: "POST",
    		url :ajax_jog_endpoint + 'ajax/exec.php',
    		data : {function: func, value: value},
    		dataType: "json",
    		beforeSend: function(msg){
    			
    		}
    	}).done(function( data ) {
            
            
    	});
	
    }

</script>

