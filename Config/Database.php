<?php

namespace App\Config;
use PDO;

class Database {

    private static PDO|null $pdo = null;
    public const MYSQL_MAX_LIMIT = 18446744073709551615;

    public static function connexion(): void {
        try {
            $server = 'localhost';
            $dbname = 'ism_db';
            $username = 'root';
            $password = 'cisco1234';
            $charset = 'utf8';
            $chaineConnexion = "mysql:host=$server;dbname=$dbname;charset=$charset";
            if (self::$pdo == null) {
                self::$pdo = new PDO($chaineConnexion, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage() . "\n";
        }
    }

    public static function getPdo(): PDO {
        return self::$pdo;
    }
}
