
<script type="text/javascript">
/* SCAN MODE */
var scan_mode = <?php echo  $_task ? $_task_attributes['mode'] : 0 ?>;
/* SCAN MODE INFO */
var scan_mode_info = new Array();
/* INIT MODE INFO */
<?php foreach($mode_list as $mode): ?>
<?php $configuration = json_decode($mode->values); ?>
scan_mode_info['<?php echo $mode->id ?>'] = <?php echo json_encode($configuration->info) ?>;
<?php endforeach; ?>
/* SCAN QUALITY */ 
var scan_quality = <?php echo  $_task && isset($_task_attributes['quality'])  ? $_task_attributes['quality'] : 0 ?>;
/* SCAN QUALITY INFO */
var scan_quality_info = new Array();
scan_quality_info[0]  = {};
/* INIT QUALITY INFO */
<?php foreach($quality_list as $quality): ?>
<?php $configuration = json_decode($quality->values); ?>
scan_quality_info[<?php echo $quality->id ?>] = <?php echo json_encode($configuration) ?>;
<?php endforeach; ?> 
/* SCAN TASK INFO */
var task_id                 =  <?php echo  $_task  ? $_task['id'] : 0 ?>;
var scan_pid                =  <?php echo  $_task  ? $_task_attributes['scan_pid'] : 0 ?>;
var scan_monitor_file       = '<?php echo  $_task  ? $_task_attributes['folder'].$_task_attributes['scan_monitor'] : '' ?>';
var scan_monitor_folder     = '<?php echo  $_task  ? $_task_attributes['folder'] : '' ?>';
var scan_uri                = '<?php echo  $_task && isset($_task_attributes['scan_uri']) ? $_task_attributes['scan_uri'] : '' ?>';
var pprocess_pid            =  <?php echo  $_task && isset($_task_attributes['pprocess_pid'])? $_task_attributes['pprocess_pid'] : 0 ?>;
var pprocess_monitor_file   = '<?php echo  $_task && isset($_task_attributes['pprocess_monitor']) ? $_task_attributes['folder'].$_task_attributes['pprocess_monitor'] : '' ?>';
var pprocess_monitor_folder = '<?php echo  $_task  ? $_task_attributes['folder'] : '' ?>';
/* MONITORING SCAN INFO */
var scan_finished            = <?php echo  $_task && is_array($_scan_monitor)  ? $_scan_monitor['scan']['completed'] == 1 ? 'true' : 'false' : 'false' ?>;
var scan_monitor_timeout     = 5000; 
var scan_elapsed_time        =  <?php echo  $_task  ? (time() - intval($_monitor_attributes->scan->started)) : 0 ?>;
var scan_estimated_time_left = 0;
var scan_monitor_response;
var scan_progress_step;
var scan_second_for_step;
var scan_array_progress_steps = new Array();
var scan_array_estimated_time = new Array();
var scan_stats_file = '<?php echo $_task && isset($_task_attributes['scan_stats_file']) ? $_task_attributes['scan_stats_file'] : '' ?>';
<?php 
if($_task && isset($_scan_stats['progress_steps'])){
	foreach($_scan_stats['progress_steps'] as $step){
		if(is_numeric ($step)){
			echo 'scan_array_progress_steps.push('.$step.');'.PHP_EOL;
		}
	}
	foreach($_scan_stats['estimated_time'] as $time){
		if(is_numeric($time)){
			echo 'scan_array_estimated_time.push('.$time.');'.PHP_EOL;
		}
	}
}
?>
var scan_image_counter        = 1;
var scan_image                = <?php echo 0; //echo  $_task  ?  $_task_attributes->scan_image != '' ? $_task_attributes->scan_image : 0  : 0 ?>;
/* MONITORING PPROCESS INFO */
var pprocess_finished = <?php echo  $_task && isset($_pprocess_monitor['post_processing']['completed'])  ? $_pprocess_monitor['post_processing']['completed'] == 1 ? 'true' : 'false' : 'false' ?>;
var pprocess_monitor_timeout = 5000;
var pprocess_interval_monitor;
var pprocess_elapsed_time = 0;
var pprocess_monitor_response = 0;
var pprocess_progress_step;
var pprocess_second_for_step;
var pprocess_array_progress_steps = new Array();
var pprocess_array_estimated_time = new Array();
var pprocess_stats_file = '<?php echo $_task && isset($_task_attributes['pprocess_stats_file']) ? $_task_attributes['pprocess_stats_file'] : '' ?>';
<?php 
if($_task && isset($_pprocess_stats['progress_steps'])){
	foreach($_pprocess_stats['progress_steps'] as $step){
		if(is_numeric($step)){
			echo 'pprocess_array_progress_steps.push('.$step.');'.PHP_EOL;
		}
	}
	foreach($_pprocess_stats['estimated_time'] as $time){
		if(is_numeric($time)){
			echo 'pprocess_array_estimated_time.push('.$time.');'.PHP_EOL;
		}
	}
}
?>
/* MONITORING MESH 
var mesh_interval_monitor;
var mesh_monitor_file = '<?php echo  $_task && isset($_task_attributes->mesh_monitor) ? $_task_attributes->folder.$_task_attributes->mesh_monitor : '' ?>';
var mesh_monitor_timeout = 1000;
var mesh_pid  = '<?php echo  $_task && isset($_task_attributes->mesh_pid)  ? $_task_attributes->mesh_pid : 0 ?>';
var mesh_interval_pid_check;
var mesh_finished = <?php echo 'false'; //echo  $_task  ? $_task_attributes->mesh_completed == 1 ? 'true' : 'false' : 'false' ?>;
*/
/* GENERAL SETTINGS */
var interval_timer; 
var precision        = 3;
var current_step     = <?php  echo $_task && isset($_task_attributes['step']) ? $_task_attributes['step'] : 0?>;
var monitor_count    = 0;
var monitor_timeout  = 1000;
var interval_monitor;
var interval_update;
var estimated_time_left = 0;
var total_array_estimated_time = new Array();
var total_array_progress_steps = new Array();
var total_current_estimated_time = 0;
var elapsed_time = <?php echo  $_task  ? (time() - intval($_scan_monitor['scan']['started'])) : 0 ?>;

