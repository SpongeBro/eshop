<?php

class Database
{
    private static $host = "localhost";
    private static $dbname = "games";
    private static $user = "root";
    private static $pass = "";
     
    private static $conn;
    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    );
    
    public static function connect() : PDO
    {
        if (!isset(self::$conn))
        {
            self::$conn = new PDO("mysql:host=".self::$host.";dbname=".self::$dbname,
                    self::$user,
                    self::$pass,
                    self::$settings);
            self::$conn->query("SET NAMES utf8");
        }
        return self::$conn;
    }
    public static function query(string $sql, array $params = array()) : PDOStatement
    {
        $stmt = self::$conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}