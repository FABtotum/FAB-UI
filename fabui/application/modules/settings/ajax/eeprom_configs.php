<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** LOAD DATABASE */
$db = new Database();
$query = 'select * from eeprom_configs order by name';
$configs = $db->query($query);
$db->close();


$rows = array();

foreach($configs as $config){
	 $rows[] = array($config['id'], $config['values'], '<a><i class="fa fa-chevron-right fa-lg" data-toggle="row-detail" title="Show Details"></i> </a>', $config['active'], $config['name'], $config['description']);
}

header('Content-Type: application/json; charset=utf-8');
echo minify(json_encode(array('aaData' => $rows)));

?>