<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

/** SAVE POST PARAMETERS */
$_object_id = $_POST["id_object"];
$_printable = $_POST["printable"];

$print_type = $_POST['print_type'];

switch($print_type){
	case 'print':
		$manufactoring = 'additive';
		break;
	case 'mill':
		$manufactoring = 'subtractive';
		break;
	case 'laser':
		$manufactoring = 'laser';
		break;
}

/** UTIL PARAMS */
$_printable_files[] = '.gc';
$_printable_files[] = '.gcode';
$_printable_files[] = '.nc';

/** LOAD DB */
$db = new Database();

/** LOAD OBJECE FROM DB */
$_object = $db->query("select * from sys_objects where id=".$_object_id);

$_object = $_object[0];

$_object['date_insert']  =  date('d/m/Y', strtotime($_object['date_insert']));
$_object['date_updated'] =  date('d/m/Y', strtotime($_object['date_updated'])); 
$_object['id']           =  $_object["id"];

/** LOAD OBJECT'S FILES FROM DB */
$_object_files = $db->query("select * from sys_obj_files left join sys_files on sys_files.id = sys_obj_files.id_file where id_obj=".$_object_id.' and print_type="'.$manufactoring.'" ' );

//echo "select * from sys_obj_files left join sys_files on sys_files.id = sys_obj_files.id_file where id_obj=".$_object_id.' and print_type="'.$print_type.'" ';

$_files = array();

foreach($_object_files as $_obj){

    $_temp = $db->query("select * from sys_files where id=".$_obj['id_file']);	
	$_files[] = $_temp[0];
}

$db->close();

/** RESPONSE */
echo minify(json_encode(array('object'=>$_object, 'files'=>array('number' => count($_files), 'data' => $_files))));


?>