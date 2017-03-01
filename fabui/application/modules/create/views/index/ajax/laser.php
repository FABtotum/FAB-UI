<div class="row" id="first-row">
	<div class="col-sm-12">
		<div class="well">
			<div class="row">
				<div class="col-sm-6 text-center">
					<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/laser/laser_1.png" />
				</div>
				<div class="col-sm-6">
					<h1 class="txt-color-red text-center animated tada"><i class="fa fa-warning"></i> Warning</h1>
					<h4 class="text-align-left">
						You are about to start a manufacturing task involving the laser head.<br> 
						Make sure to follow the <a target="_blank" href="http://www.fabtotum.com/?p=116429/">safety guidelines</a>.
						<ol class="margin-top-10">
							<li>Verify that engraving or cutting the material poses no hazard.</li>
							<li>Put the provided safety goggles now before continuing</li>
							<li>Make sure no one else can approach the  unit without proper safety goggles and being informed of the hazard.</li>
							<li>Do not remove the goggles unless it's safe to do so</li>
							<li>Wait for the procedure to end</li>
							<li>Do not touch, place or remove the laser head while the unit is operating</li>
						</ol>
					</h4>
					<div class="smart-form">
						<fieldset style="background:none!important; padding-top:10px;">
							<section>
								<label class="checkbox" style="padding-top:0px;font-size:19px;font-weight: 300; ">
									<input type="checkbox" name="terms" id="terms">
									<i></i>I understand and i agree with the conditions</label>
							</section>
						</fieldset>
					</div>
					<br class="simple">
					<p class="text-center"><button id="procede-button" type="button" disabled="disabled" class="btn btn-primary disabled">Proceed</button></p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row hidden" id="second-row">
	<div class="col-sm-12" >
		<div class="well">
			<div class="row">
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-6 text-center" id="home-description-container">
									<img style="max-width: 50%; display: inline;" class="img-responsive" src="../application/modules/create/assets/img/laser/laser_2.png" />
									<h4 class="text-center margin-top-50">
										Position the laser point to the origin of the drawing (bottom-left corner if you are engraving from an image).<br>
										Jog to the desired  XY position and then press "Home" button</h4>
								</div>
								<div class="col-sm-6 text-center" id="focus-point-description-container">
									<img style="max-width: 50%; display: inline;"  class="img-responsive" src="../application/modules/create/assets/img/laser/laser_3a.png" />
									<h4 class="text-center">
										lower the Z so that the laser head is max 1 mm away from the stock 			material, then press continue 
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-6 text-center">
							<div class="btn-group-vertical">
								<a  href="javascript:void(0)" data-attribue-direction="up-left"  data-attribute-keyboard="103" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left fa-1x fa-rotate-45"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="left"      data-attribute-keyboard="100" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left "> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-down fa-rotate-45 "> </i> </a>
							</div>
							<div class="btn-group-vertical">
								<a href="javascript:void(0)" data-attribue-direction="up"   data-attribute-keyboard="104" class="btn btn-default btn-circle btn-xl jog directions btn-xl "> <i class="fa fa-arrow-up fa-1x"> </i> </a>
								<a href="javascript:void(0)" rel="tooltip" data-placement="top" data-original-title="Set Home position" data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-info btn-circle btn-xl jog zero_all "> <i class="fa fa-home"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-down "> </i> </a>
							</div>
							<div class="btn-group-vertical">
								<a href="javascript:void(0)" data-attribue-direction="up-right"   data-attribute-keyboard="105" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-up fa-1x fa-rotate-45"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="right"      data-attribute-keyboard="102" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right fa-rotate-45"> </i> </a>
							</div>
							<div class="btn-group-vertical" style="margin-left: 10px;">
								<a rel="tooltip" data-placement="right" data-original-title="Move Z Up" href="javascript:void(0)"  class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zdown"> <i class="fa fa-angle-double-up"> </i>&nbsp;Z </a>
								<hr/>
								<a rel="tooltip" data-placement="right" data-original-title="Move Z Down" href="javascript:void(0)" class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zup"> <i class="fa fa-angle-double-down"> </i>&nbsp; Z </a>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="smart-form">
								<fieldset style="background:none!important;">
									<div class="row">
										<section class="col col-6">
											<label class="laser-label">XY Step</label>
											<label class="input">
												<input type="number" min="1" value="5" id="step">
											</label>
										</section>
										<section class="col col-6">
											<label class="laser-label">Z Step (mm)</label>
											<label class="input">
												<input type="number" min="1" value="5" id="z-step">
											</label>
										</section>
									</div>
									<section>
										<label class="laser-label">XYZ Feedrate</label>
										<label class="input">
											<input type="number" min="1" value="1000" id="feedrate">
										</label>
									</section>
								</fieldset>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="smart-form">
								<fieldset style="background:none!important;">
									<section>
										<label class="checkbox" style="padding-top:0px;font-size:19px;font-weight: 300; ">
											<input type="checkbox" name="focus-point" id="focus-point" checked="checked">
											<i></i>Calibrate Z focusing point</label>
									</section>
								</fieldset>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row hidden" id="start-button-row">
	<div class="col-sm-12 text-center">
		<button id="start-button" type="button"  class="btn btn-primary">Start</button>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("[rel=tooltip], [data-rel=tooltip]").tooltip();
		
		$("#terms").on('click', function() {
			if($(this).is(':checked')) enable_button("#procede-button")
			else disable_button("#procede-button")
		});

		
		
		$("#focus-point").on('click', function() {
			if($(this).is(':checked')){
				$("#focus-point-description-container").removeClass('hidden');
				$("#home-description-container").removeClass('col-sm-12').addClass('col-sm-6');
				go_to_focus_point = true;
			}else{
				$("#focus-point-description-container").addClass('hidden');
				$("#home-description-container").removeClass('col-sm-6').addClass('col-sm-12');
				go_to_focus_point = false;
			}
		});

		$("#focus-point").trigger('click');

		$("#procede-button").on('click', pre_laser);

		$(".directions").on("click", directions);
		$( ".axisz" ).on( "click", axisz );
		$(".zero_all").on("click", zero_all);

		$("#start-button").on('click', print_object);
		
	});
	/**
	*
	*/
	function pre_laser()
	{
		IS_MACRO_ON = true;
		openWait('<i class="fa fa-circle-o-notch fa-spin"></i> Preparing laser task');
		 $.ajax({
   		  	url: ajax_endpoint + 'ajax/pre_task.php',
   		  	dataType : 'json',
          	type: "POST", 
        	data : { type: 'laser', restart: restart}
   		}).done(function(response) {
			if(response.status == 200){
				$("#first-row").addClass('hidden');
				$("#second-row").removeClass('hidden');//.addClass("animated slideInRight");
				$("#start-button-row").removeClass('hidden');//.addClass("animated slideInRight");
			}else{
				$.smallBox({
					title : "Warning",
					content: response.trace,
					color : "#C46A69",
					icon : "fa fa-warning",
	                timeout: 15000
	            });
			}
			IS_MACRO_ON = false;
            closeWait();
   		});
	}
</script>