<script type="text/javascript">
$(function () {
	jog_call('extruder_mode', 'e');
	$(".extrude").on("click", extrudeFilament);
	$(".recalculate").on('click', calculateStep);
	$(".step-change-modal-open").on('click', openModal);
	$("#change-extruder-step-value-button").on('click', changeExtruderStepValue);
});
/* */
function extrudeFilament()
{
	var button = $(this);
	button.html('<i class="fa fa-spin fa-spinner"></i> Extruding..');
	disable_button('.extrude');
	disable_button('.step-change-modal-open');
	$(".response-container").html('');
	$(".calc-row").slideUp(function(){});
	var filamentToExtrude = $("#filament-to-extrude").val();
	var gCode = 'M302\nG91\nG0 E+'+filamentToExtrude+' F400';
	jog_make_call_ajax('mdi', gCode, extrudeCallBack);
}
/* */
function extrudeCallBack(response)
{
	setTimeout(function(){
		enable_button('.extrude');
		enable_button('.step-change-modal-open');
		$(".calc-row").slideDown(function(){
			$('.extrude').html('<i class="fab-lg fab-fw icon-fab-e"></i> Start to extrude');
		});
	}, 15000);
}
/* */
function calculateStep()
{
	var button = $(this);
	button.html('<i class="fa fa-spin fa-spinner"></i> Calculating..');
	disable_button('.recalculate');
	disable_button('.extrude');
	disable_button('.step-change-modal-open');
	$(".response-container").html('');
	var data = {
		action : 'calculate',
		actual_step : $("#actual-step").val(),
		filament_to_extrude: $("#filament-to-extrude").val(), 
		filament_extruded:$("#filament-extruded").val()
	};
	$.ajax({
		type: "POST",
		url: '/fabui/application/modules/maintenance/ajax/calculate_feeder_step.php',
		dataType: 'json',
		data: data
	}).done(function( data ) {
		jog_call('extruder_mode', 'e');
		button.html('<i class="fa fa-calculator"></i> Calculate');
		enable_button('.recalculate');
		enable_button('.extrude');
		enable_button('.step-change-modal-open');
		var html = '<div class="alert alert-info animated fadeIn"> <strong>Calibration completed</strong> New value: <strong>' + data.new_step + '</strong></div>';
		/*$(".calc-row").slideUp(function(){*/
			$(".response-container").html(html);
		/*});*/
		$("#actual-step").val(data.new_step);
		jog_call('extruder_mode', 'e');
		
	});		
}
/* */
function openModal()
{
		
	$("#feeder-step-new-value").val($("#actual-step").val());
	$('#change-value-modal').modal({
		keyboard : false
	});
}
/* */
function changeExtruderStepValue()
{	
	$(".calc-row").slideUp(function(){});
	var data = {
		action : 'change',
		new_step : $("#feeder-step-new-value").val()
	};
	$.ajax({
		type: "POST",
		url: '/fabui/application/modules/maintenance/ajax/calculate_feeder_step.php',
		dataType: 'json',
		data: data
	}).done(function( data ) {
		$('#change-value-modal').modal('hide');
		$("#actual-step").val(data.new_step);
		jog_call('extruder_mode', 'e');
	});
}
</script>