/** PROBING */
var probing_trace_file;
var ISPROBING = <?php echo $_task && $_task_attributes['mode'] == 8 ? 'true' : 'false' ?>;
var x1;
var x2;
var y1;
var y2;
var density;
var axis_increment;
var start_degree;
var end_degree;
var probe_quality = '';


/** S SCAN */
var a_offset;

var check_pre_scan_url = '<?php echo module_url('scan').'ajax/check_pre_scan.php' ?>';
var pre_scan_url     = '<?php echo module_url('scan').'ajax/pre_scan.php' ?>';
var macro_r_scan_url = '<?php echo module_url('scan').'ajax/macro_r_scan.php' ?>';
var macro_s_scan_url = '<?php echo module_url('scan').'ajax/macro_s_scan.php' ?>';
var macro_p_scan_url = '<?php echo module_url('scan').'ajax/macro_p_scan.php' ?>';

/** NEED FOR JCROP API */
var jcrop_api;

var interval_stop;
var elapsed_time_stop = 0;
var max_time_stop = 5;


/** TICKER */
var ticker_url = '';
var interval_ticker;

/* OBJECT **/
var obj_id      = 0;
var obj_name    = '';
var new_object  = true;
var id_asc_file = 0;

$(document).ready(function() {
	  
	/*
	* WIZARD
	*/
	var wizard = $('.wizard').wizard();
	
	/** ON START NEXT DISABLED */
	$('#btn-next').addClass('disabled');

	$('#btn-next').on('click', function() {
	   
        var item = $('.wizard').wizard('selectedItem');
        
        if(item.step == 1){
            if(new_object == false && obj_id == ''){
                
                $('.object-select').addClass('error');
                $('.object-select').focus();
                return false;
            }
        }
        
		$('.wizard').wizard('next');
		check_wizard();
	});
    
    
    /** OBJECTS SELECT */
    $('.object-select').on('change', function(){
        $('.object-select').removeClass('error');
        obj_id = $(this).val();
        
    });

	$('#btn-prev').on('click', function() {
		$('.wizard').wizard('previous');
		check_wizard();
	});

	$('.wizard').on('stepclick', function(e, data) {
		
		$('.wizard').wizard('selectedItem', { step: data.step });
		check_wizard();
	});


    /** HOVER SCAN MODE SELECTION EFFECT */
    $('.scan-mode').hover(function() {
        var obj = $(this);

        obj.find('img').addClass('sfumatura');
        obj.find('.mode-description').slideDown('fast');

      }, function() {
        
        var obj = $(this);
        obj.find('img').removeClass('sfumatura');
        obj.find('.mode-description').slideUp('fast');
      }
    );

	/* SET SCAN MODE */
	$('.scan-mode').on('click', function() {

		$('.scan-mode').removeClass('my-selected');
		$(this).addClass('my-selected');
		
		scan_mode = parseInt($(this).attr('data-id'));
        
        var instruction_page;
        var settings_page;
        
        switch(scan_mode){
            
            case 6 :/** ROTATING */
                instruction_page = 'r_scan.php';
                settings_page    = 'r_scan_settings.php';
                $('#pprocess-progress-container').show();
                $('#images-container').show();
                $('#images-container').removeClass().addClass('col-sm-6');
                $('#console-container').removeClass().addClass('col-sm-6');
                
                break;
            case 7 :/** SWEEP */
                instruction_page = 's_scan.php';
                settings_page    = 's_scan_settings.php';
                $('#pprocess-progress-container').show();
                $('#images-container').show();
                $('#images-container').removeClass().addClass('col-sm-6');
                $('#console-container').removeClass().addClass('col-sm-6');
                break;
            case 8 :/** PROBING */
                instruction_page = 'p_scan.php';
                settings_page    = 'p_scan_settings.php';
                $('#pprocess-progress-container').hide();
                $('#images-container').hide();
                $('#console-container').removeClass().addClass('col-sm-12');
                ISPROBING = true;
                break;
        }
        
        
        /** LOAD PRE-SCAN SETTINGS */
        $.ajax({
          url: "<?php echo module_url('scan') ?>ajax/" + settings_page,
          cache: false
        }).done(function( html ) {
            
            $("#step2").html(html);
            
        });
        
        
        /** LOAD INSTRUCTIONS */
        $.ajax({
          url: "<?php echo module_url('scan') ?>ajax/" + instruction_page,
          cache: false
        }).done(function( html ) {
            
             $("#step3").html(html);
        });

		/** WHEN A SCAN MODE IS SELECTED IS POSSIBLE TO CONTINUE */
		$("#btn-next").removeClass('disabled');
		
	});
    
    
    
    
    $("input[name=radio-object]").on('click', function() {

        if($(this).val() == 'new'){
            $('.object-select').hide();
            new_object = true;
            $('#name-object').show();
        }else{
           $('.object-select').show();
           new_object = false;
           $('.object-select').val(''); 
           obj_id = '';
           $('#name-object').hide();
           

        }
        
        
    });
    
    
    /** ADD SCAN TO EXISTING OBJ */
    $(".add-scan").on('click', function () {
        document.location.href = '<?php echo site_url('scan') ?>' + '?obj='+ obj_id ;
    });
    
    
    /** RECONSTRUCTION */
    $(".reconstruction").on('click', function(){
       document.location.href="<?php echo site_url("objectmanager/prepare/asc")?>/" + obj_id  + '/' + id_asc_file;
    });
    
    /** MERGE */
    $(".merge").on('click', function(){
       document.location.href="<?php echo site_url("objectmanager/prepare/merge")?>/" + obj_id  + '/' + id_asc_file;
    });

	
    $("#print-object").on('click', print_object);
	$("#view-object").on('click',  view_object);
    
    $("#stop-button").on('click', ask_stop);


	<?php if($_task): ?>

	_resume();

	<?php endif; ?>
    
    
    
    interval_ticker   = setInterval(ticker, 2500);
    
    
    <?php if(isset($_REQUEST['obj']) && $_REQUEST['obj'] != ''){ ?>
    
        $("input[name=radio-object]").trigger('click');
        
        obj_id = <?php echo $_REQUEST['obj']; ?>;
        new_object = false;
        
        $('.object-select').val(<?php echo $_REQUEST['obj']; ?>);
    
    <?php } ?>
    

});


