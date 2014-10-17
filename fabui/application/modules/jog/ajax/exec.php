<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/libraries/Serial.php';

/** READ POST DATA */
$function = $_POST['function'];
$value    = $_POST["value"];
$time     = $_POST['time'];
$_step    = $_POST['step'];
$z_step   = $_POST['z_step'];
$feedrate = $_POST['feedrate'];
/** LOAD DATABASE CLASS */
//$db = new Database();
/** LOAD JOG PARAMETERS FROM DATABASE */
//$_step     = $db->query('SELECT value FROM sys_configuration where sys_configuration.key = "step" ');
//$_step     = $_step[0]['value'];

//$_feedrate = $db->query('SELECT value FROM sys_configuration where sys_configuration.key = "feedrate" ');
//$_feedrate = $_feedrate[0]['value'];


$db_step = 10;
$db_feedrate = 1000;

$z_step  = $z_step == '' ? 10 : $z_step;

$_step    = $_step    == '' ? $db_step: $_step;
$feedrate = $feedrate == '' ? $db_feedrate : $feedrate;
/** CLOSE DB */
//$db->close();

/** MACRO */
$_macro = false;

$relative = '';

if($_SESSION['relative'] == false){
	$relative = 'G91';
	$_SESSION['relative'] = true;
}


$_functions["motors"]["on"]  = "M17";
$_functions["motors"]["off"] = "M18";

$_functions["lights"]["on"]  = "M706 S255";
$_functions["lights"]["off"] = "M706 S0";

$_functions["coordinates"]["relative"] = "G91";
$_functions["coordinates"]["absolute"] = "G90";

$_functions["directions"]["up"]         = $relative.PHP_EOL."G0 Y+".$_step.' F'.$feedrate;
$_functions["directions"]["up-right"]   = $relative.PHP_EOL."G0 Y+".$_step." X+".$_step.' F'.$feedrate;
$_functions["directions"]["up-left"]    = $relative.PHP_EOL."G0 Y+".$_step." X-".$_step.' F'.$feedrate;
$_functions["directions"]["down"]       = $relative.PHP_EOL."G0 Y-".$_step.' F'.$feedrate;
$_functions["directions"]["down-right"] = $relative.PHP_EOL."G0 Y-".$_step." X+".$_step.' F'.$feedrate;
$_functions["directions"]["down-left"]  = $relative.PHP_EOL."G0 Y-".$_step." X-".$_step.' F'.$feedrate;
$_functions["directions"]["left"]       = $relative.PHP_EOL."G0 X-".$_step.' F'.$feedrate;
$_functions["directions"]["right"]      = $relative.PHP_EOL."G0 X+".$_step.' F'.$feedrate;


$_functions["directions"]["home"]       = "G90 G0 X0 Y0";

//$_functions["directions"]["home"]       = "G0 X0 Y0 Z0";

$_functions["rotation"] = "G90".PHP_EOL."G0 E";

$_functions["mdi"] = " ";

$_functions["feed"] = " ";

$_functions["unit"]["mm"]   = "G21";
$_functions["unit"]["inch"] = "G20";

$_functions['zero_all'] = "G92 X0 Y0 Z0 E0";

$_functions['position'] = "M114";

$_functions['ext-temp'] = "M104 S";

$_functions['bed-temp'] = "M140 S";

$_functions['get-temp'] = "M105";

$_functions['home_all_axis'] = 'G28';

/** LOAD UNITS CONFIG */
$_units = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
$_functions['extruder_mode']['a'] = 'M92 E'.$_units['a'];
$_functions['extruder_mode']['e'] = 'M92 E'.$_units['e'];



switch ($function){
	case 'rotation':
		$command_value = $_functions[$function]." ".$value;
		break;
	case 'mdi':
		$command_value = $_functions[$function].strtoupper($value); 
		break;
	case 'feed':
		break;
	case 'zero_all':
        //$_macro      = true;
        //$_macro_name = 'zero_all';
		$command_value = $_functions[$function];
		break;
  	case 'home_all_axis':
        $_macro      = true;
        $_macro_name = 'home_all';
		//$command_value = $_functions[$function];
		break;
    case 'position':
		$command_value = $_functions[$function];
		break;
    case 'ext-temp':
		$command_value = $_functions[$function].$value;
		break;
    case 'bed-temp':
		$command_value = $_functions[$function].$value;
		break;
    case 'get-temp':
		$command_value = $_functions[$function];
		break;
    case 'zup':
		$command_value = $relative.PHP_EOL.'G0 Z+'.$z_step.' F'.$feedrate;
		break;
    case 'zdown':
		$command_value = $relative.PHP_EOL.'G0 Z-'.$z_step.' F'.$feedrate;
		break;
    case 'bed-align':
        $_macro        = true;
        $_macro_name   = 'auto_bed_leveling';
        break;
    case 'extruder_mode':
        $command_value = $_functions[$function][$value];
		if($value == "e"){
			$command_value .= PHP_EOL."G92 E0";
		}
        break;
    case 'extruder-e':
        $command_value = $relative.PHP_EOL.'G0 E'.$value.' F300';
        break;
    case 'lights':
        $command_value = $_functions[$function][$value];
        break;
	default :
		$command_value = $_functions[$function][$value];
        $command_value = $_feedrate != '' ? $command_value.' F'.$_feedrate : $command_value; 
}


if(!$_macro){
    
    $command_value=trim(str_replace("_","\r\n",$command_value));
    /** LOAD SERIAL CLASS */
    $serial = new Serial();

    $serial->deviceSet(PORT_NAME);
    $serial->confBaudRate(BOUD_RATE);
    $serial->confParity("none");
    $serial->confCharacterLength(8);
    $serial->confStopBits(1);
    $serial->deviceOpen();
	
	$temp = explode(PHP_EOL, $command_value);
	
	$response = '';
	
	foreach($temp as $com){
		$response .= '<strong>'.$com.'</strong> : ';
		$serial->sendMessage($com.PHP_EOL);
		$response .= str_replace(PHP_EOL, '', $serial->readPort()).PHP_EOL;	
	}
	
    $serial->serialflush();
    $serial->deviceClose();
	
    $_response_items['response'] = trim($response).PHP_EOL;

}else{
	
	
	$_SESSION['relative'] = false;
    
    $_macro_response = '/var/www/temp/jog_'.$time.'.log';
    $_macro_trace    = '/var/www/temp/jog_'.$time.'.trace'; 
    
    
    write_file($_macro_trace, '', 'w');
    chmod($_macro_trace, 0777);
   
    write_file($_macro_response, '', 'w');
    chmod($_macro_response, 0777);
    
    
    $_command_macro  = 'sudo python /var/www/fabui/python/gmacro.py '.$_macro_name.' '.$_macro_trace.' '.$_macro_response.' & echo $!';
	$_output_macro   = shell_exec ( $_command_macro );
	$_pid_macro      = trim(str_replace('\n', '', $_output_macro));
    
    $_response_items['command']       = $_macro_name;
    $_response_items['response']      = file_get_contents($_macro_response, FILE_USE_INCLUDE_PATH);
    $_response_items['pid_macro']     = $_pid_macro;
    $_response_items['command_macro'] = $_command_macro;
    
    //unlink($_macro_response);
   	//unlink($_macro_trace);
    sleep(1);    
}

//unlink($_macro_trace);
header('Content-Type: application/json');
echo minify(json_encode($_response_items));


?>