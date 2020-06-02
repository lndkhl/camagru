<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProGallery extends Users
{
    public static function main_()
    {
        global $page_index;
        if (static::isLoggedIn())
        {
            static::displayLoggedInHeader();
            if (static::countRows("posts"))
            {
                $actual = static::populateGallery();
                if (count($actual))
                {
                    for ($j = 0; $j  < count($actual) && $j < 15; $j++)
                    {
                        static::displayPic($actual[$j]);
                    }
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