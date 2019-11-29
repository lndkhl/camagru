<?php
    class Database
    {
        public static $host = "127.0.0.1";
        public static $dbname = "camagru";
        public static $username = "root";
        public static $password = "root";

        private static function connect() 
        {
            try
            {
                $pdo = new PDO("mysql:".self::$host."=127.0.0.1; dbname=".$dbname."; charset=utf8", self::$username, self::$password);
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
                //echo "Database created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo $sql . "<br>" . $e->getMessage();
            }
        }

        public static function create_table_users()
        {
            try
            {
                $sql = "CREATE TABLE IF NOT EXISTS camagru.users(
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(32) NOT NULL,
                    password VARCHAR(64) NOT NULL,
                    email VARCHAR(64),
                    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";
                self::connect()->exec($sql);
                //echo "<br>Table users created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "users table error: " . $e->getMessage();
            } 
        }

        public static function test()
        {
            print_r(self::query("SELECT * FROM camagru.users"));
        }

        public static function query($query, $params = array())
        {
            try
            {
                $statement = self::connect()->prepare($query);
                $statement->execute($params);
                if (explode(' ', $query)[0] == 'SELECT')
                {
                    $data = $statement->fetchAll();
                    return $data;
                }
            }
            catch (PDOException $e)
            {
                echo "Error: " . $e->getMessage();
            }
        }
    }