/** READ MACRO'S TRACE */    
function ticker(){
    
    if(ticker_url != ''){
        
         $.get( ticker_url , function( data ) {
            
            if(data != ''){
                data = data.replace("\n", "<br>");
                /*data = data.replace('<?php echo PHP_EOL; ?>', '<br>');*/
                waitContent(data);
 
            }
       });
    }
}


function setCoords(c)
{
    x1 = c.x;
    y1 = c.y;
    x2 = c.x2;
    y2 = c.y2;
    
    
    
   
    
    var y_max = 235;
    
    y1 = y_max - y1;
    y2 = y_max - y2;
    
    if(x1 < 32 ){
    	x1 = 32;
    	jcrop_api.setSelect([x1 ,y1, x2,y2]);
    	return;
    }
    
    
    if(y2 < 66){
    	y2 = 66;
    	jcrop_api.setSelect([x1 ,y1, x2,y2]);
    	return;
    }
    
 
    if(y1 >175){
    	
    	y1 = 175;
    	jcrop_api.setSelect([x1 ,y1, x2,y2]);
    	return;
    }
    
    
    $('#x1').val(x1);
    $('#y1').val(y2);
    $('#x2').val(x2);
    $('#y2').val(y1);
   
};



/* SCAN QUALITY SLIDER ENVENTS HANDLER */
function manageSlide(e){
	
	var slide_val = parseInt($(this).val());

	var quality = '';

	
	switch (slide_val) {
		case 0:
			scan_quality = 0;
		break;
		case 20:
			scan_quality = 1;
			break;
		case 40:
			scan_quality = 2;
			break;
		case 60:
			scan_quality = 3;
			break;
		case 80:
			scan_quality = 4;
			break;
		case 100:
			scan_quality = 5;
			break;
		default:
			scan_quality = 1;

	}
    
    
    
    
	var src = '<?php echo base_url() ?>/application/modules/scan/assets/img/duck' + scan_quality + '.png';
	
	$('.img-quality-container').attr('src',src); 
	
    if(slide_val > 0){
        
	   $('#quality').html(scan_quality_info[scan_quality].info.name);
	   $('#quality-description').html(scan_quality_info[scan_quality].info.description);
	}
};


 
/**
 * 
 */
