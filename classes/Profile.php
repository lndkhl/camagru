<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Profile extends Users
{
    private static function uploadPic($imgname)
    {
        if (static::isLoggedIn())
        {
            $likes = 0;
            $comments = 0;
            $user_id = static::isLoggedIn();
            static::query('INSERT INTO ' . static::get_db_name() . '.posts (imgname, likes, comments, user_id) VALUES (:imgname, :likes, :comments, :user_id)',
                        array(':imgname'=>$imgname, ':likes'=>$likes, ':comments'=>$comments, ':user_id'=>$user_id));
        }
    }

    private static function parsePic()
    {
        if (static::isLoggedIn())
        {
            if (isset($_POST['image']))
            {
                $image_string = $_POST['image'];
                $image_string = str_replace('data:image/png;base64,', '', $image_string);
                $image_string = str_replace(' ', '+', $image_string);
                $image_string = base64_decode($image_string);

                if (!file_exists("uploads"))
                {
                    mkdir("uploads", 0700, TRUE);
                }
                $pic = static::getUsername(static::isLoggedIn()). date('-Y-m-d_h:i:s') . '.png';
                $path = 'uploads/' . $pic;
                if (file_put_contents($path, $image_string) != FALSE)
                {
                    static::uploadPic($pic);
                    $return = array('status'=>200, 'message'=>"image uploaded successfully!");
                    http_response_code(200);      
                }
                else { $return = array('status'=>403, 'message'=>"image upload failed, please try again."); http_response_code(403); }
                print_r(json_encode($return));
            }
        }
    }

    private static function displayProfileHeader()
    {
        echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>
                        camagru
                    </title>
                <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
                <link rel="shortcut icon" href="favicon.ico">
                </head>

                <div class="wrapper">
                <body>
                <!--
                <header>
                    <h1 class="title">camagru</h1>
                </header>--><!-- end of header -->

                <div class="inner">
                <nav>
                <p> 
                    <ul>
                        <li><a href="logout">logout</a></li>
                        <li><a href="gallery">gallery</a></li>
                        <li><a href="settings">settings</a></li>
                    </ul>
                </p>
                </nav><!-- end of links -->
                <section class="main">';
    }

    private static function displayProfile()
    {
        echo '<div class="inner">
        <section class="main">
        
            <div class="stickers">
                <ul id="thefive">
                    <li><button id="sticker1" class="buttons"></button></li>
                    <li><button id="sticker2" class="buttons"></button></li>
                    <li><button id="sticker3" class="buttons"></button></li>
                    <li><button id="sticker4" class="buttons"></button></li>
                    <li><button id="sticker5" class="buttons"></button></li>
                </ul>
            </div>

            <div class="imgbox">
                <video id="video" autoplay="on"></video>
                <canvas id="canvas"></canvas>
                <div class="media-buttons">
                    <button id="snap">snap</button>
                    <button id="store">go!</button>
                    <button id="clear">clear</button>
                </div>
            
                <div id="alts">
                    <p>No webcam?</p>
                    <form  action="upload" method="post" id="uploadForm" enctype="multipart/form-data">
                    <input type="file" id="img" name= "img" />
                    <input type=submit name="upload" value="submit" id="upload" class="hidden">
                    </form>
                </div>
            </div>
            <div class="hidden">
                <canvas id="realcanvas"></canvas>
            </div>';
    }

    private static function displayProfileFooter()
    {
        echo '</section><!-- end of main -->

              </div><!-- end of inner -->
        
            <footer>
                <p>"<em>oop</em>"</p>
            </footer><!-- end of footer -->
            
            <div class="hidden">
            <img id="img1" src="stickers/pikachu.png" />
            <img id="img2" src="stickers/pikachu-happy.png" />
            <img id="img3" src="stickers/pepe.png" />
            <img id="img4" src="stickers/shades.png" />
            <img id="img5" src="stickers/deal-with-it-shades.png" />
            </div>

        </body>

        </div><!-- end of wrapper -->

        <script src="./js/profile.js"></script>

        </html>';
    }

    private static function displayUploads()
    {
        $actual = static::populateGallery();
        if ($actual)
        {
            echo '<span class="side">
                    <ul class="propage">';
            for ($i = 0; $i < 5 && $i < count($actual); $i++)
            {
                if (static::picExists($actual[$i]))
                {
                    echo '<li class="previews"><a href="gallery?post=' . $actual[$i] . '">
                    <button style="
                                background: url(./uploads/' . $actual[$i] . ');   
                                background-repeat: no-repeat;
                                background-size: contain;
                                background-position: center;
                                width: 15%;
                                min-width: 100px;
                                height: auto;
                                min-height: 50px;
                                margin-right: 2.5%;">
                    </button>
                    </a></li>';
                }
            }
            echo '</ul>
                </span>';
        }
    }
    
    public static function main_()
    {
        if(static::isLoggedIn())
        {
            static::displayProfileHeader();
            static::displayProfile();
            static::displayUploads();
            static::displayProfileFooter();
            static::parsePic();
        }
        else
        {
            Route::redirect("login");
            exit();
        }
    }
}
?>