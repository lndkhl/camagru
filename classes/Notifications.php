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
            if (isset($_POST['notifyme']))
            {
                $notifications = $_POST['notifyme'];
                static::query('UPDATE ' .  static::get_db_name()  .  '.users SET notifications=:notifications WHERE id=:user_id',
                            array(':notifications'=>$notifications, ':user_id'=>$user_id));
                if ($notifications) { echo "Notifications enabled"; }
                else { echo "Notifications disabled"; }
                Settings::main_();
                exit();
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