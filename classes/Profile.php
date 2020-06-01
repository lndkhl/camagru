<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sticker;

class Profile extends Users
{
    public static function main_()
    {
        global $sticker;
        echo "sticker = ";
        if(static::isLoggedIn())
        {
            if (isset($_POST['sticker']))
            {
                $GLOBALS['sticker'] = $_POST['sticker'];
                print_r($sticker);
            }
            static::parsePic();
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("profile");
    }
}
?>