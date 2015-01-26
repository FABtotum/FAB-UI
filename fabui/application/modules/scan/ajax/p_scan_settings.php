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
                    	
                        	<img id="plane" src="/fabui/application/modules/scan/assets/img/working_plane.png" />
                        
                        </div>
                        
                        <div class="col-sm-6">
                        	
                        	<div class="smart-form">
	    						<fieldset style="background: transparent;">
	    							<div class="row">
	    								<label class="label">
											Position (mm)
										</label>
	    								<section>
	    									<label class="input">
	    										<span class="icon-prepend label-x1">
	    											X1
	    										</span>
	    										<input class="coordinates" id="x1" type="text">
	    									</label>
	    								</section>
	    								<section class="y-container">
	    									<label class="input">
	    										<span class="icon-prepend">
	    											Y1
	    										</span>
	    										<input class="coordinates" id="y1" type="text">
	    									</label>
	    								</section>
	    								<section>
	    									<label class="input">
	    										<span class="icon-prepend label-x2">
	    											X2
	    										</span>
	    										<input class="coordinates" id="x2" type="text">
	    									</label>
	    								</section>
	    								<section class="y-container">
	    									<label class="input">
	    										<span class="icon-prepend">
	    											Y2
	    										</span>
	    										<input class="coordinates" id="y2" type="text">
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
                                <!--
								<label class="input">
									<input type="text" />
								</label>
                                -->
							</section>
							<section>
								<label class="label">
									Axis increment (number)
								</label>
								<label class="input">
									<input id="axis-increment" type="text" value="0" readonly="readonly"/>
								</label>
							</section>
							<div class="row">
								<section class="col col-6">
									<label class="label">
										Start degree
									</label>
									<label class="input">
										<input id="start-degree" type="text" value="0" readonly="readonly" />
									</label>
								</section>
								<section class="col col-6">
									<label class="label">
										End degree
									</label>
									<label class="input">
										<input id="end-degree" type="text" value="0" readonly="readonly" />
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


<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
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
var c = {"x":65,"y":64,"x2":152,"y2":164,"w":100,"h":100};
var maxsize = {'w':212, 'h':232};
var jcrop_api;     
/**  JCROP */  
$('#plane').Jcrop({
    bgFade: true,
   // trueSize: [maxsize.w, maxsize.h],
    //allowSelect: false,
    //setSelect: [c.x,c.y,c.x2,c.y2],
  
    onSelect: setCoords   
},function(){
    jcrop_api = this;
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


$("#probe-quality").val( 20, {
	set: true,
	animate: true
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

</script>