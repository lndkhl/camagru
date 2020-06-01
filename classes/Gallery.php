<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Gallery extends Users
{
    public static function main_()
    {
        if (!static::isLoggedIn())
        {
            echo '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>
                            camagru
                        </title>
                    <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
                    <link rel="shortcut icon" href="forward.ico">
                    </head>
    
                    <div class="wrapper">
                    <body>
                    <header>
                        <h1 class="title">camagru</h1>
                    </header><!-- end of header -->

                    <div class="inner">
                    <nav>
                    <p> 
                        <ul>
                            <li><a href="create-account">create account</a></li>
                            <li><a href="home">home</a></li>
                            <li><a href="login">login</a></li>
                        </ul>
                    </p>
                    </nav><!-- end of links -->
                    <section class="main">';

                    /*end of header html*/

            if (static::countRows("posts"))
            {
                $actual = array();
                for ($i = 0; $i < static::countRows("posts"); $i++)
                {
                    $posts = static::fetchPosts();
                    if ($posts)
                    {
                        if (file_exists("uploads"))
                        {
                            if (count(scandir("uploads")))
                            {
                                $uploads = scandir("uploads");
                                if (in_array($posts[$i]['imgname'], $uploads))
                                {
                                    array_push($actual, $posts[$i]['imgname']);
                                }
                            }
                        }
                    }
                }
                $open_div = -1;
                if (count($actual))
                {
                    for ($j = 0; $j  < count($actual); $j++)
                    {
                        if ($j % 3 == 0) { echo '</div>'; }
                        if ($j % 3 == 0 || $j == 0)
                        {    
                            echo '<div class="row">';
                            $open_div *= -1;
                            $class = "left";
                        }
                        else if  (($j - 1) % 3 == 0){ $class = "mid"; }
                        else { $class = "right"; }
                        //echo '<span class="' . $class . '">' . $actual[$j] . '</span>';
                        echo '<span class= "' . $class . '"><img src="uploads/' . $actual[$j] . '" /></span>';                                    
                    }
                }
                if ($open_div == 1) { echo '</div>'; }
            }

            /*footer html*/         

            echo '</section> <!-- end of main-->
                    </div><!-- end of inner -->
                    <footer>
                    <p>"<em>oop</em>"</p>
                    </footer><!-- end of footer -->
                    </body>
                    </div><!-- end of wrapper -->
                    </html>';
        }
        else
        {
            Route::redirect("pro-gallery");
            exit();
        }
        //static::create_view("gallery");
    }
}
?>