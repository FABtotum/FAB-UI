<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


$x1 = $_POST['x1'];
$x2 = $_POST['x2'];
$y1 = $_POST['y1'];
$y2 = $_POST['y2'];
$skip = $_POST['skip'];


$_destination_response = TEMP_PATH.'macro_response';
$_destination_trace    = TEMP_PATH.'macro_trace'; 


write_file($_destination_trace, '', 'w');
//chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
//chmod($_destination_response, 0777);


/** EXEC COMMAND */

$_command        = 'sudo python '.PYTHON_PATH.'test_bed_area.py -x'.$x1.' -y'.$y1.' -j'.$x2.' -z'.$y2.' -s'.$skip.' > /dev/null';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));


$_response_items['status']             = 200;


header('Content-Type: application/json');
echo minify(json_encode($_response_items));

?>