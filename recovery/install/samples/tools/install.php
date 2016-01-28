<?php 

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** LOAD DB */
$db = new Database();

$object['user'] = $id_user;
$object['obj_name'] = 'FABtotum Replicable parts & tools';
$object['obj_description'] = 'Usefull parts and tools that you can use with your FABtotum Personal Fabricator';
$object['date_insert'] = 'now()';
$object['date_updated'] = 'now()';
$object['private'] = 1;

/** ADD OBJECT RECORD TO DB */ 
$id_object = $db->insert('sys_objects', $object);

$first_part_sql = 'INSERT INTO sys_files ';
$files_column = ' (file_name, file_type, file_path, full_path, raw_name, orig_name, client_name, file_ext, file_size, print_type, is_image, image_width, image_height, image_type,image_size_str, insert_date, update_date, note, attributes) ';

$files = array(
	'Clamp_Complete-Set_for(M4x30)-3h.gcode' => "('Clamp_Complete-Set_for(M4x30)-3h.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Clamp_Complete-Set_for(M4x30)-3h.gcode', 'Clamp Complete Set for', 'Clamp_Complete-Set_for(M4x30)-3h.gcode', 'Clamp_Complete-Set_for(M4x30)-3h.gcode', '.gcode', '9618213', 'additive', '0', '0', '0', '0', '', now(), now(), 'M4x30\r\n3h', '{\"dimensions\": {\"x\" : \"168.404006958\", \"y\": \"157.520004272\", \"z\": \"70.0\"}, \"number_of_layers\" : 96, \"filament\": \"10432.9289551\", \"estimated_time\":\"2:06:24\" }\n')",
	'Dust_Cover_V2.gcode' => "('Dust_Cover_V2.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Dust_Cover_V2.gcode', 'Dust Cover V2', 'Dust_Cover_V2.gcode', 'Dust_Cover_V2.gcode', '.gcode', '3781448', 'additive', '0', '0', '0', '0', '', now(), now(), '', '{\"dimensions\": {\"x\" : \"114.555000305\", \"y\": \"165.25100708\", \"z\": \"70.0\"}, \"number_of_layers\" : 235, \"filament\": \"3452.9901123\", \"estimated_time\":\"1:03:49\" }\n')",
	'Mandrel_40-80_(1h30min-25gram).gcode' => "('Mandrel_40-80_(1h30min-25gram).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Mandrel_40-80_(1h30min-25gram).gcode', 'Mandrel 40-80', 'Mandrel_40-80_(1h30min-25gram).gcode', 'Mandrel_40-80_(1h30min-25gram).gcode', '.gcode', '4920153', 'additive', '0', '0', '0', '0', '', now(),now(), '1h30min, 25gram', '{\"dimensions\": {\"x\" : \"134.380996704\", \"y\": \"144.380996704\", \"z\": \"70.0\"}, \"number_of_layers\" : 95, \"filament\": \"6175.09619141\", \"estimated_time\":\"1:01:21\" }\n');",
	'RaspiCam_Dust_Cover.gcode' => "('RaspiCam_Dust_Cover.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/RaspiCam_Dust_Cover.gcode', 'RaspiCam Dust Cover', 'RaspiCam_Dust_Cover.gcode', 'RaspiCam_Dust_Cover.gcode', '.gcode', '4002904', 'additive', '0', '0', '0', '0', '', now(), now(), '', '{\"dimensions\": {\"x\" : \"111.584999084\", \"y\": \"134.703994751\", \"z\": \"70.0\"}, \"number_of_layers\" : 211, \"filament\": \"2019.13769531\", \"estimated_time\":\"0:31:43\" }\n')",
	'Socket_Insert_Screwdriver_5.5_(M3_nuts).gcode' => "('Socket_Insert_Screwdriver_5.5_(M3_nuts).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Socket_Insert_Screwdriver_5.5_(M3_nuts).gcode', 'Socket Insert Screwdriver 5.5', 'Socket_Insert_Screwdriver_5.5_(M3_nuts).gcode', 'Socket Insert Screwdriver 5.5 (M3 nuts).gcode', '.gcode', '2625114', 'additive', '0', '0', '0', '0', '', now(), now(), 'M3 nuts', '{\"dimensions\": {\"x\" : \"106.928001404\", \"y\": \"201.130004883\", \"z\": \"70.0\"}, \"number_of_layers\" : 149, \"filament\": \"1403.69854736\", \"estimated_time\":\"0:38:35\" }\n')",
	'Socket_Screwdriver_10_(head_V2_Pushfit).gcode' => "('Socket_Screwdriver_10_(head_V2_Pushfit).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Socket_Screwdriver_10_(head_V2_Pushfit).gcode', 'Socket Screwdriver 10', 'Socket_Screwdriver_10_(head_V2_Pushfit).gcode', 'Socket Screwdriver 10 (head V2 Pushfit).gcode', '.gcode', '4253110', 'additive', '0', '0', '0', '0', '', now(), now(), 'head V2 Pushfit', '{\"dimensions\": {\"x\" : \"111.04599762\", \"y\": \"120.440994263\", \"z\": \"84.0599975586\"}, \"number_of_layers\" : 495, \"filament\": \"4405.16699219\", \"estimated_time\":\"0:51:26\" }\n')",
	'Socket_Screwdriver_5.5_(M3_nuts).gcode' => "('Socket_Screwdriver_5.5_(M3_nuts).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Socket_Screwdriver_5.5_(M3_nuts).gcode', 'Socket Screwdriver 5.5 ', 'Socket_Screwdriver_5.5_(M3_nuts).gcode', 'Socket Screwdriver 5.5 (M3 nuts).gcode', '.gcode', '4008527', 'additive', '0', '0', '0', '0', '', now(), now(), 'M3 nuts', '{\"dimensions\": {\"x\" : \"108.845001221\", \"y\": \"117.970001221\", \"z\": \"70.0\"}, \"number_of_layers\" : 403, \"filament\": \"2815.16967773\", \"estimated_time\":\"0:41:34\" }\n')",
	'Socket_Screwdriver_7_(M4_nuts).gcode' => "('Socket_Screwdriver_7_(M4_nuts).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Socket_Screwdriver_7_(M4_nuts).gcode', 'Socket Screwdriver 7', 'Socket_Screwdriver_7_(M4_nuts).gcode', 'Socket Screwdriver 7 (M4 nuts).gcode', '.gcode', '3993327', 'additive', '0', '0', '0', '0', '', now(), now(), ' M4_nuts', '{\"dimensions\": {\"x\" : \"108.845001221\", \"y\": \"117.970001221\", \"z\": \"70.0\"}, \"number_of_layers\" : 398, \"filament\": \"2849.56054688\", \"estimated_time\":\"0:41:02\" }\n')",
	'Socket_Screwdriver_8_(M5_nuts).gcode' => "('Socket_Screwdriver_8_(M5_nuts).gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Socket_Screwdriver_8_(M5_nuts).gcode', 'Socket Screwdriver 8', 'Socket_Screwdriver_8_(M5_nuts).gcode', 'Socket Screwdriver 8 (M5 nuts).gcode', '.gcode', '4143784', 'additive', '0', '0', '0', '0', '', now(), now(), ' M5 nuts', '{\"dimensions\": {\"x\" : \"110.0\", \"y\": \"118.970001221\", \"z\": \"70.0\"}, \"number_of_layers\" : 405, \"filament\": \"3284.03149414\", \"estimated_time\":\"0:41:58\" }\n')",
	'Spool_Lock_Lever_V2.gcode' => "('Spool_Lock_Lever_V2.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Spool_Lock_Lever_V2.gcode', 'Spool Lock Lever V2', 'Spool_Lock_Lever_V2.gcode', 'Spool Lock Lever V2.gcode', '.gcode', '842624', 'additive', '0', '0', '0', '0', '', now(), now(), '', '{\"dimensions\": {\"x\" : \"128.180999756\", \"y\": \"119.110992432\", \"z\": \"70.0\"}, \"number_of_layers\" : 51, \"filament\": \"1204.93713379\", \"estimated_time\":\"0:10:51\" }\n')"
);


foreach($files as $file_name => $final_sql_part){
	
	if(file_exists(dirname(__FILE__).'/'.$file_name)){
		
		$full_file_path = dirname(__FILE__).'/'.$file_name;
		
		$complete_sql = $first_part_sql.$files_column.' VALUES '.$final_sql_part;
		
		//insert record file
		if(!$db->query($complete_sql)){
			
			//get file id
			$file_id = $db->last_insert_id();
			
			//match with object
			$match['id_obj']  = $id_object;
			$match['id_file'] = $file_id;
			
			$db->insert('sys_obj_files', $match);
			//copy files to gcode's folder
			shell_exec('sudo cp "'.$full_file_path.'" "'.UPLOAD_PATH.'gcode/'.$file_name.'"');
			
		}	
	}
	
}

$db->close();


?>