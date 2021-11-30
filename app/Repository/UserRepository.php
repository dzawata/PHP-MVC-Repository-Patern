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

    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users(id,name,password) VALUES (?, ?, ?)");
        $statement->execute([
            $user->id,
            $user->name,
            $user->password
        ]);
        return $user;
    }

    public function update(User $user): void
    {
        $statement = $this->connection->prepare("UPDATE users SET name = ?, password = ? WHERE id = '".$user->id."'");
        $statement->execute([$user->name, $user->password]);
    }

    public function findById($id)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE id = ? ");
        $statement->execute([$id]);

        try{
            $user = new User;
            if($row = $statement->fetch()){
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

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM users");
    }

}