<?php
class database
{
    private static $DB_DSN = "mysql:hostname=127.0.0.1; db_name=camagru; charset=utf8";
    private static $DB_USER = "root";
    private static $DB_PASSWORD = "root";

    private static function connect() 
    {
        try
        {
            $pdo = new PDO(self::$DB_DSN, self::$DB_USER, self::$DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch (PDOException $e)
        {
            echo "PDO error: " . $e->getMessage();
        }
    }

    public static function create_db()
    {
        try
        {
            self::connect()->exec("CREATE DATABASE IF NOT EXISTS camagru");
            //echo "Database created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "failed to create database: " . "<br>" . $e->getMessage();
        }
    }

    public static function recreate_db()
    {
        try
        {
            self::connect()->exec("DROP DATABASE IF EXISTS camagru");
        }
        catch(PDOException $e)
        {
            echo "recreate database error: " . "<br>" . $e->getMessage();
        }
        static::create_db();
    }

    public static function query($query, $params)
    {
        $statement= self::connect()->prepare($query);
        $statement->execute($params);
        if (explode(' ', $query)[0] == 'SELECT')
        {
            $data = $statement->fetchAll();
            return $data;
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
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                verified TINYINT(1) UNSIGNED
                )";
            self::connect()->exec($sql);
            //echo "<br>Table users created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "users table error: " . $e->getMessage();
        } 
    }

    public static function create_table_verification_tokens()
    {
        try
        {
            $sql = "CREATE TABLE IF NOT EXISTS camagru.vokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                voken CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            //echo "<br>Table vokens created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "Verification tokens table initialization error: " . $e->getMessage();
        } 
    }

    public static function create_table_tokens()
    {
        try
        {
            $sql = "CREATE TABLE IF NOT EXISTS camagru.tokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                token CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            //echo "<br>Table tokens created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "Session tokens table initialization error: " . $e->getMessage();
        } 
    }
    
    public static function create_table_password_tokens()
    {
        try
        {
            $sql = "CREATE TABLE IF NOT EXISTS camagru.pokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                poken CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            //echo "<br>Table pokens created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "Password reset tokens table initialization error: " . $e->getMessage();
        } 
    }
    
    public static function create_table_followers()
    {
        try
        {
            $sql = "CREATE TABLE IF NOT EXISTS camagru.followers(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(12) UNSIGNED NOT NULL,
                follower_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
                self::connect()->exec($sql);
                //echo "<br>Table followers created successfully<br>";
        }
        catch (PDOException $e)
        {
            echo "Followers table initialization error: " . "<br>" . $e->getMessage();
        }
    }

    public static function create_table_posts()
    {
        try
        {        
            $sql = "CREATE TABLE IF NOT EXISTS camagru.posts(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                post VARCHAR(256), likes INT(12) UNSIGNED,
                user_id INT(12) UNSIGNED NOT NULL,
        
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            //echo "<br>Table posts created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "Posts table initialization error: " . "<br>" . $e->getMessage();
        }
    }

    public static function create_table_comments()
    {
        try
        {        
            $sql = "CREATE TABLE IF NOT EXISTS camagru.comments(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                comment VARCHAR(240), likes INT(12) UNSIGNED,
                user_id INT(12) UNSIGNED NOT NULL,
        
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            //echo "<br>Table comments created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "Comments table initialization error: " . "<br>" . $e->getMessage();
        }
    }
}
?>