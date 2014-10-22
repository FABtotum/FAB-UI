<?php
include_once("header.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

echo "<b>MACRO SIMULATOR</b>";
if($_POST['macro']!=""){
	$macro=$_POST['macro'];
	}

$macrofile="/var/www/fabui/python/gmacro.py";
$data = file_get_contents($macrofile);

$data=explode('preset=="',$data);

echo '<form method="post" action="macrosim.php"><select name="macro">';
	
			for ($i = 1; $i <= count($data); $i++) {
				$macroname=explode('":',$data[$i]);
				if ($macro==$macroname[0]){
						$selected="selected";
					}else{
						$selected="";
				}
			
				echo '<option value="'.$macroname[0].' '.$selected.'">'.$macroname[0].'</option>';
			}

echo '</select><INPUT TYPE="submit" name="submit" /></form>';

if ($macro!=""){
	echo "selected : " . $macro;
	
	$cmd = "sudo python /var/www/fabui/python/gmacro.py ".$macro." /var/www/temp/simtrace.trace /var/www/temp/simresult.log";
	
	$descriptorspec = array(
	   0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
	   2 => array("pipe", "w")    // stderr is a pipe that the child will write to
	);
	
	flush();
	
	$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
	echo "<pre>";
		if (is_resource($process)) {
			while ($s = fgets($pipes[1])) {
				print $s;
				flush();
			}
		}
		echo "</pre>";
	}
	
?>

