<?php
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
    
    function isLoggedIn()
    {
        if(isset($_COOKIE["CID"]))
        {
            $hashcookie = sha1($_COOKIE["CID"]);
            if (DB::query('SELECT user_id FROM tokens WHERE token= :token', array(':token'=>$hashcookie)))
            {
                echo "testing...";
                return true;;
            }
        }
        return false;
    }

    if (isLoggedIn())
    {
        echo "You are logged in!";
    }
    else
    {
        echo "You are not logged in!";
    }

?>