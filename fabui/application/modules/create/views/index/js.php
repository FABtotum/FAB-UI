<script type="text/javascript">

	var id_task = <?php echo $_id_task; ?>;
	var pid     = <?php echo $_pid; ?>; 
	/**/
	var monitor_file = "<?php echo $_monitor_file; ?>";
	var data_file    = "<?php echo $_data_file; ?>";
	var trace_file   = "<?php echo $_trace_file; ?>";
	var debug_file   = "<?php echo $_debug_file; ?>";
    var uri_monitor  = "<?php echo $_uri_monitor; ?>";
    var uri_trace    = "<?php echo $_uri_trace; ?>";
    var stats_file   = "<?php echo $_stats_file; ?>"; 
    var folder       = "<?php echo $_folder; ?>";
    var print_type   = "";
	/**/
	var monitor_response;
	<?php if($_running && strlen($_monitor) > 0): ?>
	monitor_response = <?php echo $_monitor; ?>;
	<?php endif; ?>
	/**/
	var elapsed_time  = <?php echo $_seconds ?>; 

	var array_estimated_time =  <?php echo $_estimated_time; ?>;
	var array_progress_steps =  <?php echo $_progress_steps; ?>;
	
	/*var ajax_endpoint         = '<? echo site_url('create') ?>';*/
    var ajax_endpoint         = '<? echo module_url('create') ?>';
	/*var ajax_object_endopoint = '<?php echo site_url('objectmanager')?>';*/
    var ajax_object_endopoint = '<?php echo module_url('objectmanager')?>';
    var ajax_intertitial_endpoint = '<?php echo module_url('interstitial')  ?>';
    var ajax_jog_endpoint = '<?php echo module_url('jog'); ?>';

	var is_running = <?php echo  $_running ? 'true' : 'false' ?>;
	var server_host = 'http://<? echo $_SERVER['HTTP_HOST'] ?>/';
    
    /** IF I COME FROM OBJECT MANAGER */
    var request_file = <?php echo $_request_file != FALSE ? $_request_file : 0 ?>;
    var do_request_file = <?php echo $_request_file != FALSE ? 'true' : 'false' ?>;
    
    /** ACE EDITOR */
    /*
    var editor;
    */
    var view_details = false;

    /** PRE-PRINT */
    var pre_print_trace    = '';
    var pre_print_response = '';
    var pre_print_url_response = '';
    var skip                   = 0;
    
    /** TICKER */
    var ticker_url = '';
    var interval_ticker;
    
    var extruder_target = <?php echo $ext_target ?>; 
    var bed_target     = <?php echo $bed_target; ?>;
    
    
    /** CALIBRATION */
	var calibration = 'homing';
	
	/** PROGRESS */
	var progress = 0;
	
	
	var isEngageFeeder = 0;
	
	var process_type;
	
	$(document).ready(function() {
		
		
		$('.progress-bar').progressbar({
			display_text : 'fill'
		});

		
 	  $('#objects_table').dataTable({
			"aaSorting": []
		});
        
        
        
        /*
		* WIZARD
		*/
		var wizard = $('.wizard').wizard();

		$('#btn-next').on('click', function() {
			$('.wizard').wizard('next');
			check_wizard();
		});

		$('#btn-prev').on('click', function() {
			$('.wizard').wizard('previous');
			check_wizard();
		});

		$('.wizard').on('stepclick', function(e, data) {
			
			$('.wizard').wizard('selectedItem', { step: data.step });
			check_wizard();
		});
        
        
        
        
        $("#turn-off").on('change', function(){
            _controls_listener($(this));
        });
        
        

		<?php if(!$_running):?>
		$(".spinner").spinner();


		$('.carousel.slide').carousel({
			interval : 3000,
			cycle : true
		});
	
		

		/*
		* ACCODION
		*/
		var accordionIcons = {
            header: "fa fa-plus",    
		    activeHeader: "fa fa-minus" 
		};

		$(".accordion").accordion({
			autoHeight : false,
			heightStyle : "content",
			collapsible : true,
			animate : 300,
			icons: accordionIcons,
			header : "h4",
			active: false
		});


		
        /** PROCESS STL TO GCODE BUTTON */
        $('#process-button').on('click', function(){
            document.location.href = '<?php echo base_url("objectmanager/prepare") ?>/'+ process_type + '/' + object.object.id + '/' + file_selected.id + '?return=1' ;
        });

		
		/**
		* Print button action
		*/
		$('#print-button').on('click', function() {
			print_object();	
		});


		<?php endif; ?>

		$('#trace').on('click', function(){

			if($(this).is(':checked')){
				do_trace = true;
				interval_trace   = setInterval(_trace, 1000);
				$( '.trace' ).show( "fast" );
			}else{
				do_trace = false;
				$('.trace').hide("fast");
				_stop_trace();
			}

		});
        
        
        $('#details').on('click', function(){
         
            if(!do_trace){
                do_trace = true;
				interval_trace   = setInterval(_trace, 1000);
				/*$( '.details-container' ).show( "fast" );*/
                
                $( ".details-container" ).slideDown( "slow", function() {
                       
                });
                
                
                
            }else{
                do_trace = false;
				/*$('.details-container').hide("fast");*/
                $( ".details-container" ).slideUp( "slow", function() {
                       
                });
				_stop_trace();
            }   
            
            
        });
        
        
        
        $("#velocity").noUiSlider({
		        range: {'min': 0, 'max' : 500},
                /*range: [0, 500],*/
		        start: <?php echo $_velocity != '' ? $_velocity : 100 ?>,
		        handles: 1,
                connect: 'lower'
        });
        
        $("#temp1").noUiSlider({
		        range: {'min': 0, 'max' : 230},
                /*range: [0, 250],*/
		        start: <?php echo $ext_temp; ?>,
		        handles: 1,
                connect: 'lower'
        });
        
        $("#temp2").noUiSlider({
		        range: {'min': 0, 'max' : 100 },
                /*range: [0, 100],*/
                start: <?php echo $bed_temp; ?>,
		        handles: 1,
                connect: 'lower'
        });
        
     	$("#rpm").noUiSlider({
		        range: {'min': 6000, 'max' : 14000 },
                /*range: [0, 100],*/
                start: <?php echo $_rpm != '' ? $_rpm : 6000 ?>,
		        handles: 1,
                connect: 'lower'
        });
		
        
        $(".sliders").on({
		      slide: manage_slide,
              change: manage_change
	   });
        

		/**
		* Controls action (play, pause, stop, velocity, temperature) */
		
		$('.controls').on('click', function() {
			_controls_listener($(this));
		});
        
        
        $('#stop-button').on('click', ask_stop);

	
		
		<?php if($_running): ?>

		_resume();
        
        $('.wizard').wizard('selectedItem', { step: 4 });

		<?php endif; ?>
        
        <?php if($_request_file != FALSE && $_request_obj != FALSE && $_running == FALSE): ?>
                  
             /** IF I COME FROM OBJECT MANAGER */
            $( "#objects_table > tbody > tr" ).each(function() {
               
                if($(this).attr('data-id') == <?php echo $_request_obj?>){
                    $(this).trigger('click');
                }
               
            });
            
            $("#btn-next").trigger('click');          
        <?php endif; ?>
        
        /*
        editor = ace.edit("ace-editor");
        editor.getSession().setMode("ace/mode/text");
        editor.setReadOnly(true);
        */
        
        /** TICKER */
		
        interval_ticker   = setInterval(ticker, 2500);
		
        
	});
    

