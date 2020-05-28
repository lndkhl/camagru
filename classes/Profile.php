<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Profile extends Users
{
    public static function main_()
    {
        if(static::isLoggedIn())
        {
            if (isset($_GET['username']))
            {
                if (static::query('SELECT username FROM camagru.users WHERE username=:username',
                    array(':username'=>$_GET['username'])))
                {
                    $username = static::query('SELECT username FROM camagru.users WHERE username=:username',
                        array(':username'=>$_GET['username']))[0]['username'];
                    echo $username . "'s Profile";
                }
            }
        }
        else
        {
            die ("You are not logged in");
        }
        static::create_view("profile");
    }
}
?>