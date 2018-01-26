<?php
namespace Server;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use App\Chat;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'chat.php';

class Server
{
    public static function runServer()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8090
        );
        $server->run();
    }
}