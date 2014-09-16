<?php
require_once("php_serial.class.php");

/**
 * 
 *  UPDATE CENTER UTILITIES
 * 
 * 
 */


/**
 * 
 */
function myfab_get_remote_version(){
	$_remote_version =  file_get_contents(MYFAB_REMOTE_VERSION_URL);
	return $_remote_version;	
}

/**
 * 
 * @return unknown
 */
function myfab_get_local_version(){
	$_local_version = file_get_contents(MYFAB_LOCAL_VERSION_PATH, FILE_USE_INCLUDE_PATH );
	return $_local_version;
}





/**
 * 
 */
function marlin_get_local_info(){
	
	
	$_response = array();
	
	$serial = new phpSerial;
	$serial->deviceSet("/dev/ttyAMA0");
	$serial->confBaudRate(115200);
	$serial->confParity("none");
	$serial->confCharacterLength(8);
	$serial->confStopBits(1);
	$serial->confFlowControl("none");
	
	
	$pos = strpos('', 'FIRMWARE_NAME:');
	
	while($pos === false ){
		
		$serial->deviceOpen();
		$serial->sendMessage("M115");
		$_temp = $serial->readPort();
		$pos = strpos($_temp, 'FIRMWARE_NAME:');
		$serial->serialflush();
		$serial->deviceClose();	
		
	}
	
	$_response = explode(' ', $_temp);
	
	
	
	$_info['fw_name']          = str_replace('FIRMWARE_NAME:', '', $_response[0]);
	$_info['fw_version']       = str_replace(';', '', $_response[1]);
	$_info['fw_url']           = str_replace('FIRMWARE_URL:', '', $_response[6]);
	$_info['protocol_version'] = str_replace('PROTOCOL_VERSION:', '', $_response[7]);
	$_info['machine_type']     = str_replace('MACHINE_TYPE:', '', $_response[8]);
	$_info['extruder_count']   = str_replace('EXTRUDER_COUNT:', '', $_response[9]);
	
	
	//return array('fw_name' => str_replace('FIRMWARE_NAME:', '', $_response[0]), 'fw_version' => str_replace(';', '', $_response[1]), 'fw_url' => str_replace('FIRMWARE_URL:', '', $_response[6]));
	
	return $_info;
}




/**
 *
 */
function marlin_get_local_version(){
	
	$_local_version = file_get_contents(MARLIN_LOCAL_VERSION_PATH, FILE_USE_INCLUDE_PATH );
	
	return $_local_version;

}


/**
 * 
 */
function marlin_get_remote_version(){
	
	

	$_remote_version =  file_get_contents(MARLIN_REMOTE_VERSION_URL);
	
	return $_remote_version;
}


/** */
function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE){
	if ( ! $fp = @fopen($path, $mode))
	{
		return FALSE;
	}

	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);

	return TRUE;
}


?>