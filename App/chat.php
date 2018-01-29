<?php

namespace App;
use Database\Connection;
use Model\Cliente;
use Model\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ComponentInterface;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'user.class.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Connection.class.php';

class Chat implements MessageComponentInterface
{
    protected $clients;
    private $users;
    private $connection;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->connection = new Connection();
        $this->users = new User($this->connection);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $obj = json_decode($msg);
        if($obj->forAll)
        {
            $this->forAll($from, $obj);
        }
    }

    private function forAll(ConnectionInterface $from, $obj)
    {
        $numRecv = count($this->clients) -1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $obj->msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $user = $this->users->getUserById($obj->user);
                $resp = json_encode(array(
                    'message'=> $obj->msg,
                    'nameUser'=> $user['nome'],
                    'date'=> date('Y-m-d H:i:s')
                ));
                // The sender is not the receiver, send to each client connected
                $client->send($resp);
            }
        }
        $this->users->saveTimeline($obj->msg, $user['id']);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $result = $this->users->getMessagesTimeline();
        $conn->send(json_encode($result));
        echo ("New Connection ! {{$conn->resourceId}}\n");
    }
}