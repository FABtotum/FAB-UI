<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Dowload test</title>
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

<?php
ob_start();

// Create string to overflow browser buffer ...?
$buffer = str_repeat(" ", 4096);
$oldpercent=0;

$targetFile = fopen( 'testfile.iso', 'w+' );
 
$ch = curl_init( 'http://download.thinkbroadband.com/50MB.zip' );
 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt( $ch, CURLOPT_NOPROGRESS, false );
curl_setopt( $ch, CURLOPT_PROGRESSFUNCTION, 'callback' ); 
curl_setopt( $ch, CURLOPT_FILE, $targetFile );
 
curl_exec( $ch );
fclose( $ch );
 
function callback($download_size, $downloaded, $upload_size, $uploaded)
{
  $percent=intval(($downloaded/$download_size)*100);
  // Do something with $percent
  //echo sprintf('%.2f', $percent*100);
  //echo '%<br/>';
  //$fp = fopen( "progress.txt", 'a+' );
  
  if($percent>$oldpercent){
	$logfile="progress.txt";
   	$fh = fopen($logfile, 'w+');
	fwrite($fh,$percent);
	
	fclose($fh);
	//echo "Writing: ". $percent."- ".$logfile." - <br>";
	//ob_flush();
	//flush();
	$oldpercent=$percent;
   }
   
   if($percent==100){
   //post_redir(2,3000);
   echo "done!";
   }
 }
 
 
?>
