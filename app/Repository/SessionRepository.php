<?php

namespace Idharf\PhpMvc\Repository;

use Idharf\PhpMvc\Domain\Session;
use PDO;

class SessionRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $statement = $this->connection->prepare("INSERT INTO sessions(id, user_id) VALUES(?, ?)");
        $statement->execute([
            $session->id,
            $session->userId
        ]);
        return $session;
    }

    public function findById($id): Session
    {
        $statement = $this->connection->prepare("SELECT id, user_id FROM sessions WHERE id = '$id' ");
        $statement->execute();

        try{
            $session = new Session();
            if($row = $statement->fetch()){
                $session->id = $row['id'];
                $session->userId = $row['user_id'];
            }
            return $session;
        }finally{
            $statement->closeCursor();
        }
    }

    public function deleteById($id): void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = '$id' ");
        $statement->execute();
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM sessions");
    }

}