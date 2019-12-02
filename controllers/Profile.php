<?php
    $username = "";

    if (isset($_GET['username']))
    {
        if (DB::query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$_GET['username'])))
        {
            $username = DB::query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
        }
        else
        {
            die ("User not found!");
        }
    }
?>