<div class="row">
	<div class="col-sm-12">
		<div class="smart-form">
			<header>Quick settings</header>
			<fieldset>
				<div class="row">
					<section class="col col-8">
						<label class="label">Actual Extruder steps </label>
						<label class="input">
							<input type="text" id="actual-step" readonly="readonly" value="<?php echo $eeprom['steps_per_unit']['e']; ?>">
						</label>
					</section>
					<section class="col col-4">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" class="btn btn-sm btn-default btn-block step-change-modal-open"><i class="fa fa-pencil"></i> Change value</a>
					</section>
				</div>
			</fieldset>
			<header>Measure and calibrate extruder steps</header>
			<fieldset>
				<div class="row">
					<section class="col col-8">
						<label class="label"> Filament to extrude (mm) </label>
						<label class="input">
							<input type="number" value="100" id="filament-to-extrude" readonly="readonly">
						</label>
					</section>
					<section class="col col-4">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" class="btn btn-sm btn-default btn-block extrude"><i class="fab-lg fab-fw icon-fab-e"></i> Start to extrude</a>
					</section>
				</div>
				
				<div class="row calc-row" style="display:none;">
					<section class="col col-8">
						<label class="label">Enter the measure of the filament extruded (mm) </label>
						<label class="input">
							<input type="number" placeholder="100" value="100" id="filament-extruded">
						</label>
					</section>
					<section class="col col-4">
						<label class="label">&nbsp;</label>
						<a href="javascript:void(0);" class="btn btn-sm btn-default btn-block recalculate"><i class="fa fa-calculator"></i> Recalculate</a>
					</section>
				</div>
			</fieldset>
		</div>
	</div>
</div>
<div class="row">
<!--  -->
	<div class="col-sm-12 response-container"></div>
</div>
<div class="widget-footer"></div>
