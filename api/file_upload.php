<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/ajax/lib/utilities.php';

$config = '/var/www/fabui/config/config.json';

$key = $_SERVER['HTTP_X_API_KEY'];
$location = trim($_REQUEST['location']);

$_units = json_decode(file_get_contents($config), TRUE);
$_upload_api_key = isset($_units['api']['key']) ? $_units['api']['key']: '';


if($key == $_upload_api_key){
	
	$_file_path = UPLOAD_PATH;
	$_file_name = basename($_FILES['file']['name']);
	$uploadfile = $_file_path . $_file_name;
	
	
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		$upload_ok = TRUE;
		
		/** CONNECT DATABASE */
		$db = new Database();
		
		/** CHECK FOR OBJ OR CREATE IT */
		$_user = 0;
		$cmd  = "SELECT id FROM sys_objects WHERE obj_name='Slic3r Upload' AND user=$_user";
		$obj_id  = $db->query($cmd)['id'];
		if (!$obj_id){
			$_obj_insert['obj_name'] = "Slic3r Upload";
			$_obj_insert['obj_description'] = "Objects uploaded from Slic3r";
			$_obj_insert['private'] = "false";
			$_obj_insert['date_insert'] = 'now()';
			$_obj_insert['user'] = 0;
			$obj_id = $db->insert('sys_objects', $_obj_insert);
		}
		
		$_file_id = $db->insert('sys_files', array());
		
		
		
		/** GET TYPE OF PRINT */
		$_print_type = print_type($uploadfile);
		
		$_file_size = filesize($uploadfile);
		
		/** UPDATE FILE INFO */
		$_file_update['file_name'] = $_file_name;
		$_file_update['file_type'] = 'text/plain';
		$_file_update['file_path'] = UPLOAD_PATH;
		$_file_update['full_path'] = $uploadfile;
		$_file_update['raw_name'] = $_file_name;
		$_file_update['orig_name'] = $_file_name;
		$_file_update['client_name'] = $_file_name;
		$_file_update['file_ext'] = '.gcode';
		$_file_update['file_size'] = $_file_size;
		$_file_update['print_type'] = $_print_type;
		$_file_update['insert_date'] = 'now()';
		
		$db->update('sys_files', array('column' => 'id', 'value' => $_file_id, 'sign' => '='), $_file_update);
		
		//** ADD FILE TO OBJ */
		$_obj_file_insert['id_obj'] = $obj_id;
		$_obj_file_insert['id_file'] = $_file_id;
		$db->insert('sys_obj_files', $_obj_file_insert);
		
		$db->close();
		
		/** GCODE ANALYZER */
		shell_exec('sudo php /var/www/fabui/script/gcode_analyzer.php '.$_file_id.' > /dev/null & echo $!');
		
		sleep(1);
		
		
	} else {
		$upload_ok = FALSE;
		return http_response_code(500);
	}
	
	

}else{
	return http_response_code(401);

}

// echo $location;

// $myfile = fopen("/home/tom/test/testfile.txt", "w");

// fwrite($myfile, 'key:' . $_SERVER['HTTP_X_API_KEY'].PHP_EOL);

// fwrite($myfile, 'POST:' . var_export($_POST, TRUE) . PHP_EOL. "****************".PHP_EOL);

// fwrite($myfile, 'REQUEST:' . var_export($_REQUEST, TRUE) . "****************".PHP_EOL);

// fwrite($myfile, 'FILES:' . var_export($_FILES, TRUE) . "****************".PHP_EOL);

// fwrite($myfile, 'SERVER:' . var_export($_SERVER, TRUE) . "****************".PHP_EOL);



// fclose($myfile);






?>



