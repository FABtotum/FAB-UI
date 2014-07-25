<div class="row">
	<div class="col-sm-6">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="text-primary">
					Select Area
				</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="well">
					<div class="plane">
					</div>
					<div class="smart-form">
						<fieldset style="background: transparent;">
							<div class="row">
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
	<div class="col-sm-6">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="text-primary">
					Title
				</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="well">
					<div class="smart-form">
						<fieldset>
							<section>
								<label class="label">
									Density
								</label>
								<label class="input">
									<input type="text" />
								</label>
							</section>
							<section>
								<label class="label">
									Axis increment
								</label>
								<label class="input">
									<input type="text" />
								</label>
							</section>
							<div class="row">
								<section class="col col-6">
									<label class="label">
										Start degree
									</label>
									<label class="input">
										<input type="text" />
									</label>
								</section>
								<section class="col col-6">
									<label class="label">
										End degree
									</label>
									<label class="input">
										<input type="text" />
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

<script type="text/javascript">

/** PLANE COORDINATES */  
var c = {"x":65,"y":64,"x2":152,"y2":164,"w":100,"h":100};
    
/**  JCROP */  
$('.plane').Jcrop({
    bgFade: true,
    allowSelect: false,
    setSelect: [c.x,c.y,c.x2,c.y2],
    onChange: setCoords,
    onSelect: setCoords   
},function(){
    jcrop_api = this;
});

</script>