function check_wizard(){
	
	
	

	var item = $('.wizard').wizard('selectedItem');

	$('#btn-next').show();
	
	if(item.step > 1){
		$('#btn-prev').removeClass('disabled');
	}

	if(item.step == 1){
		$('#btn-prev').addClass('disabled');
	}


	if(item.step == 3){
		
		$('#btn-next').hide();
		
	}
	
	if(item.step >= 4){
		$("#wizard-buttons").hide();
	}
	
	
	

};






/**
 *  START SCAN & PPROCESS
 */
function start(){
    
    a_offset =  $("#a_offset").val();
    
    axis_increment = $("#axis-increment").val();
    start_degree = $("#start-degree").val();
    end_degree = $("#end-degree").val();
    
    
    
    
    
	$.ajax({
		  type: "POST",
		  url: "<?php echo site_url('scan/start') ?>", 
		  data: { mode: scan_mode, new_object: new_object, obj_id : obj_id, obj_name : $("#name-object").val(),  quality: scan_quality, x1: x1, x2: x2, y1:y1, y2:y2, axis_increment: axis_increment, start_degree: start_degree, end_degree: end_degree, density:density, a_offset:a_offset, probe_quality : probe_quality },
		  dataType: 'json',
		  asynch: true,
		  beforeSend: function() {
			    openWait('Starting scan..');
	      }
	}).done(function( response ) {

		task_id            = response.task_id;
		scan_monitor_file  = response.scan_monitor_file;
		monitor_folder     = response.folder;
		scan_uri           = response.scan_uri;
		scan_pid           = response.scan_pid;
        probing_trace_file = response.probing_trace_file;
        scan_stats_file    = response.scan_stats_file;
        
        /** PPROCESS ONLY FOR SCAN MODE 6 - 7 (rotating, sweep) */
        if(!ISPROBING){
            
            pprocess_monitor_file = response.pprocess_monitor_file;
            pprocess_pid          = response.pprocess_pid;
            pprocess_stats_file   = response.pprocess_stats_file;
            
            $('.scan-quality-label').html(scan_quality_info[scan_quality].info.name);
            $('.slices-label').html(scan_quality_info[scan_quality].values.slices);
            $('.iso-label').html(scan_quality_info[scan_quality].values.iso);
            $('.img-resolution-label').html(scan_quality_info[scan_quality].values.resolution.width + ' X ' + scan_quality_info[scan_quality].values.resolution.height);

        }
        
		/* SCAN LABELS */
		$('.scan-mode-label').html(scan_mode_info[scan_mode].name);
		 

		/* START MONITOR */
		interval_monitor     = setInterval(print_monitor, monitor_timeout); /* SCAN MONITOR */
		interval_timer       = setInterval(_timer, 1000); /* START TIMER... */
        interval_update      = setInterval(_update_task, 5000);
        
        
        
		freeze_menu('scan');
		current_step = 4;
		$('#btn-next').trigger('click');
        $("#stop-button").removeClass('disabled');
		closeWait();
		    
	});
	
};

/**
 *  DO SCAN MONITOR
 */
var print_monitor = function (){

   
	if(!scan_finished || (!ISPROBING && !pprocess_finished)){
		monitor_call();
                     	
	}else{ 

		/*se � finita, termino monitor*/
		_stop_monitor();
		_stop_timer();

		current_step = 5;
        
		$('.progress').removeClass('active');
		$('.estimated-time').html('-');
		$('.estimated-time-left').html('-');
        
        openWait('Finalizing scan');
        
        setTimeout(function(){
            get_info();
            
            /** get all info from the task */
        }, 15000);
	}

};


