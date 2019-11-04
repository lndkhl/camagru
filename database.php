<?php
    $hostname = "127.0.0.1";
    $username = "root";
    $password = "iamgroot";
    $dbname = "web_project_1";

    require_once(tables.php)

    function 
    try
    {
        $conn = new PDO("mysql:host=$hostname; dbname=$dbname", $username, $password);        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    }
    catch(PDOException $exception)
    {
        echo "Connection to database $dbname failed: " . $exception->getMessage() . "<br>";
    }
?>