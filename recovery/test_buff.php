<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Progressbar - Default functionality</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script>

setInterval(function() {
    $.get("progress.txt", function(data) {
	  data=parseInt(data);
	  if (isNaN(data)){
	  data=old_data;
	  }else{
	  old_data=data;
	  }
	  
		$("#progressbar").progressbar({value:data});	
		$("#text").text(data);
	
	
    });
}, 500); // updates every second
</script>
</head>
<body>
<div id="text">0</div>
<div id="progressbar"></div>
</body>
</html>