/** GET ALL INFO FROM THE TASK WHEN IS COMPLETED */
function get_info(){
    
    
    $.ajax({
		url : '<?php echo module_url('scan').'ajax/info.php' ?>',
		data : {task_id: task_id},
		type : 'post',
		dataType : 'json',
	}).done(function(response) {
        
        obj_id      = response.id_obj;
        id_asc_file = response.id_file;
        
        $(".download-scan").attr('href', '<?php echo site_url('objectmanager/download') ?>' + '/' + id_asc_file);
        
        $('#btn-next').trigger('click');
        closeWait();
	});
    
    
}


/**
 *  SCAN MONITOR
 */
function monitor_call(){


	/*se la scansione non è finita*/
	if(!scan_finished || (!ISPROBING && !pprocess_finished)){	
	
		$.ajax({
              /*url : '<?php echo site_url('scan/monitor') ?>',*/
              url : '<?php echo module_url('scan').'ajax/monitor.php' ?>',
			  dataType : 'json',
			  type: 'post',
			  async : true,
			  data : {task_id: task_id, scan_monitor_file: scan_monitor_file, pprocess_monitor_file: pprocess_monitor_file, isprobing: ISPROBING }
		}).done(function(response) {
			
           /* try{ */ 
                
                if(response.scan == null){
                    
                    /*stop_scan('An error occured.. stopping scan');*/
                    /*console.log('scan null');*/
                    return;
                }
                
    			monitor_count++;
    			scan_monitor_response     = response.scan;
                
    			var scan_progress_percent = parseFloat(scan_monitor_response.scan.stats.percent);
    
    			/* SCAN  */
    			$('#lines-progress').attr('style', 'width:' +scan_progress_percent + '%');
    			$('#lines-progress').attr('aria-valuetransitiongoal', scan_progress_percent);
    			$('#lines-progress').attr('aria-valuenow', scan_progress_percent);
    			$('.progress-status').html(number_format(scan_progress_percent, 2, ',', '.') + ' %');
                
    
    
                 if(!ISPROBING){
                    
                    
                    if(response.pprocess == null){
                        /*stop_scan('An error occured.. stopping scan');*/
                        
                        
                        /*console.log('pprocess null');*/
                        return;
                    }
                    
                    pprocess_monitor_response = response.pprocess;
                    var pprocess_progress_percent = parseFloat(pprocess_monitor_response.post_processing.stats.percent);
                    
                    /* PPROCESS  */
        			$('#pprocess-lines-progress').attr('style', 'width:' + pprocess_progress_percent + '%');
        			$('#pprocess-lines-progress').attr('aria-valuetransitiongoal',  pprocess_progress_percent);
        			$('#pprocess-lines-progress').attr('aria-valuenow', pprocess_progress_percent);
        			$('.pprocess-progress-status').html(number_format(pprocess_progress_percent, 2, ',', '.') + ' %');
                    
                    
                }
                
    			/*se il numero dell'immagine � minore al numero degli slice totali mostro l'immagine, evito che di caricare un immagine non esistente
    			if(parseInt(scan_monitor_response.scan.stats.img_number) <= parseInt(scan_quality_info[scan_quality].values.slices)){
    				
    
    				for(var i=scan_image_counter;i<scan_monitor_response.scan.stats.img_number; i++){
    					
    					var src_laser = scan_uri + 'images/'+ i +'_l.png';
    					var new_laser_image = $('<div class="scan-preview"><a class="image-link-laser" href="'+ src_laser + '"><img src="' + src_laser +'" data-img="'+src_laser+'"  class="superbox-img"></a></div>');
    					$('.laser').append(new_laser_image);
    				}
    
    				
    				scan_image_counter = scan_monitor_response.scan.stats.img_number;
    			
    				scan_image = scan_monitor_response.scan.stats.img_number; 
    
    				_init_gallery('.image-link-laser');
    				
    				
    			}*/
    
    
    
    			/* CHECK IF SCAN IS COMPLETED */
    			if(scan_monitor_response.scan.completed == 1){
    				scan_finished = true;	
    			}
                
                
                if(!ISPROBING){
                    
                    /* CHECK IF POST-PROCESSING IS COMPLETED */
                    /*console.log("check if pprocess is finished");*/
        			if(pprocess_monitor_response.post_processing.completed == 1){
        				pprocess_finished = true;	
        			}
                }
    			
    
    
    
    			/* AL PRIMO GIRO */
    			if(monitor_count == 1){
    
    				/* SCAN */
    				scan_progress_step   = precise_round(scan_monitor_response.scan.stats.percent, precision);
    				scan_second_for_step = elapsed_time;
                    
                    if(!ISPROBING){
                        /* POST-PROCESS */
        				pprocess_progress_step   = precise_round(pprocess_monitor_response.post_processing.stats.percent, precision);
        				pprocess_second_for_step = elapsed_time;
                    }
    				
    
    				$('.estimated-time').html(' - ');
    				$('.estimated-time-left').html(' - ');
    			
    			}
    
    			/**
    			* CALCULING ESTIMATED TIME LEFT SCAN
    			**/
    			/* se cambia la percentuale verifico di quanta � cambiata ne calcolo il tempo e faccio una stima di quanto ci si mette a completare tutto al 100% */
    			if(scan_progress_step != precise_round(scan_monitor_response.scan.stats.percent, precision)){
    
    				var second_for_this_step            = (elapsed_time - scan_second_for_step);
    				var progress_for_this_step          = precise_round(Math.abs(precise_round(scan_monitor_response.scan.stats.percent, 2) - scan_progress_step), precision);
    				var estimated_seconds_for_all_steps = precise_round(parseFloat((second_for_this_step * 100) / progress_for_this_step), precision);
    
    				/* calcolo la media ponderata per la stima del tempo totale di scansione */
    				
    				if(!isNaN(progress_for_this_step)){
    					scan_array_progress_steps.push(progress_for_this_step);
    				}
    
    
    				var scan_estimated_time = precise_round(Math.abs(estimated_seconds_for_all_steps * progress_for_this_step), precision);
    
    				if(!isNaN(scan_estimated_time)){
    					scan_array_estimated_time.push(scan_estimated_time);
    				}
    				
    				scan_current_estimated_time = precise_round(parseFloat((eval(scan_array_estimated_time.join('+')))/(eval(scan_array_progress_steps.join('+')))), 0);
    
    				scan_estimated_time_left = (parseInt(scan_current_estimated_time) - parseInt(elapsed_time)); /* stima secondi rimasti = stima secondi totali - elapsed_time dall'inizio della scansione */
    				scan_estimated_time_left = Math.abs(scan_estimated_time_left);
    
    				scan_progress_step   = precise_round(scan_monitor_response.scan.stats.percent, precision);
    				scan_second_for_step = elapsed_time; 
    
    			}
    
                
                if(!ISPROBING){
        			/**
        			* CALCULING ESTIMATED TIME LEFT POST PROCESSING
        			**/
        			/* se cambia la percentuale verifico di quanta � cambiata ne calcolo il tempo e faccio una stima di quanto ci si mette a completare tutto al 100% */
        			if(pprocess_progress_step != precise_round(pprocess_monitor_response.post_processing.stats.percent, precision)){
        
        				var second_for_this_step            = (elapsed_time - pprocess_second_for_step);
        				var progress_for_this_step          = precise_round(Math.abs(precise_round(pprocess_monitor_response.post_processing.stats.percent, 2) - pprocess_progress_step), precision);
        				var estimated_seconds_for_all_steps = precise_round(parseFloat((second_for_this_step * 100) / progress_for_this_step), precision);
        
        				/* calcolo la media ponderata per la stima del tempo totale di scansione */
        				if(!isNaN(progress_for_this_step)){
        					pprocess_array_progress_steps.push(progress_for_this_step);
        				}
        				
        				var pprocess_estimated_time = precise_round(Math.abs(estimated_seconds_for_all_steps * progress_for_this_step), precision);
        
        				if(!isNaN(pprocess_estimated_time)){
        					pprocess_array_estimated_time.push(pprocess_estimated_time);
        				}
        				/*pprocess_array_estimated_time.push(precise_round(Math.abs(estimated_seconds_for_all_steps * progress_for_this_step), precision));*/
        
        				pprocess_current_estimated_time = precise_round(parseFloat((eval(scan_array_estimated_time.join('+')))/(eval(scan_array_progress_steps.join('+')))), 0);
        
        				pprocess_estimated_time_left = (parseInt(pprocess_current_estimated_time) - parseInt(elapsed_time)); /*stima secondi rimasti = stima secondi totali - elapsed_time dall'inizio della scansione*/
        				pprocess_estimated_time_left = Math.abs(pprocess_estimated_time_left);
        							
        				pprocess_progress_step   = precise_round(pprocess_monitor_response.post_processing.stats.percent, precision);
        				pprocess_second_for_step = elapsed_time; 
        
        			}
                }
    
    
    
    			/** GLOBAL ESTIMATED TIME LEFT */
    
    			if(monitor_count > 1){
    
    
    				var _array_temp_time = new Array(0);
    				var _array_temp_step = new Array(0);
    
    				if(scan_finished == false){
    					
    					_array_temp_time.concat(scan_array_estimated_time);
    					_array_temp_step.concat(scan_array_progress_steps);
    					
    				}
    
    				if(pprocess_finished == false){
    
    					_array_temp_time.concat(pprocess_array_estimated_time);
    					_array_temp_step.concat(pprocess_array_progress_steps);
    				}
    					
    				total_array_estimated_time = scan_array_estimated_time.concat(pprocess_array_estimated_time);
    
    				total_array_progress_steps = scan_array_progress_steps.concat(pprocess_array_progress_steps);
    				
    				total_current_estimated_time = precise_round(parseFloat((eval(total_array_estimated_time.join('+')))/(eval(total_array_progress_steps.join('+')))), 0);
    
    				estimated_time_left = (parseInt(total_current_estimated_time) - parseInt(elapsed_time));
    				estimated_time_left = Math.abs(estimated_time_left);
    				
                    if(!isNaN(total_current_estimated_time)){
                        $('.estimated-time').html(_time_to_string(total_current_estimated_time));
                    }
    				
    			}

            
            
			
		});


	}

};



