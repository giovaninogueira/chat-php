<?php
namespace Server;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use App\Chat;
use Ratchet\Wamp\Exception;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'chat.php';

class Server
{
    public static function runServer($port, $ip)
    {
        try
        {
            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new Chat()
                    )
                ),
                $port,
                $ip
            );
            echo 'Chat Server running in '. $ip . ':'. $port . "\n";
            $server->run();
        }
        catch (\Exception $e)
        {
            throw new Exception($e->getMessage());
            die;
        }
    }
}