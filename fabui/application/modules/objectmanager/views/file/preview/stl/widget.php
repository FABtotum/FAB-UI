<div class="row">
	<div class="col-sm-3">
		<div class="well">
			<div class="row">

				<div class="col-sm-12" id="progress-status">
					<p id="percent"></p>
					<div class="progress">
						<div class="progress-bar bg-color-redLight" id="pb" role="progressbar" style="width: 0%"></div>
					</div>

				</div>

				<div class="col-sm-12 action-container" style="display: none;">
					
					<p class="">Material</p>
					<div class="btn-group ">
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="solid"     class="btn btn-default material active">Solid</a>
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="wireframe" class="btn btn-default material">Wireframe</a>
					</div>
					
					<p class="margin-top-10">Rotation</p>
					<div class="btn-group ">
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="off"     class="btn btn-default rotation active">Off</a>
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="on" class="btn btn-default rotation">On</a>
					</div>
					
					<p class="margin-top-10">Plane</p>
					<div class="btn-group ">
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="show"     class="btn btn-default plane active">Show</a>
						<a rel="tooltip" title="Caution: uses lots of memory" href="javascript:void(0);" data-action="hide" class="btn btn-default plane">hide</a>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-9">
		<div id="viewer" style="height:600px;"></div>
	</div>
</div>