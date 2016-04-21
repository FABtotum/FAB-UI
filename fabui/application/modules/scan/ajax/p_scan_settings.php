<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-12">
				<h6 class="text-primary">Select scan area</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="well">
                    <div class="row">
                    	
                    	<div class="col-sm-6 text-center">
                    	
                        	<img width="223" height="235" id="plane" src="/fabui/application/modules/scan/assets/img/working_plane.png" />
                        
                        </div>
                        
                        <div class="col-sm-6">
                        	
                        	<div class="smart-form">
	    						<fieldset style="background: transparent;">
	    							
	    							<div class="row">
	    								<section class="col col-6">
	    									<label class="label">First point</label>
	    									<label class="input">
	    										<span class="icon-prepend">X</span>
	    										<input class="coordinates" id="x1" type="text">
	    									</label>
	    								</section>
	    								<section class="y-container col col-6">
	    									<label class="label">&nbsp;</label>
	    									<label class="input">
	    										<span class="icon-prepend">Y</span>
	    										<input class="coordinates" id="y1" type="text">
	    									</label>
	    								</section>
	    							</div>
	    							<div class="row">
	    								<section class="col col-6">
	    									<label class="label">Second point</label>
	    									<label class="input">
	    										<span class="icon-prepend">X</span>
	    										<input class="coordinates" id="x2" type="text">
	    									</label>
	    								</section>
	    								<section class="y-container col col-6">
	    									<label class="label">&nbsp;</label>
	    									<label class="input">
	    										<span class="icon-prepend">Y</span>
	    										<input class="coordinates" id="y2" type="text">
	    									</label>
	    								</section>
	    							</div>
	    							<div class="row">
	    								<section class="col col-6">
	    										<a style="padding: 6px 12px" class="btn btn-default btn-block btn-info go-origin" >Move to origin</a>
	    								</section>
	    								<section class="col col-6">
	    									<label class="input">
	    										<a style="padding: 6px 12px" class="btn btn-default btn-block btn-info test-area" >Test selected area</a>
	    									</label>
	    								</section>
	    							</div>
	    						</fieldset>
	    					</div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="row">
			<div class="col-sm-12">
				<h6 class="text-primary">Settings</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="well">
					<div class="smart-form">
						<fieldset style="background: none !important;">
							<section>
                                <div id="probe-quality" class="noUiSlider fab-slider"></div>
								<label class="label margin-top-10 probe-quality-label"> </label>
							</section>
							<div class="row">
								<section class="col col-6">
									<label class="label">Z Jump (mm)</label>
									<label class="input">
										<input class="" id="z_hop" type="number" max="10" min="1" value="1" step="0.5">
    								</label>
    								<p class="note">This is the maximum difference in height of the different portions of the object to probe</p>
								</section>
								<section class="col col-6">
									<label class="label">Detail treshold (mm)</label>
									<label class="input">
										<input class="" id="probe_skip" type="number" max="0.05" min="0" value="0" step="0.01">
    								</label>
    								<p class="note">if Z height change is minor than detail threshold adaptive autoskipping is automatically enabled. Lower values give finer details. 0 = disable</p>
								</section>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
            
		</div>
	</div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/database.php';
/** LOAD DB */
$db = new Database();
/** GET PROBE QUALITY FROM DB */
$_probe_qualities = $db->query('select * from sys_scan_configuration where type="probe_quality"');
$db->close();
?>


<script type="text/javascript">

var probe_quality_info = new Array();
<?php foreach($_probe_qualities as $quality): ?>
probe_quality_info[<?php echo $quality['id'] ?>] = <?php echo $quality['values']; ?>;
<?php  endforeach; ?>

/** PLANE COORDINATES */
X_MIN = 32;
Y_MAX = 168;
var jcrop_api;
var c = {"x":X_MIN,"y":Y_MIN,"x2":X_MAX,"y2":Y_MAX,"w":100,"h":100};

