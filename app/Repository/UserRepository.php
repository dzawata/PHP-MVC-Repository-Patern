<?php

namespace Idharf\PhpMvc\Repository;

use Idharf\PhpMvc\Domain\User;
use PDO;

class UserRepository
{

    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        $statement = $this->connection->prepare("INSERT INTO users(id,name,password) VALUES (?, ?, ?)");
        $statement->execute([
            $user->id,
            $user->name,
            $user->password
        ]);
        return $user;
    }

    public function findById($id)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE id = ? ");
        $statement->execute([$id]);

        try{
            if($row = $statement->fetch()){
                $user = new User;
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
    
                return $user;
            }else{
                return null;
            }
        }finally{
            $statement->closeCursor();
        }
    }

    public function deleteAll()
    {
        $this->connection->exec("DELETE FROM users");
    }

}