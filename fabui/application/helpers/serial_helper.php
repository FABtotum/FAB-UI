<?php


/**
 * 
 *  Send command e get response
 * 
 */
function sendAndGet($message){
	
	$CI = &get_instance();

	//load configuration file
	$CI -> config -> load('fabtotum', TRUE);

	//serial
	$serial_port = $CI -> config -> item('fabtotum_serial_port', 'fabtotum');
	$serial_boud_rate = $CI -> config -> item('fabtotum_serial_boud_rate', 'fabtotum');

	$CI -> load -> library('serial');

	$CI -> serial -> deviceSet($serial_port);
	$CI -> serial -> confBaudRate($serial_boud_rate);
	$CI -> serial -> confParity("none");
	$CI -> serial -> confCharacterLength(8);
	$CI -> serial -> confStopBits(1);
	$CI -> serial -> deviceOpen();

	$CI -> serial -> sendMessage($message);
	$response = $CI ->serial-> readPort();
	$CI -> serial -> serialflush();
	$CI -> serial -> deviceClose();
	
	return $response;
}



/**
 *  Return Firwmare version
 */
function firmware_version() {

	$response = sendAndGet('M765'.PHP_EOL);
	$version = str_replace('V', '', $response);
	$version = str_replace('ok', '', $version);
	$version = trim($version);
	
	return $version;
}





/**
 * 
 * Return hw version 
 * 
 */
 function hardware_id(){
 	
	$response = sendAndGet('M763'.PHP_EOL);
	$version = trim($response);
	$version = str_replace('ok', '', $version);
	
	return $version;
 	
 }




?>