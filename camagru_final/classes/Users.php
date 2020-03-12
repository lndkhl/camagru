<?php
class Users extends Database
{
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
            $sql = "CREATE TABLE IF NOT EXISTS camagru.tokens(
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
                password_token CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )"; 
            self::conect()->exec($sql);
            echo "<br>creating password tokens table<br>";
            echo "<br>Table password_tokens created successfully<br>";
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
            echo "<br>Table posts created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "profile table error: " . "<br>" . $e->getMessage();
        }
    }

    public static function create_table_comments()
    {
        echo "trying to create comments table";
        try
        {        
            $sql = "CREATE TABLE IF NOT EXISTS camagru.comments(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                comment VARCHAR, likes INT(12) UNSIGNED,
                user_id INT(12) UNSIGNED NOT NULL,
        
                FOREIGN KEY (user_id) REFERENCES camagru.users(id)
                )";
            self::connect()->exec($sql);
            echo "Table comments created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo "profile table error: " . "<br>" . $e->getMessage();
        }
    }
}
?>