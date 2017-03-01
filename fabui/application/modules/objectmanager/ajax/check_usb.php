<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

$inserted = false;
$tree = array();

$content = <<<EOT
<div class="text-center">
    <h1><span style="font-size: 50px;" class="icon-fab-usb"></span></h1>
    <h1>Please insert USB disk</h1>
	<a class="btn btn-info check-usb" href="javascript:void(0);">Reload</a>
</div>
EOT;

/** LOAD FROM USB DISK */

if (file_exists('/dev/sda1')) {
	$inserted = true;
	$_destination = '/var/www/fabui/application/modules/objectmanager/temp/media.json';
	$_command = 'sudo python ' . PYTHON_PATH . 'usb_browser.py  --dest=' . $_destination;
	shell_exec($_command);
	$tree = json_decode(file_get_contents($_destination, FILE_USE_INCLUDE_PATH), TRUE);

	if (sizeof($tree) > 0) {
		$content = '<div class="tree smart-form"><ul>';
		
		//folders
		foreach ($tree as $folder) {
			
			if(substr($folder, -1) == '/' && $folder[0] != '.'){
				$content .= '<li><span data-loaded="false" data-folder="' . $folder . '"><i class="fa fa-lg fa-folder-open"></i> ' . rtrim(str_replace("/media/", '', $folder), '/') . '</span><ul></ul></li>';
			}
		}

		//files
		foreach ($tree as $folder) {
			
			if(substr($folder, -1) != '/' && $folder[0] != '.'){
				$content .= '<li><span><label class="checkbox inline-block"><input type="checkbox" name="checkbox-inline" value="'.$folder.'"><i></i> '.$folder.'</label></span></li>';
			}
		}

		
		
		
		$content .= '</ul></div>';
	}

}


$data_response['inserted'] = $inserted;
$data_response['treee']    = $tree;
$data_response['content']  = $content;
$data_response['command']  = $_command;

header('Content-Type: application/json');
echo minify(json_encode($data_response));
