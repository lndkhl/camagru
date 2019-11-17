<?php
    include_once("./classes/DB.php");
    include_once("./classes/Login.php");

    $hostname = '127.0.0.1';
    $username = 'root';
    $password = 'root';

    try 
    {
        $conn = new PDO("mysql:host=$hostname; port=8889", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS camagru";
        $conn->exec($sql);
        echo "Database created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

    if (Login::isLoggedIn())
    {
        echo Login::isLoggedIn();
        echo " is logged in!";
    }
    else
    {
        echo "You are not logged in!";
    }
?>