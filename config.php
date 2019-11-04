<?php
    include_once("database.php")
    try
    {
        $sql = "CREATE DATABASE $dbname";
        $conn->exec(sql);
        echo "Database $dbname created successfully<br>";
    }
    catch (PDOException $exception)
    {
        echo $sql . "Failed to create $dbname" . $exception->getMessage();
    }