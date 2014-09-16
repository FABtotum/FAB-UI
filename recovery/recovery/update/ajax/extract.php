<?php

require_once("/var/www/recovery/update/inc/init.php");


$_file = $_POST['file'];
$_type = $_POST['type'];

switch($_type){

	case 'myfab':
		$_result = extract_zip($_file, MYFAB_DOWNLOAD_EXTRACT_FOLDER);
		break;
	case 'marlin':
		$_result = true;
		break;

}

$_response_items['result'] = $_result;

header('Content-Type: application/json');
echo json_encode($_response_items);



/**
 * 
 * @param unknown $source
 * @param unknown $destination
 */
function extract_zip($source, $destination){
	
	
	
	$zip = new ZipArchive;
	
	$res = $zip->open($source);
	
	if ($res === TRUE) {
	
		$zip->extractTo($destination);
		$zip->close();
		return true;
	} else {
		return false;
	}
	
}

?>