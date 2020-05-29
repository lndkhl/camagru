<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ChangeUsername extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            if (isset($_POST['changeusername']))
            {
                $username = $_POST['username'];
                if (preg_match('/[a-z_]/', $username) && preg_match('/[a-z]/', $username))
                {
                    if (!static::query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$username)))
                    {
                        if(preg_match('/.{3}/', $username))
                        {
                            static::query('UPDATE camagru.users SET username=:username WHERE id=:user_id',
                                array(':username'=>$username, ':user_id'=>static::isLoggedIn()));
                            echo "Username changed successfully";
                        }
                        else
                        {
                            echo "Invalid username<>Username must be at least 3 characters long";
                        }
                    }
                    else
                    {
                        echo "That username is unavailable";
                    }
                }
                else
                {
                    echo "Invalid username<br>Username can only consist of lower case letters and (optionally) the underscore character";
                }
            }
        }
        else
        {
            die("You are not logged in");
        }
        static::create_view("change-username");
    }
}
?>