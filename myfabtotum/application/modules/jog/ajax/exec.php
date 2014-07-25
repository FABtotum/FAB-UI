<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/application/libraries/Serial.php';

/** READ POST DATA */
$function = $_POST['function'];
$value    = $_POST["value"];
$time     = $_POST['time'];

/** LOAD DATABASE CLASS */
$db = new Database();


/** LOAD JOG PARAMETERS FROM DATABASE */
$_step     = $db->query('SELECT value FROM sys_configuration where sys_configuration.key = "step" ');
$_step     = $_step[0]['value'];

$_feedrate = $db->query('SELECT value FROM sys_configuration where sys_configuration.key = "feedrate" ');
$_feedrate = $_feedrate[0]['value'];

/** CLOSE DB */
$db->close();

/** MACRO */
$_macro = false;


$_functions["motors"]["on"]  = "M17";
$_functions["motors"]["off"] = "M18";

$_functions["lights"]["on"]  = "M706 S255";
$_functions["lights"]["off"] = "M706 S0";

$_functions["coordinates"]["relative"] = "G91";
$_functions["coordinates"]["absolute"] = "G90";

$_functions["directions"]["up"]         = "G0 Y+".$_step;
$_functions["directions"]["up-right"]   = "G0 Y+".$_step." X+".$_step;
$_functions["directions"]["up-left"]    = "G0 Y+".$_step." X-".$_step;
$_functions["directions"]["down"]       = "G0 Y-".$_step;
$_functions["directions"]["down-right"] = "G0 Y-".$_step." X+".$_step;
$_functions["directions"]["down-left"]  = "G0 Y-".$_step." X-".$_step;
$_functions["directions"]["left"]       = "G0 X-".$_step;
$_functions["directions"]["right"]      = "G0 X+".$_step;


$_functions["directions"]["home"]       = "G0 X0 Y0 Z0";

$_functions["rotation"] = "G90\r\nG0 E";

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
		$command_value = 'G0 Z+'.$value;
		break;
    case 'zdown':
		$command_value = 'G0 Z-'.$value;
		break;
    case 'bed-align':
		//$command_value = 'G91'.PHP_EOL.'G28'.PHP_EOL.'G0 Z60'.PHP_EOL.'M402'.PHP_EOL.'G29'.PHP_EOL.'G0 Z60'.PHP_EOL.'M402'.PHP_EOL.'G0 X90 Y70'.PHP_EOL.'G92 X0 Y0'; 
		//$command_value = 'G91'.PHP_EOL.'G0 Z5 F1000'.PHP_EOL.'G90'.PHP_EOL.'G28'.PHP_EOL.'G29'.PHP_EOL.'G0 X+10 Y+10 Z+30 F5000';
        $_macro        = true;
        $_macro_name   = 'auto_bed_leveling';
        
        break;
    case 'extruder_mode':
        $command_value = $_functions[$function][$value];
        break;
    case 'extruder-e':
        $command_value = 'G0 E'.$value.' F300';
        break;
    case 'lights':
        $command_value = $_functions[$function][$value];
        break;
	default :
		$command_value = $_functions[$function][$value];
        $command_value = $_feedrate != '' ? $command_value.' F'.$_feedrate : $command_value; 
}


if(!$_macro){
    
    $command_value=str_replace("_","\r\n",$command_value);
    /** LOAD SERIAL CLASS */
    $serial = new Serial();
    
    $serial->deviceSet(PORT_NAME);
    $serial->confBaudRate(BOUD_RATE);
    $serial->confParity("none");
    $serial->confCharacterLength(8);
    $serial->confStopBits(1);
    $serial->deviceOpen();
    $serial->sendMessage($command_value."\r\n");
    $reply = $serial->readPort();
    $serial->serialflush();
    $serial->deviceClose();
    
    $_response_items['command']  = $command_value;
    $_response_items['response'] = $reply;

}else{
    
    $_macro_response = '/var/www/temp/jog_'.$time.'.log';
    $_macro_trace    = '/var/www/temp/jog_'.$time.'.trace'; 
    
    
    write_file($_macro_trace, '', 'w');
    chmod($_macro_trace, 0777);
   
    write_file($_macro_response, '', 'w');
    chmod($_macro_response, 0777);
    
    
    $_command_macro  = 'sudo python /var/www/myfabtotum/python/gmacro.py '.$_macro_name.' '.$_macro_trace.' '.$_macro_response.' & echo $!';
	$_output_macro   = shell_exec ( $_command_macro );
	$_pid_macro      = trim(str_replace('\n', '', $_output_macro));
    
    $_response_items['command']   = $_macro_name;
    $_response_items['response']  = file_get_contents($_macro_response, FILE_USE_INCLUDE_PATH);
    $_response_items['pid_macro'] = $_pid_macro;
    $_response_items['command_macro'] = $_command_macro;
    
    unlink($_macro_response);
    //unlink($_macro_trace);
    sleep(1);    
}

unlink($_macro_trace);
header('Content-Type: application/json');
echo minify(json_encode($_response_items));


?>