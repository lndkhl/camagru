<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_index;

class Gallery extends Users
{
    public static function main_()
    {
        if (!static::isLoggedIn())
        {
            static::displayLoggedOutHeader();
            if (static::countRows("posts"))
            {
                $actual = static::populateGallery();
                if (count($actual))
                {
                    static::displayPage($actual, "gallery");
                }
            }
            static::displayFooter();
        }
        else
        {
            Route::redirect("pro-gallery");
            exit();
        }
    }
}
?>