<?php

include 'header.php';

$macros[] = array('name' => 'Temperature', 'title'=>'Temperature', 'action'=>'temperature');
$macros[] = array('name' => 'Laser',       'title'=>'Laser',       'action'=>'laser');
$macros[] = array('name' => 'Mill',        'title'=>'Mill',        'action'=>'mill');
$macros[] = array('name' => 'Blower',      'title'=>'Blower',      'action'=>'blower');
$macros[] = array('name' => 'Head Light',  'title'=>'Head Light',  'action'=>'head_light');
$macros[] = array('name' => 'End Stop',    'title'=>'End Stop',    'action'=>'end_stop');
$macros[] = array('name' => 'Homing',      'title'=>'Homing',      'action'=>'g28');

$macros[] = array('name' => 'Probe Down',  'title'=>'Probe Down',  'action'=>'probe_down');
$macros[] = array('name' => 'Probe Up',    'title'=>'Probe Up',    'action'=>'probe_up');


?>

</head>
<body>
	<header id="header">
		<div id="logo-group">
			<span id="logo"><img src="/assets/img/logo-0.png"></span>
		</div>
	</header>
	<div id="main" role="main">
		<div id="ribbon">
			<ol class="breadcrumb">
				<li>
					<a href="/recovery/index.php">Recovery</a>
				</li>
				<li>
					Test Tool
				</li>
			</ol>
			
		</div>
		<div id="content">
			<div class="row">
				<div class="col-sm-12">
					<a class="btn btn-default pull-right reset-controller">Reset Controller</a>
				</div>
			</div>
			<div class="row margin-top-10">
				<div class="col-sm-5">
					<div class="well">
						
						<?php $count = 0; ?>
						<?php foreach($macros as $macro): ?>
						
						
						<?php if($count > 0): ?>
						<hr class="simple">
						<?php endif; ?>
						<div class="row">
							<div class="col-sm-12">
								<h4><span class="name"><?php echo $macro['name']; ?></span> <a data-action="<?php echo $macro['action'] ?>" class="btn btn-default action pull-right" href="javascript:void(0);">start</a></h4>
							</div>
						</div>
						
						<?php $count++;?>
						<?php endforeach; ?>
						
					</div>
				</div>
				
				<div class="col-sm-7">
					<div class="well">
						<h6 class="title"></h6>
						<pre class="console"></pre>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
	<?php
	include 'footer.php';
	?>
	
	<script src="/assets/js/notification/SmartNotification.min.js"></script>
	<script src="/assets/js/plugin/fuelux/wizard/wizard.min.js"></script>
	
	<script>
		
		
		var fabui = true;
		var setup_wizard = false;
		var number_tasks = 0;
		var number_updates = 0;
		var number_notifications  = 0;
		
	</script>
	
	<script type="text/javascript">
		$(function() {
			$('.action').click(do_action);
			$('.reset-controller').click(ask_reset);	
		});
		
		
		function ask_reset(){
		
		
			$.SmartMessageBox({
				title: "Reset controller",
				content: "This operation will reset your control board, continue?",
				buttons: '[No][Yes]'
				}, function(ButtonPressed) {
				   
					if (ButtonPressed === "Yes") {
					  	reset_controller();
						
					}
					if (ButtonPressed === "No") {
						
						return false;
					}
			
			});
			
			
		}
		
		function reset_controller(){
			
			var buttons = $('.btn');
			
			buttons.addClass('disabled');
			
			$.ajax({
				type: "POST",
				url : "/fabui/application/modules/controller/ajax/reset_controller.php",
				dataType: "json"
			}).done(function( data ) {
				
				
				buttons.removeClass('disabled');
				$('.console').html("Reset Done!");
				
			});
			
			
		}
		
		function do_action(){
			
			var button = $(this);
			
			button.parent().find('span').addClass("font-md");
			
			var action = button.attr("data-action");
			
			$(".console").html('');
			
			$(".action").addClass('disabled');
			button.html('<i class="fa fa-gear fa-spin"></i>');
			
			$(".title").html(getActionTitle(action));
			
			
			
			$.ajax({
				type: "POST",
				url : "/recovery/test_action.php",
				data : {action: action},
				dataType: "json"
			}).done(function( data ) {
				
				
				$(".action").removeClass('disabled');
				button.html('Start');
				button.parent().find('span').removeClass('font-md');
				
			});
			
			
		}
		
		
		function getActionTitle(action){
			
			
			switch(action){
				
			<?php foreach($macros as $macro): ?>
			
				case '<?php echo $macro['action']; ?>':
					return '<?php echo $macro['title']; ?>';
					break;
			
			<?php endforeach; ?>	
			
				
			}
			
		}
		
		
		

	</script>

</body>
</html>