$(document).ready(function() {
	
	
	
	$("#z_hop").spinner({
		step :0.5,
		numberFormat : "n",
		min: 1,
		max: 10
	});
	
	$("#probe_skip").spinner({
		step :0.01,
		numberFormat : "n",
		min: 0,
		max: 0.05
	});
	
	
	
	
	
	
	/*  SCAN QUALITY SLIDER */
	$("#probe-quality").noUiSlider({
	    range: {'min': 20, 'max' : 120},
	    /*range: [20,120],*/
	    start: 20,
	    step: 20,
	    handles: 1,
	    connect: 'lower',
	    behaviour: 'tap-drag' 
	 });
	 
	 
	 /* SCAN QUALITY SLIDER EVENTS */
	$("#probe-quality").on({
		 slide: manageProbeSlide,
		 set: manageProbeSlide,
		 change: manageProbeSlide
	});
	
	
	$("#probe-quality").val( 40, {
		set: true,
		animate: true
	});
	

	     
	/**  JCROP */  
	$('#plane').Jcrop({
	    bgFade: true,
	    onSelect: setCoords,
	    onChange: debugCoords   
	},function(){
	    jcrop_api = this;
	    
		setTimeout(function() {
			jcrop_api.setSelect([c.x ,c.y, c.x2,c.y2]);
		}, 500);
	});
	
	POINT = c;
	
	$(".coordinates").on('change', changeCoordinates);
	$(".test-area").on('click', test_area);
	$(".go-origin").on('click', go_to_origin);
	
	
});





function manageProbeSlide(){
    
    var slide_val = parseInt($(this).val());
    
	switch (slide_val) {
		case 20:
			probe_quality = 9;
			break;
		case 40:
			probe_quality = 10;
			break;
		case 60:
			probe_quality = 11;
			break;
		case 80:
			probe_quality = 12;
			break;
		case 100:
			probe_quality = 13;
			break;
   	    case 120:
			probe_quality = 14;
			break;
		default:
			probe_quality = 9;

	}
    
    
    $(".probe-quality-label").html('Quality: <strong>' + probe_quality_info[probe_quality].info.name + '</strong> -  Probes per square millimiters: <strong>' + probe_quality_info[probe_quality].values.sqmm + '</strong>');
    
}






function changeCoordinates(){
	
	var coord = parseInt($(this).val());
	var id = $(this).attr('id');
	
	var actual_point = jcrop_api.tellSelect();
	
	switch(id){
		case 'x1':
			actual_point.x = coord + parseInt(X_MIN);
			break;
		case 'x2':
			actual_point.x2 = coord + parseInt(X_MIN);
			break;
		case 'y1':
			actual_point.y2 = Math.abs(coord - parseInt(Y_MAX));
			break;
		case 'y2':
			actual_point.y = Math.abs(coord - parseInt(Y_MAX));
			break;
	}
	
	setCoords(actual_point, true);
	
}


function test_area(){
	
	IS_MACRO_ON = true;
	var data = {x1: $("#x1").val(), y1: $("#y1").val(), x2: $("#x2").val(), y2:$("#y2").val(), skip: check_skip_homing};
	
	openWait("<i class='fa fa-circle-o-notch fa-spin'></i> Check area");
	
	$.ajax({
    		  type: "POST",
    		  url: check_area_url,
    		  dataType: 'json',
    		  asynch: true,
              data:data
    	}).done(function( response ) {     
            ticker_url = '';
            IS_MACRO_ON = false;
    		check_skip_homing = 1;
    		closeWait();
            
    	});
}


function go_to_origin(){
	
	var area = jcrop_api.tellSelect();
	var x=X_MIN, x2=0, y=0, y2 = Y_MAX;
	var x2 = (area.x2 - area.x) + X_MIN;
	area.x = x;
	area.x2 = x2;
	area.y = Y_MAX - (area.y2 - area.y) ;
	area.y2 = y2;
	setCoords(area, true);
	
}

</script>