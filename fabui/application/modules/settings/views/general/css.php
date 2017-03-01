<style type="text/css">

#red {
		background: #c0392b !important;
	}
	#green {
		background: #27ae60 !important;
	}
	#blue {
		background: #2980b9 !important;
	}
    
    .pick-a-color-markup {
				margin:20px 0px;
	}
	
.result {
	height: 100px; 
	border: 1px solid #ccc; 
	
	background-color: rgb(<?php echo $settings['color']['r'] ?>, <?php echo $settings['color']['g'] ?>, <?php echo $settings['color']['b'] ?>); 
	color: rgb(<?php echo $settings['color']['r']?>, <?php echo $settings['color']['g'] ?>, <?php echo $settings['color']['b'] ?>);
}

.custom-settings{
		
		display:none;
	}
</style>