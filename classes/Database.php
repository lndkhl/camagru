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
                $pdo = new PDO("mysql:hostname=".self::$host."; dbname=".$dbname."; charset=utf8", self::$username, self::$password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }
            catch (PDOException $e)
            {
                echo "Error: " . $e->getMessage();
            }
        }

        
        public static function isLoggedIn()
        {
            if(isset($_COOKIE["CID"]))
            {
                $hashcookie = sha1($_COOKIE["CID"]);
                if (static::query('SELECT user_id FROM camagru.tokens WHERE token= :token', array(':token'=>$hashcookie)))
                {
                    $idnum = static::query('SELECT user_id FROM camagru.tokens WHERE token= :token', array(':token'=>$hashcookie))[0]['user_id'];
                    $uid = static::query('SELECT username FROM camagru.users WHERE id= :user_id', array(':user_id'=>$idnum))[0]['username'];
                    
                    if (isset($_COOKIE["CID_REFRESH"]))
                    {
                        return $idnum;
                    }
                    else
                    {
                        $bother = True;
                        $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
                        $token = sha1($toke);
                        static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$idnum));
                        static::query('DELETE FROM camagru.tokens WHERE token=:token', array(':token'=>$hashcookie));
                        
                        setcookie("CID", $toke, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                        setcookie("CID_REFRESH", 'irrelevant', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                        
                         return $idnum;
                    }
                }
            }
            return false;
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
                echo "<br>Table users created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "users table error: " . $e->getMessage();
            } 
        }

        public static function create_table_tokens()
        {
            try
            {
                $sql = $sql = "CREATE TABLE IF NOT EXISTS camagru.tokens(
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    token CHAR(64) UNIQUE NOT NULL,
                    user_id INT(12) UNSIGNED NOT NULL,
                    
                    FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                    )";
                self::connect()->exec($sql);
                echo "<br>Table tokens created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "tokens table error: " . $e->getMessage();
            } 
        }

        public static function create_table_password_tokens()
        {
            try
            {
                $sql = "CREATE TABLE IF NOT EXISTS camagru.password_tokens(
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    token CHAR(64) UNIQUE NOT NULL,
                    user_id INT(12) UNSIGNED NOT NULL,
                
                    FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                    )";
                self::conect()->exec($sql);
                echo "Table password_tokens created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "password tokens table error: " . "<br>" . $e->getMessage();
            }
        }

        public static function create_table_posts()
        {
            try
            {        
                $sql = "CREATE TABLE IF NOT EXISTS camagru.posts(
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    post CHAR(64), likes INT(12) UNSIGNED,
                    user_id INT(12) UNSIGNED NOT NULL,
            
                    FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                    )";
                self::connect()->exec($sql);
                echo "Table followers created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "profile table error: " . "<br>" . $e->getMessage();
            }
        }

        public static function create_table_comments()
        {
            try
            {        
                $sql = "CREATE TABLE IF NOT EXISTS camagru.comments(
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    comment VARCHAR, likes INT(12) UNSIGNED,
                    user_id INT(12) UNSIGNED NOT NULL,
            
                    FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                    )";
                self::connect()->exec($sql);
                echo "Table followers created successfully<br>";
            }
            catch(PDOException $e)
            {
                echo "profile table error: " . "<br>" . $e->getMessage();
            }
        }

        public static function test($table)
        {
            print_r(self::query("SELECT * FROM camagru.$table"));
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
                "Error: " . $e->getMessage();
            }
        }
    }
    ?>