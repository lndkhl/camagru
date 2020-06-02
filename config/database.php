<?php
class database
{
    private static $DB_NAME;
    private static $DB_DSN;
    private static $DB_USER;
    private static $DB_PASSWORD;

    private static function connect() 
    {
        try
        {            
            static::set_vars();
            $pdo = new PDO(self::$DB_DSN, self::$DB_USER, self::$DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch (PDOException $e)
        {
            echo "PDO error: " . $e->getMessage();
        }
    }

    public static function get_db_name()
    {
        if (!self::$DB_NAME)
        {
            self::$DB_NAME = "camagru";
        }
        return self::$DB_NAME;
    }

    
    public static function set_vars()
    {        
        self::$DB_DSN = "mysql:hostname=" . $_SERVER['HTTP_HOST'] . "; db_name=" . static::get_db_name() . "; charset=utf8";
        self::$DB_USER = "root";
        self::$DB_PASSWORD = "root";
    }

    public static function get_project_root($page)
    {
        $project_root = "http://";
        $project_root .= $_SERVER['HTTP_HOST'];
        $project_root .= $_SERVER['REQUEST_URI'];
        $project_index = strstr($project_root, $page);
        $project_root = substr($project_root, 0, strlen($project_root) - strlen($project_index));
        return $project_root;
    }

    public static function create_db()
    {
        try
        {
            self::connect()->exec("CREATE DATABASE IF NOT EXISTS ".self::$DB_NAME."");
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
            self::connect()->exec("DROP DATABASE IF EXISTS " . self::get_db_name() . "");
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

    public static function countRows($table)
    {
        $nRows = self::connect()->query('SELECT COUNT(*) from ' . static::get_db_name() . '.' . $table . '')->fetchColumn(); 
        return $nRows;
    }

    public static function fetchPosts()
    {
        if (self::connect()->query('SELECT imgname FROM ' . static::get_db_name() . '.posts ORDER BY id DESC'))
        {
            $images = self::connect()->query('SELECT imgname FROM ' . static::get_db_name() . '.posts ORDER BY id DESC')->fetchAll();
            return $images;
        }
        else
        {
            echo "no images were found";
        }
    }
    
    public static function deleteById($user_id, $table)
    {
        static::query('DELETE FROM ' .  static::get_db_name()  .  '.' . $table . ' WHERE user_id=:user_id', array(':user_id'=>$user_id));
    }                

    public static function create_table_users()
    {
        try
        {
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".users(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(32) NOT NULL,
                password VARCHAR(64) NOT NULL,
                email VARCHAR(64),
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                verified TINYINT(1) UNSIGNED,
                notifications TINYINT(1) UNSIGNED
                )";
            self::connect()->exec($sql);
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
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".vokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                voken CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES " . static::get_db_name() . ".users(id)
                )";
            self::connect()->exec($sql);
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
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".tokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                token CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES " . static::get_db_name() . ".users(id)
                )";
            self::connect()->exec($sql);
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
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".pokens(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                poken CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
                
                FOREIGN KEY (user_id) REFERENCES " . static::get_db_name() . ".users(id)
                )";
            self::connect()->exec($sql);
        }
        catch(PDOException $e)
        {
            echo "Password reset tokens table initialization error: " . $e->getMessage();
        } 
    }
    
    public static function create_table_posts()
    {
        try
        {        
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".posts(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                imgname CHAR(64), likes INT(12) UNSIGNED,
                user_id INT(12) UNSIGNED NOT NULL,
        
                FOREIGN KEY (user_id) REFERENCES " . static::get_db_name() . ".users(id)
                )";
            self::connect()->exec($sql);
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
            $sql = "CREATE TABLE IF NOT EXISTS " . static::get_db_name() . ".comments(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                comment VARCHAR(240), likes INT(12) UNSIGNED,
                post_id INT(12) UNSIGNED NOT NULL,
        
                FOREIGN KEY (post_id) REFERENCES " . static::get_db_name() . ".posts(id)
                )";
            self::connect()->exec($sql);
        }
        catch(PDOException $e)
        {
            echo "Comments table initialization error: " . "<br>" . $e->getMessage();
        }
    }
}
?>