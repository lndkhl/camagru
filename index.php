<?php
    require_once('./Routes.php');
    
    function __autoload($class_name)
    {
        require_once './classes/'.$class_name.'.php';
    }

    $hostname = '127.0.0.1';
    $username = 'root';
    $password = 'root';

    try 
    {
        $conn = new PDO("mysql:host=$hostname; port=8889", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DROP DATABASE IF EXISTS camagru";
        $conn->exec($sql);
        $sql = "CREATE DATABASE IF NOT EXISTS camagru";
        $conn->exec($sql);
        echo "Database created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
?>