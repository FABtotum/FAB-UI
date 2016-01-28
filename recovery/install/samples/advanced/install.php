<?php 

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** LOAD DB */
$db = new Database();

$object['user'] = $id_user;
$object['obj_name'] = 'Samples - Advanced';
$object['obj_description'] = 'These objects are more precise, bigger or complex to print.';
$object['date_insert'] = 'now()';
$object['date_updated'] = 'now()';
$object['private'] = 1;

/** ADD OBJECT RECORD TO DB */ 
$id_object = $db->insert('sys_objects', $object);

$first_part_sql = 'INSERT INTO sys_files ';
$files_column = ' (file_name, file_type, file_path, full_path, raw_name, orig_name, client_name, file_ext, file_size, print_type, is_image, image_width, image_height, image_type,image_size_str, insert_date, update_date, note, attributes) ';

$files = array(
	'2x_stages_kit.gcode' => "('2x_stages_kit.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/2x_stages_kit.gcode', '2x Stages kit', '2x_stages_kit.gcode', '2x_stages_kit.gcode', '.gcode', '24335276', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Aubenc', '{\"dimensions\": {\"x\" : \"172.352996826\", \"y\": \"191.878997803\", \"z\": \"70.0\"}, \"number_of_layers\" : 351, \"filament\": \"22125.0515137\", \"estimated_time\":\"4:05:11\" }\n')",
	'3D_Knot_(hi-res)_by_Chylld_(thinghiverse_BSD_2-License)_PLA-1.5hour-13grams.gcode' => "('3D_Knot_(hi-res)_by_Chylld_(thinghiverse_BSD_2-License)_PLA-1.5hour-13grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/3D_Knot_(hi-res)_by_Chylld_(thinghiverse_BSD_2-License)_PLA-1.5hour-13grams.gcode', '3D Knot (hi-res)', '3D_Knot_(hi-res)_by_Chylld_(thinghiverse_BSD_2-License)_PLA-1.5hour-13grams.gcode', '3D_Knot_(hi-res)_by_Chylld_(thinghiverse_BSD_2-License)_PLA-1.5hour-13grams.gcode', '.gcode', '6904597', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Chylld\r\nPLA, 1.5hour, 13grams', '{\"dimensions\": {\"x\" : \"123.089996338\", \"y\": \"132.268997192\", \"z\": \"70.0\"}, \"number_of_layers\" : 209, \"filament\": \"4465.99023438\", \"estimated_time\":\"1:02:26\" }\n')",
	'Apollo_Astronaut_by__MaxGrueter_0.7scale_(thingiverse_CC-BY-NC)-PLA-5hour-38gram.gcode' => "('Apollo_Astronaut_by__MaxGrueter_0.7scale_(thingiverse_CC-BY-NC)-PLA-5hour-38gram.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Apollo_Astronaut_by__MaxGrueter_0.7scale_(thingiverse_CC-BY-NC)-PLA-5hour-38gram.gcode', 'Apollo Astronaut', 'Apollo_Astronaut_by__MaxGrueter_0.7scale_(thingiverse_CC-BY-NC)-PLA-5hour-38gram.gcode', 'Apollo_Astronaut_by_ MaxGrueter_0.7scale_(thingiverse_CC-BY-NC)-PLA-5hour-38gram.gcode', '.gcode', '26386065', 'additive', '0', '0', '0', '0', '', now(), now(), 'by MaxGrueter, 0.7scale\r\nPLA, 5hours, 38gram', '{\"dimensions\": {\"x\" : \"149.268997192\", \"y\": \"139.432998657\", \"z\": \"127.150001526\"}, \"number_of_layers\" : 847, \"filament\": \"12964.8791504\", \"estimated_time\":\"3:07:13\" }\n')",
	'Arc_triomphe_by_LeFab_Shop_(thingiverse_CC-BY-SA)_PLA-1.5hour-14grams.gcode' => "('Arc_triomphe_by_LeFab_Shop_(thingiverse_CC-BY-SA)_PLA-1.5hour-14grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Arc_triomphe_by_LeFab_Shop_(thingiverse_CC-BY-SA)_PLA-1.5hour-14grams.gcode', 'Arc Triomphe', 'Arc_triomphe_by_LeFab_Shop_(thingiverse_CC-BY-SA)_PLA-1.5hour-14grams.gcode', 'Arc_triomphe_by_LeFab_Shop_(thingiverse_CC-BY-SA)_PLA-1.5hour-14grams.gcode', '.gcode', '5781784', 'additive', '0', '0', '0', '0', '', now(), now(), 'LeFab Shop\r\nPLA, 1.5hour, 14grams', '{\"dimensions\": {\"x\" : \"119.936004639\", \"y\": \"124.809005737\", \"z\": \"70.0\"}, \"number_of_layers\" : 250, \"filament\": \"4841.12695312\", \"estimated_time\":\"0:53:23\" }\n')",
	'BoltAndNut_9x_Thighter.gcode' => "('BoltAndNut_9x_Thighter.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/BoltAndNut_9x_Thighter.gcode', 'Bolt And Nut', 'BoltAndNut_9x_Thighter.gcode', 'BoltAndNut 9x Thighter.gcode', '.gcode', '5499911', 'additive', '0', '0', '0', '0', '', now(), now(), '', '')",
	'Bucky_Ball_inflated,_by_Fred_Bartels.gcode' => "('Bucky_Ball_inflated,_by_Fred_Bartels.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Bucky_Ball_inflated,_by_Fred_Bartels.gcode', 'Bucky Ball inflated', 'Bucky_Ball_inflated,_by_Fred_Bartels.gcode', 'Bucky Ball inflated, by Fred Bartels.gcode', '.gcode', '4304413', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Fred Bartels', '{\"dimensions\": {\"x\" : \"56.1550064087\", \"y\": \"56.3259963989\", \"z\": \"54.9500007629\"}, \"number_of_layers\" : 183, \"filament\": \"1980.17874207\", \"estimated_time\":\"1:25:05\" }\n')",
	'Hand_OK_lowpoly_by_Cyclone_h40_(thingiverse_CC-BY-SA)_PLA-30min-4grams.gcode' => "('Hand_OK_lowpoly_by_Cyclone_h40_(thingiverse_CC-BY-SA)_PLA-30min-4grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Hand_OK_lowpoly_by_Cyclone_h40_(thingiverse_CC-BY-SA)_PLA-30min-4grams.gcode', 'Hand OK lowpoly', 'Hand_OK_lowpoly_by_Cyclone_h40_(thingiverse_CC-BY-SA)_PLA-30min-4grams.gcode', 'Hand_OK_lowpoly_by_Cyclone_h40_(thingiverse_CC-BY-SA)_PLA-30min-4grams.gcode', '.gcode', '2865428', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Cyclone h40\r\nPLA, 30min, 4grams', '{\"dimensions\": {\"x\" : \"109.476997375\", \"y\": \"116.913002014\", \"z\": \"70.0\"}, \"number_of_layers\" : 267, \"filament\": \"1303.3795166\", \"estimated_time\":\"0:24:13\" }\n')",
	'Matlab_Knot_by_Emmet_(thingiverse_BSD-License)-PLA-Support-2hour-20grams.gcode' => "('Matlab_Knot_by_Emmet_(thingiverse_BSD-License)-PLA-Support-2hour-20grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Matlab_Knot_by_Emmet_(thingiverse_BSD-License)-PLA-Support-2hour-20grams.gcode', 'Matlab Knot\r\n', 'Matlab_Knot_by_Emmet_(thingiverse_BSD-License)-PLA-Support-2hour-20grams.gcode', 'Matlab_Knot_by_Emmet_(thingiverse_BSD-License)-PLA-Support-2hour-20grams.gcode', '.gcode', '6318138', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Emmet\r\nPLA, Support, 2hours, 20grams', '{\"dimensions\": {\"x\" : \"129.662994385\", \"y\": \"140.791000366\", \"z\": \"70.0\"}, \"number_of_layers\" : 166, \"filament\": \"5931.91357422\", \"estimated_time\":\"1:24:35\" }\n')",
	'Soft_Octet_Trusses_by_HunterFrerich_(thingiverse_CC_BY_SA)_PLA-1hour-7grams.gcode' => "('Soft_Octet_Trusses_by_HunterFrerich_(thingiverse_CC_BY_SA)_PLA-1hour-7grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Soft_Octet_Trusses_by_HunterFrerich_(thingiverse_CC_BY_SA)_PLA-1hour-7grams.gcode', 'Soft Octet Trusses', 'Soft_Octet_Trusses_by_HunterFrerich_(thingiverse_CC_BY_SA)_PLA-1hour-7grams.gcode', 'Soft Octet Trusses by HunterFrerich (thingiverse CC_BY_SA) PLA-1hour-7grams.gcode', '.gcode', '5670594', 'additive', '0', '0', '0', '0', '', now(), now(), 'by HunterFrerich\r\nPLA, 1hour, 7grams', '{\"dimensions\": {\"x\" : \"114.302001953\", \"y\": \"122.26600647\", \"z\": \"70.0\"}, \"number_of_layers\" : 129, \"filament\": \"2564.25585938\", \"estimated_time\":\"0:29:06\" }\n')",
	'Venus_De_Milo_scanned_by_Cosmo_Wenman_(thingiverse_CC-BY)_PLA-4hour-26grams.gcode' => "('Venus_De_Milo_scanned_by_Cosmo_Wenman_(thingiverse_CC-BY)_PLA-4hour-26grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Venus_De_Milo_scanned_by_Cosmo_Wenman_(thingiverse_CC-BY)_PLA-4hour-26grams.gcode', 'Venus de Milo', 'Venus_De_Milo_scanned_by_Cosmo_Wenman_(thingiverse_CC-BY)_PLA-4hour-26grams.gcode', 'Venus De Milo scanned by Cosmo Wenman (thingiverse CC-BY) PLA-4hour-26grams.gcode', '.gcode', '22850625', 'additive', '0', '0', '0', '0', '', now(), now(), ' scanned by Cosmo Wenman\r\nPLA, 4hours, 26grams', '{\"dimensions\": {\"x\" : \"122.147994995\", \"y\": \"131.643997192\", \"z\": \"131.679992676\"}, \"number_of_layers\" : 1013, \"filament\": \"8894.44433594\", \"estimated_time\":\"2:04:40\" }\n')"
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