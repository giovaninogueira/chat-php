<?php

namespace App;
use Database\Connection;
use Model\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'user.class.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Connection.class.php';

class Chat implements MessageComponentInterface
{
    protected $clients;
    private $users;
    private $listUserOn;
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
        $std = new \stdClass();

        $result = $this->users->getUserById($obj->id_user);
        if(count($result) !=0) {
            if ($obj->type == 'online')
            {
                $std->name_user = $result['nome'];
                $std->id_user = $obj->id_user;
                $std->type = "online";
                $std->idCon = $from->resourceId;
                $this->listUserOn[$from->resourceId] = $std;
                $this->toSend($from, $this->returnListUser());

            }
            else if ($obj->type == 'message')
            {
                $obj->date = date('Y-m-d H:i:s');
                $userOn = $this->listUserOn[$from->resourceId];
                if ($userOn->idCon == $from->resourceId)
                {
                    $std->nome = $result['nome'];
                    $std->id_user = $obj->id_user;
                    $std->type = "online";
                    $std->idCon = $from->resourceId;
                    $std->message = $obj->message;
                    $std->date = date('Y-m-d H:i:s');
                    $this->toSend($from, array("message" => $std));
                    $this->users->saveTimeline($std->message, $std->id_user);
                }
            }
        }
        unset($std);
    }

    public function toSend(ConnectionInterface $from, $obj)
    {
        $from->send(json_encode($obj));
        foreach ($this->clients as $client)
        {
            if ($from !== $client)
            {
                $client->send(json_encode($obj));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        if(count($this->listUserOn)==0)
        {
            $this->clients->detach($conn);
            throw new \Exception('deu erro');
        }

        $obj = $this->listUserOn[$conn->resourceId];

        if($obj->idCon === $conn->resourceId)
        {
            $result = $this->users->getUserById($obj->id_user);
            if(count($result)!=0)
            {
                $std = new \stdClass();

                $std->type = "disconnected";
                $std->idCon = $conn->resourceId;
                $std->id_user = $obj->id_user;
                $std->name_user = $result['nome'];
                $this->toSend($conn, $std);

                unset($this->listUserOn[$conn->resourceId]);

                $this->toSend($conn, $this->returnListUser());
                $this->clients->detach($conn);
                echo "Connection {$conn->resourceId} has disconnected\n";
                unset($std);
            }
            else
            {
                unset($this->listUserOn[$conn->resourceId]);
                $this->clients->detach($conn);
                echo "Connection {$conn->resourceId} has disconnected\n";
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $std = new \stdClass();
        $std->error = $e->getMessage();
        $std->type = "error";
        $std->idCon = $conn->resource_id;
        $this->clients->attach($conn);
        unset($this->listUserOn[$conn->resource_id]);
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $result = $this->users->getMessagesTimeline();
        $conn->send(json_encode(array("msg"=>$result)));
        echo ("New Connection ! {{$conn->resourceId}}\n");
    }

    private function returnListUser()
    {
        return array("usersOnline"=>$this->listUserOn);
    }

}