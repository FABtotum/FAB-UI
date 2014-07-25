<?php
require_once '/var/www/myfabtotum/script/config.php';
require_once '/var/www/myfabtotum/ajax/lib/database.php';
require_once '/var/www/myfabtotum/ajax/lib/utilities.php';

$_tasks = array();

if ($handle = opendir('/var/www/tasks')) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($_folder = readdir($handle))) {
        if(!is_dir($_folder)){
            $_temp = explode('_', $_folder);
            $_tasks[] = array('id' => $_temp[1]);    
        }         
    }    
    closedir($handle);
}

$_response_items['number'] = count($_tasks);
$_response_items['tasks']  = json_encode($_tasks);
$_json                     = minify(json_encode($_response_items));

file_put_contents('/var/www/temp/notifications.json', $_json, FILE_USE_INCLUDE_PATH);


?>