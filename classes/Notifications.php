<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Notifications extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['allownotifications']))
            {
                $notifications = 1;
                static::query('UPDATE camagru.users SET notifications=:notifications WHERE id=:user_id',
                    array(':notifications'=>$notifications, ':user_id'=>$user_id));
                    echo "Notifications enabled";    
            }
            else if (isset($_POST['disallownotifications']))
            {
                $notifications = 0;
                static::query('UPDATE camagru.users SET notifications=:notifications WHERE id=:user_id',
                    array(':notifications'=>$notifications, ':user_id'=>$user_id));
                echo "Notifications disabled";
            }
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("notifications");
    }
}
?>