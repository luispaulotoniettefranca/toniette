<?php
require "vendor/autoload.php";
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \Source\Core\Socket() //change this line to the right class
        )
    ),
    8080
);
$server->run();