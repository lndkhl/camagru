<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Home extends Users
{
    public static function main_()
    {
        setup::initialize();
        if (isset($_GET['voken']))
        {
            if (static::query('SELECT user_id FROM camagru.vokens WHERE voken=:voken', array(':voken'=>sha1($_GET['voken']))))
            {
                $user_id = static::query('SELECT user_id FROM camagru.vokens WHERE voken=:voken', array(':voken'=>sha1($_GET['voken'])))[0]['user_id'];
                $verified = 1;
                static::query('UPDATE camagru.users SET verified=:verified WHERE id=:user_id',
                    array(':verified'=>$verified, ':user_id'=>$user_id));
                static::query('DELETE FROM camagru.vokens WHERE user_id=:user_id', array(':user_id'=>$user_id));
                echo "Email verification complete. You may login";
            }
        }
        if (!static::isLoggedIn())
        {
            static::create_view("home");
        }
        else
        {
            Route::redirect("profile");
            exit();
        }        
    }
}
?>