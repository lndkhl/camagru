<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_index;

class Gallery extends Users
{
    private static function displayPrev($current)
    {
        echo '<a href="' . static::get_project_root("gallery") . 'gallery?page=' . (--$current) . '">prev</a>';
    }

    private static function displayNext($current)
    {
        echo '<a href="' . static::get_project_root("gallery") . 'gallery?page=' . (++$current) . '">next</a>';
        /*
        echo '<form method="post">
                <input type="submit" name="next" value="next" class="paging">
                </form>';
        */
    }

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
                    $ppp = 7;
                    if (isset($_GET['page'])) { $page_index = $_GET['page']; }
                    else { $page_index = 0; }
                    for ($j = ($page_index * $ppp), $j >=0; $j  < count($actual) && $j < (($page_index * $ppp) + $ppp); $j++)
                    {
                        //echo "page index = " . $page_index . "br";
                        static::displayPic($actual[$j]);
                    }
                    if ($page_index) { static::displayPrev($page_index); }
                    if (($page_index * $ppp) < count($actual)) { static::displayNext($page_index); }
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