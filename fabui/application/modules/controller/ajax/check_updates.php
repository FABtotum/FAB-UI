<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

//$time_to_check = 60;
$time_to_check = (60 * 60) * 4;
//4 Hours
$now = time();
$check = false;

$updates = isset($_SESSION["updates"]) ? $_SESSION["updates"] : array();

if (!isset($_SESSION['updates']['time'])) {
	$_SESSION['updates']['time'] = 0;
}



//IF IS PASSED MORE THAN TIME TO CHECK SO CHECK AGAIN IF THERE ARE UPDATES AVAIABLES

if (($now - $_SESSION['updates']['time']) > $time_to_check) {

	if (is_internet_avaiable()) {

		$updates = array();

		$updates['number'] = 0;
		$updates['time'] = time();

		$fabui_update = myfab_get_local_version() < myfab_get_remote_version();
		$fw_update    = marlin_get_local_version() < marlin_get_remote_version();

		$updates['number'] += $fabui_update ? 1 : 0;
		$updates['number'] += $fw_update ? 1 : 0;
		$updates['fabui'] = $fabui_update;
		$updates['fw'] = $fw_update;

		$_SESSION['updates'] = $updates;
		$check = true;

	}

}

/*
if (isset($_SESSION['updates'])) {
	$updates = $_SESSION['updates'];
} else {
	$_SESSION['updates'] = $updates;
}
 * 
 * 
 */

$_response_items = array();
$_response_items['updates'] = $updates;
$_response_items['check'] = $check;

header("Cache-Control: no-cache, must-revalidate");
header('Content-Type: application/json');
echo minify(json_encode($_response_items));
?>