<?php
set_time_limit(0);

require_once '/var/www/lib/php_web_socket.php';
require_once '/var/www/lib/web_socket_server_factory.php';

//$Factory = new WebSocketServerFactory();

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {

	global $Server;
	//global $Factory;
	$Factory = new WebSocketServerFactory();
	
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

		foreach ($Server->wsClients as $id => $client) {
			$Server -> wsSend($id, $message);
		}
	}

}

// when a client connects
function wsOnOpen($clientID) {
	
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
}

// start the server
$Server = new PHPWebSocket();

$Server -> bind('message', 'wsOnMessage');
//$Server -> bind('open', 'wsOnOpen');
//$Server -> bind('close', 'wsOnClose');

$Server -> wsStartServer(SOCKET_HOST, SOCKET_PORT);
?>