/**
 * 
 */
function _timer() {
	
	/**
	 * ELAPSED TIME
	 */
	 
	elapsed_time = (parseInt(elapsed_time) + 1);
	$('.elapsed-time').html(_time_to_string(elapsed_time));


	/**
	 * TIME LEFT
	 */
	if (!isNaN(estimated_time_left)) {
		estimated_time_left = (parseInt(estimated_time_left) - 1);

		if(scan_estimated_time_left > 0){
			$('.estimated-time-left').html(_time_to_string(estimated_time_left));
		}else{
			$('.estimated-time-left').html(' - ');
		}

	}
};


/**
 * 
 */

function _stop_monitor(){
	clearInterval(interval_monitor);
    clearInterval(interval_update);
    	
};

function _stop_timer(){
	clearInterval(interval_timer);
};

/***
 * 
 */
function _update_task(){

	var _async = true;
    
	$.ajax({
		url : '<?php echo module_url('scan').'ajax/update.php' ?>',
		data : {
			task_id                 : task_id,
            scan_monitor_file       : scan_monitor_folder + scan_monitor_file,
            pprocess_monitor_file   : scan_monitor_folder + pprocess_monitor_file,
            scan_array_progress_steps : scan_array_progress_steps,
            scan_array_estimated_time : scan_array_estimated_time,
            scan_stats_file           : scan_stats_file,
            pprocess_stats_file       : pprocess_stats_file,
            pprocess_array_progress_steps : pprocess_array_progress_steps,
            pprocess_array_estimated_time : pprocess_array_estimated_time,
			/*scan_completed          : scan_monitor_response.scan.completed,
			pprocess_completed      : pprocess_monitor_response.post_processing.completed,*/
			/*mesh_completed          : mesh_finished,*/
			step                    : current_step
		},
		type : 'post',
		dataType : 'json',
		async : _async,
	}).done(function(response) {

	});
};


