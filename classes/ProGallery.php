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
            static::displayLoggedInHeader();
            if (static::countRows("posts"))
            {
                $actual = static::populateGallery();
                if (count($actual))
                {
                    static::displayPage($actual, "pro-gallery");
                    echo '<script src="./js/gallery.js"></script>';
                    static::parseUserInput();
                }
            }
            static::displayFooter();
        }
        else
        {
            Route::redirect("gallery");
            exit();
        }
    }
}
?>