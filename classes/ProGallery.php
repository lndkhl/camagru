<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProGallery extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {

        }
        else
        {
            Route::redirect("gallery");
            exit();
        }
        static::create_view("pro-gallery");
    }
}
?>