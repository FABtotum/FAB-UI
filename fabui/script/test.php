<?php
require_once '/var/www/fabui/application/libraries/Serial.php';

$port_name = '/dev/ttyAMA0';
$boud_rate = 115200;


$file = '/var/www/recovery/install/file/copafullthin_2.gcode';


$gcode = file_get_contents($file);

$gcode = explode(PHP_EOL,$gcode);

/*
$serial = new Serial();

$serial -> deviceSet($port_name);
$serial -> confBaudRate($boud_rate);
$serial -> confParity("none");
$serial -> confCharacterLength(8);
$serial -> confStopBits(1);
$serial -> deviceOpen();
*/

$start = time();
$wait_ext_temp = true;
$wait_bed_temp = true;


foreach($gcode as $line){
	
	$wait = 0;
	
	
	if(!is_blank($line)){
		
		if(!is_comment($line)){
			
			
			if($wait_bed_temp){
				if(substr($line, 0, 4) == 'M190'){
					echo "wait bed temperature".PHP_EOL;
					$wait_bed_temp = false;
					$wait = 100;
				}
			}
			
			
			if($wait_ext_temp){
				if(substr($line, 0, 4) == 'M109'){
					echo "wait ext temperature".PHP_EOL;
					$wait_ext_temp = false;
					$wait = 60;
				}
			}	
			
			
			echo '->'.$line;
			echo $wait > 0 ? ' <- wait '.$wait : '';
			echo PHP_EOL;
			sleep($wait);
			
		}
		
	}
	
}

$end = time();


$diff = $end -$start;



echo time_elapsed($diff).PHP_EOL;


function is_blank($line){
	
	$line = trim($line);
	return $line == '' ? true : false;
	
}


function is_comment($line){
	
	$line = trim($line);
	return $line[0] == ';' ? true : false;
	
}






function time_elapsed($secs){
	
	
    $bit = array(
        ' year'        => $secs / 31556926 % 12,
        ' week'        => $secs / 604800 % 52,
        ' day'        => $secs / 86400 % 7,
        ' hour'        => $secs / 3600 % 24,
        ' minute'    => $secs / 60 % 60,
        ' second'    => $secs % 60
        );
        
    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
        }
    array_splice($ret, count($ret)-1, 0, 'and');
    $ret[] = 'ago.';
    
    return join(' ', $ret);
}

?>