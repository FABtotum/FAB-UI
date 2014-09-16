<div class="row">
    <div class="col-sm-6">
    	<h2 class="text-primary">
    		Select scan quality
    	</h2>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="well well-light">
			<div class="row margin-bottom-10" style="margin-top: 10px;">
				<div class="col-sm-12">
					<div class="text-center img-quality-container margin-bottom-10">
						<img class="img-responsive img-quality-container" style="display: inline; max-width: 200px;" src="application/modules/scan/assets/img/duck0.png">
					</div>
					<div id="scan-quality" class="noUiSlider"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<h5>
						Quality:
						<span id="quality">
						</span>
					</h5>
					<p id="quality-description">
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h2 class="text-primary">Select Area </h2>
    </div>
</div>

<div class="row">
    
    <div class="col-sm-12">
    
         <div class="well">
         
            <div class="row text-center">
                <img id="plane" src="/fabui/application/modules/scan/assets/img/working_plane.png" />
            </div>
            
            <div class="row">
            <div class="smart-form">
				<fieldset style="background: transparent;">
					<div class="row">
						<label class="label">
										Position (mm)
									</label>
						<section>
							<label class="input">
								<span class="icon-prepend label-x1">Start</span>
								<input class="coordinates" id="x1" type="text" />
							</label>
						</section>
						<section>
							<label class="input">
								<span class="icon-prepend label-x2">End</span>
								<input class="coordinates" id="x2" type="text" />
							</label>
						</section>
                        <section>
							<label class="input">
								<span class="icon-prepend label-x2">A</span>
								<input class="coordinates" id="a_offset" type="text" value="0" />
							</label>
						</section>
					</div>
				</fieldset>
            </div>
            </div>
         
         </div>
    
    </div>
    
</div>

<script type="text/javascript">
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




/** PLANE COORDINATES */  
var c = {"x":65,"y":64,"x2":152,"y2":164,"w":100,"h":100};
var jcrop_api;   
/**  JCROP */  
$('#plane').Jcrop({
    bgFade: true,
    allowSelect: false,
    setSelect: [c.x,c.y,c.x2,c.y2],
    onChange: setCoords,
    onSelect: setCoords   
},function(){
    jcrop_api = this;
});


jcrop_api.setSelect([0,101,223,141, 50, 50]);

$('.coordinates').on('keyup', function(e){
          x1 = $('#x1').val(),
          x2 = $('#x2').val(),
          y1 = $('#y1').val(),
          y2 = $('#y2').val();
    jcrop_api.setSelect([x1,y1,x2,y2]);
});


$("#scan-quality").val( 20, {
	set: true,
	animate: true
});

</script>