/** READ MACRO'S TRACE */    
function ticker(){
    
    if(ticker_url != ''){
    	
    	
    	
    	$.ajax({
			type: 'GET',
			url: ticker_url,
		}).done(function(data, statusText, xhr) {
			
			if(xhr.status == 200){
				data = data.replace("\n", "<br>");
				waitContent(data);
			
			}
			
		});	
    	
    	
    }
}


    
function manage_slide(e){
    
    var id = $(this).attr('id');
    
    
    
    
   switch(id){
   	
   	case 'velocity':
   		 $("#label-"+ id ).html('' + parseInt($(this).val()) + '%');
   		 break;
   	case 'temp1':
   		extruder_target = parseInt($(this).val());
   		$("#label-"+ id + '-target' ).html('' + parseInt($(this).val()) + '');
   		break;
   	case 'temp2':
   		bed_target = parseInt($(this).val());
   		$("#label-"+ id + '-target' ).html('' + parseInt($(this).val()) + '');
   		break;
   	case 'rpm':
   		$("#label-"+ id ).html('' + parseInt($(this).val()) + '');
   		break;
   }
    
    
    /*
    if(id == "velocity"){
    	
        $("#label-"+ id ).html('' + parseInt($(this).val()) + '%');
        
    }else{
    	
    	if(id == 'temp1'){
    		extruder_target = parseInt($(this).val());
    	}
    	
    	if(id == 'temp2'){
    		bed_target = parseInt($(this).val());
    	}
    	
        $("#label-"+ id + '-target' ).html('' + parseInt($(this).val()) + '');
    }
    */
    
}


function manage_change(e){
    
   	var action = $(this).attr('data-action');
	var value  = parseInt($(this).val());

	_do_action(action, value);

	if(action == 'stop'){
		_stop_monitor();
		_stop_timer();
		_stop_trace();
		stopped = 1;
		_update_task();
	}
    
}

</script>