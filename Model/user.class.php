<?php

namespace Model;

class User
{
    private $connection;

    public function __construct($conn)
    {
        $this->connection = $conn;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM user where id =:id";
        $prepare = $this->connection->prepare($sql);
        $prepare->execute([':id'=>$id]);
        $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0];
    }

    public function saveTimeline($msg, $id_user)
    {
        $sql = "INSERT INTO timeline (id_user, message, date) values (:id_user, :message, :date)";
        $prepare = $this->connection->prepare($sql);
        $prepare->execute(
            [
                'id_user'=>$id_user,
                'message'=>$msg,
                'date'=> date('Y-m-d H:i:s')
            ]
        );
    }

    public function getMessagesTimeline()
    {
        $sql = "select user.nome, timeline.message, timeline.id_user, timeline.date from timeline inner join user on user.id = timeline.id_user order by date asc";
        $prepare = $this->connection->prepare($sql);
        $prepare->execute();
        $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}