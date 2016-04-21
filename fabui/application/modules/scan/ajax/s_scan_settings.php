<div class="row">
    <div class="col-sm-6">
    	<h2 class="text-primary">
    		Select scan quality and area
    	</h2>
    </div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="well well-light">
			<div class="row margin-bottom-10" style="margin-top: 10px;">
				<div class="col-sm-6">
					<div class="text-center img-quality-container margin-bottom-10">
						<img class="img-responsive img-quality-container" style="display: inline; max-width: 200px;" src="../application/modules/scan/assets/img/duck0.png">
					</div>
					
				</div>
				
				<div class="col-sm-6 stats-well">
					<p>Quality Parameters</p>
					<hr>					
					<p class="scan">Slices: <span class="quality-slices pull-right"></span></p>
					<p class="scan">Resolution: <span class="quality-resolution pull-right"></span></p>
					<p class="scan">Iso: <span class="quality-iso pull-right"></span></p>
					<hr>
				</div>
				
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div id="scan-quality" class="noUiSlider fab-slider"></div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<h5>
						Quality:
						<strong><span id="quality"></span></strong>
					</h5>
					<p id="quality-description">
					</p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-sm-6">
    
         <div class="well well-light">
         
            <div class="row text-center">
                <img id="plane" src="/fabui/application/modules/scan/assets/img/working_plane.png" />
            </div>
            
            <div class="row">
            <div class="smart-form">
				<fieldset style="background: transparent;">
					
						
						<section class="col col-6">
							<label class="label">Start</label>
							<label class="input">
								<input class="coordinates" id="x1" type="number" type="number" max="223" min="1" />
							</label>
						</section>
						<section class="col col-6">
							<label class="label">End</label>
							<label class="input">
								<input class="coordinates" id="x2" type="number" max="223" min="1" />
							</label>
						</section>
						<!--
                        <section class="col col-4">
                        	<label class="label">A</label>
							<label class="input">
								<input class="coordinates" id="a_offset" type="text" value="0" />
							</label>
						</section>
						-->
						<input class="coordinates" id="a_offset" type="hidden" value="0" />
						<input id="z_offset" type="hidden" value="" >
					
				</fieldset>
            </div>
            </div>
         
         </div>
    
    </div>
	
</div>

<script type="text/javascript">

$("#x1").spinner({
	step :1,
	numberFormat : "n",
	min: 0,
	max: 223
});

$("#x2").spinner({
	step :1,
	numberFormat : "n",
	min: 0,
	max: 223
});


/*  SCAN QUALITY SLIDER */
$("#scan-quality").noUiSlider({
    range: {'min': 20, 'max' : 100},
    /*range: [20, 100],*/
    start: 20,
    step: 20,
    handles: 1,
    connect: 'lower',
    behaviour: 'tap-drag' 
 });

/* SCAN QUALITY SLIDER EVENTS */
 $("#scan-quality").on({
	 slide: manageSlide,
	 set: manageSlide,
	 change: manageSlide
});



setTimeout(function (){
	 	
	/* SCAN QUALITY SLIDER EVENTS */
	 $("#scan-quality").on({
		 slide: manageSlide,
		 set: manageSlide,
		 change: manageSlide
	});
	
	$("#scan-quality").val( 20, {
		set: true,
		animate: true
	});
	

 }, 100);



/** PLANE COORDINATES */ 

X_MIN = 0;
Y_MAX = 220;

var jcrop_api;   
/**  JCROP */  
$('#plane').Jcrop({
    bgFade: true,
    onChange: setCoords,
    onSelect: setCoords   
},function(){
    jcrop_api = this;
});




$('.coordinates').on('keyup', function(e){
	x1 = $('#x1').val(),
    x2 = $('#x2').val(),
    y1 = $('#y1').val(),
    y2 = $('#y2').val();
    jcrop_api.setSelect([x1,y1,x2,y2]);
});

setTimeout(function() {
	jcrop_api.setSelect([1,101,223,141, 50, 50]);
}, 100);


</script>

