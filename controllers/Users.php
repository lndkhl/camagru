<?php
class Users extends Controller
{
    public static function isLoggedIn()
    {
        if (isset($_COOKIE['CamagruID']))
        {
            if (static::query('SELECT user_id FROM ' . static::get_db_name() . '.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID']))))
            {
                $user_id = static::query('SELECT user_id FROM ' . static::get_db_name() . '.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])))[0]['user_id'];
                if (isset($_COOKIE['CamagruID']))
                {
                    return $user_id;
                }
                else
                {
                    $cryptographically_strong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                    static::query('INSERT INTO ' . static::get_db_name() . '.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                    static::query('DELETE FROM camgru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])));
                    setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
                    setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
                    return $user_id;
                }
            }
        }
        return FALSE;
    }

    public static function getUsername($user_id)
    {
        if (static::query('SELECT username FROM ' . static::get_db_name() . '.users WHERE id=:user_id', array(':user_id'=>$user_id)))
        {
            $username = static::query('SELECT username FROM ' . static::get_db_name() . '.users WHERE id=:user_id', array(':user_id'=>$user_id))[0]['username'];
            return $username;
        }
        return FALSE;
    }

    public static function userExists($username)
    {
        if (static::validUsername($username))
        {
            if (static::query('SELECT username FROM ' .static::get_db_name() . '.users WHERE username=:username', 
                            array(':username'=>$username)))
            {
                $user_id = static::query('SELECT id FROM ' . static::get_db_name() . '.users WHERE username=:username',
                            array(':username'=>$username))[0]['id'];
                return $user_id;
            }
        }
        return FALSE;
    }

    public static function emailExists($email)
    {
        if (static::query('SELECT email FROM ' . static::get_db_name() . '.users WHERE email=:email', array(':email'=>$email)))
        {
            $user_id = static::query('SELECT id FROM ' .  static::get_db_name()  .  '.users WHERE email=:email', array(':email'=>$email))[0]['id'];
            return $user_id;
        }
        return FALSE;
    }

    public static function validUsername($username)
    {
        if (preg_match('/^[a-z0-9_]+$/', $username))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function validUsernameLength($username)
    {
        if (preg_match('/.{3}/', $username))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function validPasswordComplexity($password)
    {
        if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/', $password))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function validPasswordLength($password)
    {
        if (preg_match('/.{8}/', $password))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function populateGallery()
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
        return $actual;
    }

    public static function uploadPic($imgname, $user_id)
    {
        $likes = 0;
        static::query('INSERT INTO ' . static::get_db_name() . '.posts (imgname, likes, user_id) VALUES (:imgname, :likes, :user_id)',
                    array(':imgname'=>$imgname, ':likes'=>$likes, ':user_id'=>$user_id));
        echo "Image uploaded successfully<br>";
    }

    public static function displayPic($source)
    {
        $class = "post";
        
        echo '<div class="row">';
        /* echo '<span class="' . $class . '">' . $source . '</span>'; */
        echo '<span class= "' . $class . '">
                <figure class="cap">        
                <img src="uploads/' . $source . '" class="pic" />
                <figcaption><button class="likes">like</button><button class= "comments">comment</button></figcaption>
                </figure>
                </span>
                </div>';
    }

    public static function displayPage($actual, $caller)
    {
        $ppp = 7;
        
        if (isset($_GET['page'])) { $page_index = $_GET['page']; }
        else { $page_index = 0; }
        if (is_numeric($page_index) && $page_index >= 0 &&( $page_index * $ppp <= count($actual) ))
        {
            for ($j = ($page_index * $ppp), $j >= 0; $j  < count($actual) && $j < (($page_index + 1)  * $ppp); $j++)
            {
                //echo "page index = " . $page_index . "br";
                static::displayPic($actual[$j]);
            }
            if ($page_index) { static::displayPrev($page_index, $caller); }
            if ((($page_index + 1) * $ppp) <= count($actual)) { static::displayNext($page_index, $caller); }
        }
        else { die("page does not exist"); }        
    }

    public static function displayPrev($current, $caller)
    {
        echo '<a href="' . static::get_project_root($caller) . '' . $caller . '?page=' . (--$current) . '" class="paging">prev</a>';
    }

    private static function displayNext($current, $caller)
    {
        echo '<a href="' . static::get_project_root($caller) . '' . $caller . '?page=' . (++$current) . '" class="paging">next</a>';
    }


    public static function displayLoggedOutHeader()
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
    }

    public static function displayLoggedInHeader()
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
                <header>
                    <h1 class="title">camagru</h1>
                </header><!-- end of header -->

                <div class="inner">
                <nav>
                <p> 
                    <ul>
                        <li><a href="logout">logout</a></li>
                        <li><a href="profile">profile</a></li>
                        <li><a href="settings">settings</a></li>
                    </ul>
                </p>
                </nav><!-- end of links -->
                <section class="main">';
    }


    public static function displayFooter()
    {
        echo '</section> <!-- end of main-->
                </div><!-- end of inner -->
                <footer>
                <p>"<em>oop</em>"</p>
                </footer><!-- end of footer -->
                </body>
                </div><!-- end of wrapper -->
                </html>';
    }
}

?>