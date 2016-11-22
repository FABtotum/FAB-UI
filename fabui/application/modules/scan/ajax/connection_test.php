<?php

$ip   = $_POST['ip'];
$port = $_POST['port'];



$response_array = array();


$response_array['connection'] = 'success';


if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
{
	$errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
	
    $response_array['connection'] = 'failed';
    
    //die("Couldn't create socket: [$errorcode] $errormsg \n");
}

socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 10, 'usec' => 5000)); 

if(!socket_connect($sock , $ip , $port))
{
	$errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
	    
    $response_array['connection'] = 'failed';
}


echo json_encode($response_array);


?>