<?php
set_time_limit(0);
// include the web sockets server script (the server is started at the far bottom of this file)
require_once '/var/www/lib/php_web_socket.php';
require_once '/var/www/lib/web_socket_server_factory.php';

$Factory = new WebSocketServerFactory();

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {

	global $Server;
	global $Factory;

	//$ip = long2ip($Server -> wsClients[$clientID][6]);

	// check if message length is 0
	if ($messageLength == 0) {
		$Server -> wsClose($clientID);
		return;
	}

	$object = json_decode($message, TRUE);

	$function = isset($object['name']) ? $object['name'] : $object['type'];
	$data = isset($object['data']) ? $object['data'] : '';

	if (method_exists($Factory, $function)) {
		$message = $Factory -> $function($data);
	}

	// > 1 cause monitor.py is always connected
	if ($Server -> wsGetClientsNumber() > 0) {

		//Send the message to everyone but the person who said it
		foreach ($Server->wsClients as $id => $client) {
			//if ($id != $clientID) {

			//$clientIp = long2ip($client[6]);

			//echo $clientIp.PHP_EOL;
			$Server -> wsSend($id, $message);
			
			
			
			//}

		}
	}

}

// when a client connects
function wsOnOpen($clientID) {
	//echo $clientID . " connected" . PHP_EOL;
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	//echo $clientID . " disconnected" . PHP_EOL;
}

// start the server
$Server = new PHPWebSocket();

$Server -> bind('message', 'wsOnMessage');
//$Server -> bind('open', 'wsOnOpen');
//$Server -> bind('close', 'wsOnClose');

$Server -> wsStartServer(SOCKET_HOST, SOCKET_PORT);
?>