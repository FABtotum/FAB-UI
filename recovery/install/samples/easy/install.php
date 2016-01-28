<?php 
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** LOAD DB */
$db = new Database();

$object['user'] = $id_user;
$object['obj_name'] = 'Samples - Easy';
$object['obj_description'] = 'Beginners samples. Easier to print';
$object['date_insert'] = 'now()';
$object['date_updated'] = 'now()';
$object['private'] = 1;

/** ADD OBJECT RECORD TO DB */ 
$id_object = $db->insert('sys_objects', $object);

$first_part_sql = 'INSERT INTO sys_files ';
$files_column = ' (file_name, file_type, file_path, full_path, raw_name, orig_name, client_name, file_ext, file_size, print_type, is_image, image_width, image_height, image_type,image_size_str, insert_date, update_date, note, attributes) ';

$files = array(
	'8bitHeart_by_Makerbot_(thinghiverse_CC-BY)_PLA-43min-6grams.gcode' => "('8bitHeart_by_Makerbot_(thinghiverse_CC-BY)_PLA-43min-6grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/8bitHeart_by_Makerbot_(thinghiverse_CC-BY)_PLA-43min-6grams.gcode', '8 bit Heart', '8bitHeart_by_Makerbot_(thinghiverse_CC-BY)_PLA-43min-6grams.gcode', '8bitHeart_by_Makerbot_(thinghiverse_CC-BY)_PLA-43min-6grams.gcode', '.gcode', '4748904', 'additive', '0', '0', '0', '0', '', now(), now(), 'PLA, 43min, 6grams\nby MakerBot', '{\"dimensions\": {\"x\" : \"111.678001404\", \"y\": \"120.070999146\", \"z\": \"70.0\"}, \"number_of_layers\" : 259, \"filament\": \"2122.73071289\", \"estimated_time\":\"0:25:55\" }\n');",
	'Darth_Marvin_by_FABtotum_(thingiverse_CC-BY-SA)-PLA-30min-5g.gcode' => "('Darth_Marvin_by_FABtotum_(thingiverse_CC-BY-SA)-PLA-30min-5g.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Darth_Marvin_by_FABtotum_(thingiverse_CC-BY-SA)-PLA-30min-5g.gcode', 'Darth Marvin', 'Darth_Marvin_by_FABtotum_(thingiverse_CC-BY-SA)-PLA-30min-5g.gcode', 'Darth_Marvin_by_FABtotum_(thingiverse_CC-BY-SA)-PLA-30min-5g.gcode', '.gcode', '1945176', 'additive', '0', '0', '0', '0', '', now(), now(), 'PLA, 30min, 5g', '{\"dimensions\": {\"x\" : \"110.491996765\", \"y\": \"123.649993896\", \"z\": \"70.0\"}, \"number_of_layers\" : 169, \"filament\": \"1656.94824219\", \"estimated_time\":\"0:18:33\" }\n')",
	'Fabtotum_logo_keychan_(CC-BY)_PLA-3min-1gram.gcode' => "('Fabtotum_logo_keychan_(CC-BY)_PLA-3min-1gram.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Fabtotum_logo_keychan_(CC-BY)_PLA-3min-1gram.gcode', 'Fabtotum Logo Keychan', 'Fabtotum_logo_keychan_(CC-BY)_PLA-3min-1gram.gcode', 'Fabtotum_logo_keychan_(CC-BY)_PLA-3min-1gram.gcode', '.gcode', '206143', 'additive', '0', '0', '0', '0', '', now(), now(), 'PLA, 3min, 1gram', '{\"dimensions\": {\"x\" : \"116.82900238\", \"y\": \"125.434997559\", \"z\": \"70.0\"}, \"number_of_layers\" : 15, \"filament\": \"296.799972534\", \"estimated_time\":\"0:03:03\" }\n')",
	'Lisa_the_printable_skull_by_macouno_(thingiverse_CC-BY-SA)_PLA-50min-8grams.gcode' => "('Lisa_the_printable_skull_by_macouno_(thingiverse_CC-BY-SA)_PLA-50min-8grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Lisa_the_printable_skull_by_macouno_(thingiverse_CC-BY-SA)_PLA-50min-8grams.gcode', 'Lisa the printable skull', 'Lisa_the_printable_skull_by_macouno_(thingiverse_CC-BY-SA)_PLA-50min-8grams.gcode', 'Lisa the printable skull by macouno_(thingiverse_CC-BY-SA)_PLA-50min-8grams.gcode', '.gcode', '4550736', 'additive', '0', '0', '0', '0', '', now(),now(), 'by Macouno\r\nPLA, 50min, 8grams', '{\"dimensions\": {\"x\" : \"112.559997559\", \"y\": \"130.014007568\", \"z\": \"70.0\"}, \"number_of_layers\" : 236, \"filament\": \"2903.31640625\", \"estimated_time\":\"0:31:16\" }\n')",
	'Marvin_KeyChain_FABtotum2.gcode' => "('Marvin_KeyChain_FABtotum2.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Marvin_KeyChain_FABtotum2.gcode', 'Marvin Key Chain', 'Marvin_KeyChain_FABtotum.gcode', 'Marvin_KeyChain_FABtotum.gcode', '.gcode', '2297676', 'additive', '0', '0', '0', '0', '', now(), now(), 'by 3D Hubs', '{\"dimensions\": {\"x\" : \"109.444000244\", \"y\": \"116.483001709\", \"z\": \"50.0\"}, \"number_of_layers\" : 203, \"filament\": \"1276.94702148\", \"estimated_time\":\"0:25:07\" }\n')",
	'PacMan_Ghost_by_Hatch_(thingiverse-CC-BY)_PLA-20min-4gram.gcode' => "('PacMan_Ghost_by_Hatch_(thingiverse-CC-BY)_PLA-20min-4gram.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/PacMan_Ghost_by_Hatch_(thingiverse-CC-BY)_PLA-20min-4gram.gcode', 'PacMan Ghost', 'PacMan_Ghost_by_Hatch_(thingiverse-CC-BY)_PLA-20min-4gram.gcode', 'PacMan Ghost_by_Hatch_(thingiverse-CC-BY)_PLA-20min-4gram.gcode', '.gcode', '1632161', 'additive', '0', '0', '0', '0', '', now(), now(), 'by Hatch\r\nPLA, 20min, 4gram', '{\"dimensions\": {\"x\" : \"111.478996277\", \"y\": \"121.479003906\", \"z\": \"70.0\"}, \"number_of_layers\" : 135, \"filament\": \"1245.50048828\", \"estimated_time\":\"0:13:48\" }\n')",
	'Watering_Can_by_James_Wood_(thingiverse_CC-BY-NC)-PLA-15min-3grams.gcode' => "('Watering_Can_by_James_Wood_(thingiverse_CC-BY-NC)-PLA-15min-3grams.gcode', 'text/plain', '/var/www/upload/gcode/', '/var/www/upload/gcode/Watering_Can_by_James_Wood_(thingiverse_CC-BY-NC)-PLA-15min-3grams.gcode', 'Watering_Can', 'Watering_Can_by_James_Wood_(thingiverse_CC-BY-NC)-PLA-15min-3grams.gcode', 'Watering_Can_by_James_Wood_(thingiverse_CC-BY-NC)-PLA-15min-3grams.gcode', '.gcode', '374913', 'additive', '0', '0', '0', '0', '', now(), now(), 'by James Wood\r\nPLA,15min,3grams', '{\"dimensions\": {\"x\" : \"112.789001465\", \"y\": \"122.789001465\", \"z\": \"70.0\"}, \"number_of_layers\" : 585, \"filament\": \"1087.22827148\", \"estimated_time\":\"0:14:13\" }\n')",
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