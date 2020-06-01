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
            
            if (static::countRows("posts"))
            {
                $open_div = -1;
                for ($i = 0; $i < static::countRows("posts") && $i < 15; $i++)
                {
                    if ($i % 3 == 0)
                    {    
                        echo '</div>';
                    }
                    if ($i % 3 == 0 || $i == 0)
                    {    
                        echo '<div class="row">';
                        $open_div *= -1;
                    }
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
                                    echo '<img src="uploads/' . $posts[$i]['imgname'] . '" class="gallery" />';                                    
                                }
                            }
                        }
                    }
                }
                if ($open_div == 1)
                {
                    echo '</div>';
                }
            }
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