<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';



$post_key = $_SERVER['HTTP_X_API_KEY'];
$location = trim($_REQUEST['location']);
$key_match = FALSE;
$api_user = 0;

$_units = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
$_upload_api_keys = isset($_units['api']['keys']) ? $_units['api']['keys']: '';

foreach ($_upload_api_keys as $user => $key){
	if ($key == $post_key){
		$key_match = TRUE;
		$api_user = $user;
	}
}


if($key_match){
	

	$ext = end(explode('.', $_FILES['file']['name']));
	$_extension = strtolower($ext);
	

	
	$_file_path = UPLOAD_PATH . $_extension;
	$_origin_name = basename($_FILES['file']['name']);
	
	$_file_name = set_filename($_file_path, $_origin_name);
	$_raw_name = str_replace('.'.$ext, '', $_file_name);
	$uploadfile = $_file_path . $_file_name;
	
	if (!file_exists($_file_path))
	{
		mkdir($_file_path, 0777);
	}
	
	
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		$upload_ok = TRUE;
		
		/** CONNECT DATABASE */
		$db = new Database();
		
		/** CHECK FOR OBJ OR CREATE IT */
		
		$cmd  = "SELECT id FROM sys_objects WHERE obj_name='Slic3r Upload' AND user=$api_user";
		$obj_id  = $db->query($cmd)[0]['id'];
		if (!$obj_id){
			$_obj_insert['obj_name'] = "Slic3r Upload";
			$_obj_insert['obj_description'] = "Objects uploaded from Slic3r";
			$_obj_insert['private'] = "false";
			$_obj_insert['date_insert'] = 'now()';
			$_obj_insert['user'] = $api_user;
			$obj_id = $db->insert('sys_objects', $_obj_insert);
		}
		
		$_file_id = $db->insert('sys_files', array());
		
		
		
		/** GET TYPE OF PRINT */
		$_print_type = print_type($uploadfile);
		
		$_file_size = filesize($uploadfile);
		
		/** UPDATE FILE INFO */
		$_file_update['file_name'] = $_file_name;
		$_file_update['file_type'] = 'text/plain';
		$_file_update['file_path'] = $_file_path;
		$_file_update['full_path'] = $uploadfile;
		$_file_update['raw_name'] = $_raw_name;
		$_file_update['orig_name'] = $_origin_name;
		$_file_update['client_name'] = $_origin_name;
		$_file_update['file_ext'] = '.'.$_extension;
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




?>
