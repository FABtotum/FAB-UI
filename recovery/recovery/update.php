<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Progressbar - Default functionality</title>
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="/fabui/application/layout/assets/js/fabtotum.js"></script>


</head>
<body>

	<div>
		<button id="update">Update</button>
		<span id="percentuale"></span>
		<span id="size"></span>
		
	</div>

	<div id="progressbar"></div>


	<script>

 var monitor_timeout  = 500;
 var interval_monitor;
 
 $(document).ready(function() {

		$("#update").on('click', function(){

			do_update();

		});

		$("#progressbar").progressbar({value: 0});

		  
  });



  function do_update(){


	  $.ajax({
		  url: "/recovery/update/do_update.php",
		  async: false
		})
		  .done(function( data ) {
			  interval_monitor  = setInterval(progress, monitor_timeout); /* SCAN MONITOR */
		});
		

	  
  }


  function progress(){


	  $.ajax({
		  url: "/recovery/update/progress.json",
		  async: true,
		  dataType: 'json'
		})
		  .done(function( response ) {


			  var percent = response.percent;

			  percent = number_format(precise_round(percent, 2), 2, ',', '.')
			  
			  $("#progressbar").progressbar({value: precise_round(response.percent, 2)});

			  $("#percentuale").html(percent + "%");

			  $("#size").html(bytesToSize(response.downloaded) + " of " + bytesToSize(response.download_size));
				  
		  });

	  

	
	  
  }

  
  </script>


</body>
</html>
