<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Logout extends Users
{
    public static function main_()
    {
        $redirect = FALSE;
        if (static::isLoggedIn())
        {
            if (isset($_POST['confirm']))
            {
                if (isset($_POST['alldevices']))
                {
                    static::query('DELETE FROM camagru.tokens WHERE user_id=:user_id', array(':user_id'=>static::isLoggedIn()));
                }
                else 
                {
                    if (isset($_COOKIE['CamagruID']))
                    {
                        static::query('DELETE FROM camagru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])));
                    }
                }
                setcookie('CamagruID',sha1($_COOKIE['CamagruID']), time() - 3600, '/', NULL, NULL, TRUE);
                setcookie('StayIn', '1', time() - 3600, '/', NULL, NULL, TRUE);
                $redirect = TRUE;
                Home::main_();            
            }
        }
        else
        {
            die("You are not logged in");
        }
        if (!$redirect)
        {
            static::create_view("logout");
        }
    }
}