/**
 * RESUME SCAN
 */


 function _resume(){
	 	
		monitor_count = 1;
		if(current_step <= 4){
			interval_monitor = setInterval(print_monitor, monitor_timeout);
			interval_timer   = setInterval(_timer, 1000);
		}
		if(current_step == 5){
			mesh_interval_monitor   = setInterval(_mesh_detail, mesh_monitor_timeout);
			mesh_interval_pid_check = setInterval(_mesh_pid_check, mesh_monitor_timeout);	
		}
		_resume_images(1, scan_image, scan_uri);
		_init_gallery('.image-link-laser');
		$('.wizard').wizard('selectedItem', { step: current_step });		
};



/**
 *  INIT GALLERY
 */
function _init_gallery(element){

	$(element).magnificPopup({
		type:'image', 
		gallery: {
	    	enabled: true 
	  	},
	  	retina: {
	  	    ratio: 2
	  	},
	  	removalDelay: 300,
	  	mainClass: 'mfp-fade',
	  	image: {
            markup: '<div class="mfp-figure">'+
	  	            '<div class="mfp-close"></div>'+
	  	            '<div class="mfp-img"></div>'+
	  	            '<div class="mfp-bottom-bar">'+
	  	              '<div class="mfp-title"></div>'+
	  	              '<div class="mfp-counter"></div>'+
	  	            '</div>'+
	  	          '</div>',

            cursor: null, 
	  	    titleSrc: 'title', 
	  	    verticalFit: true,
	  	    tError: '<a href="%url%">The image</a> could not be loaded.'
	  	}
	});
	
};


