<?php
class Database
{
    private static $host = "127.0.0.1";
    private static $dbname = "camagru";
    private static $username = "root";
    private static $password = "root";

    private static function connect() 
    {
        try
        {
            $pdo = new PDO("mysql:hostname=".self::$host."; dbname=".$dbname."; charset=utf8", self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch (PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function create_db()
    {
        try
        {
            self::connect()->exec("DROP DATABASE IF EXISTS camagru");
            self::connect()->exec("CREATE DATABASE IF NOT EXISTS camagru");
            echo "Database created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "create database error: " . "<br>" . $e->getMessage();
        }
    }
}
?>