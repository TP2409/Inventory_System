<?php
namespace App;

use PDO;

class Database 
{
    public static function connect(): PDO
    {
        return new PDO(
            "mysql:host=localhost;dbname=smart_inventory;charset=utf8",
            "root",
            "tp24@mysql",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}
