<?php

namespace Idharf\PhpMvc\Config;

use PDO;
use PDOException;

class Database
{
    private static $pdo;
    
    public static function getConnection($env = "dev")
    {
        if(self::$pdo == null)
        {
            require_once __DIR__ . "/../../config/database.php";
            $config = getDatabaseConfig();

            try{
                self::$pdo = new PDO(
                    $config["database"][$env]["url"],
                    $config["database"][$env]["username"],
                    $config["database"][$env]["password"]
                );
            }catch(PDOException $e){
                echo "Koneksi gagal : ".$e->getMessage();exit();
            }
        }
        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction()
    {
        self::$pdo->rollBack();
    }
        
}