/**
 * 
 */
function _resume_images(start, end, uri){

	for(var i = start; i <= end; i++){

		var src = uri + 'images/'+ i +'_l.png';
		var new_image = $('<div class="scan-preview"><a class="image-link-laser" href="'+ src + '"><img src="' + src +'" data-img="'+src+'"  class="superbox-img"></a></div>');
		$('.laser').append(new_image);
		
	}
	
};


/**
 * 
 */
 /*
function _mesh(){

	
	$('#btn-next').trigger('click');
	current_step = 5;
	_start_mesh();
	
};
*/

/**
 * 
 */
 /*
function _start_mesh(){

	
	$.ajax({
		  type: "POST",
		  url: "<?php echo site_url('scan/mesh') ?>", 
		  data: {task_id: task_id},
		  dataType: 'json'
	}).done(function( response ) {

		mesh_pid                = response.mesh_pid; 
		mesh_monitor_file       = response.mesh_monitor_file;
		mesh_interval_monitor   = setInterval(_mesh_detail, mesh_monitor_timeout);
		mesh_interval_pid_check = setInterval(_mesh_pid_check, mesh_monitor_timeout);
		
		    
	});

	
};
*/


/**
 * 
 */
 /*
function _mesh_detail(){

	$.ajax({
		  url: scan_uri +  mesh_monitor_file,
		  data: {task_id: task_id, mesh_monitor_file: mesh_monitor_file},
		  dataType: 'text'
	}).done(function( data ) {
		$("#mesh-detail").html(data);
		$('#mesh-detail').scrollTop($('#mesh-detail')[0].scrollHeight); 
	});
	
};
*/

/**
 * 
 */
 /*
function _mesh_pid_check(){

	$.ajax({
		  type: "POST",
		  url: "<?php echo site_url('scan/pid_check') ?>", 
		  data: {pid: mesh_pid},
		  dataType: 'json'
	}).done(function( response ) {

		if(parseInt(response.exist) == 0){
			_stop_mesh();
			
			
		}
		
		
	});
	
};
*/

/**
 * 
 */
 /*
function _stop_mesh(){

	clearInterval(mesh_interval_monitor);
	clearInterval(mesh_interval_pid_check);

	$('#btn-next').trigger('click');
	current_step = 6;
	mesh_finished = true;
	
	_do_object();
	obj_created = true;
	_update_task();

};
*/

/**
 * 
 */
function _do_object(){


	if(obj_created == false){
		$.ajax({
			  type: "POST",
			  url: "<?php echo site_url('scan/object') ?>", 
			  data: {task_id: task_id},
			  dataType: 'json',
			  beforeSend: function(  ) {
				    openWait('Saving file and object creating...');
		      }
		}).done(function( response ) {

			obj_created = true;
			obj_id = response.obj_id;

			closeWait();
			
		});
	}
};



/**
 * 
 */
function view_object(){
	document.location.href = '<?php echo site_url('objectmanager/edit') ?>/' + obj_id;
};


/**
 * 
 */
function print_object(){
	document.location.href = '<?php echo site_url('create') ?>?' + obj_id;
};



/**
*  
* ASK STOP
*
*/

function ask_stop() {

	$.SmartMessageBox({
		title: "Attention!",
		content: "Stop scan ?",
		buttons: '[No][Yes]'
	}, function(ButtonPressed) {
	   
		if (ButtonPressed === "Yes") {
		  
			stop_scan('Stopping scan');
		}
		if (ButtonPressed === "No") {

		}

	});

}


/**
*
*/
function stop_scan(message){
    
    openWait(message);
    _stop_monitor();
    _stop_timer();
    
    $.ajax({
		  type: "POST",
		  /*url: "<?php echo site_url('scan/stop') ?>",*/
          url: "<?php echo module_url('scan').'ajax/stop.php' ?>", 
		  data: {task_id: task_id},
	      dataType: 'json'
		}).done(function( data ) {
		  
          if(data.status == 'ok'){
                setTimeout(function(){document.location.href = '<?php echo site_url('scan') ?>';}, 1000); 
          }			
    });
    
}

function _stopper(){
	elapsed_time_stop = (parseInt(elapsed_time_stop) + 1);
    if(elapsed_time_stop == max_time_stop){
        document.location.href = '<?php echo site_url('scan') ?>';
    }
}
</script>
