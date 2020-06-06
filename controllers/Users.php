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
                if (static::picExists($posts[$i]['imgname']))
                {
                    array_push($actual, $posts[$i]['imgname']);
                }
            }
        }
        return $actual;
    }

    public static function uploadPic($imgname)
    {
        if (static::isLoggedIn())
        {
            $likes = 0;
            $comments = 0;
            $user_id = static::isLoggedIn();
            static::query('INSERT INTO ' . static::get_db_name() . '.posts (imgname, likes, comments, user_id) VALUES (:imgname, :likes, :comments, :user_id)',
                        array(':imgname'=>$imgname, ':likes'=>$likes, ':comments'=>$comments, ':user_id'=>$user_id));
            echo "Image uploaded successfully<br>";
        }
    }

    public static function ownPic($imgname)
    {
        if(static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (static::query('SELECT user_id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname',
                            array(':imgname'=>$imgname)))
            {
                $owner = static::query('SELECT user_id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname',
                                array(':imgname'=>$imgname))[0]['user_id'];
                if ($user_id == $owner)
                {
                    return TRUE;
                }
            }
            return FALSE;
        }
    }

    public static function getLikes($imgname)
    {
        if (static::query('SELECT likes FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname)))
        {
            return (static::query('SELECT likes FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['likes']);
        }
        return (0);
    }

    public static function getCommentCount($imgname)
    {
        if (static::query('SELECT comments FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname)))
        {
            return (static::query('SELECT comments FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['comments']);
        }
        return (0);  
    }

    public static function displayPic($source)
    {
        if (static::picExists($source))
        {
            echo '<div class="row">';
            /* echo '<span class="' . $class . '">' . $source . '</span>'; */
            echo '<span class= "post">
                    <figure class="cap">        
                        <img src="uploads/' . $source . '" class="pic" />
                        <figcaption>
                            <div class="caption">
                                <button class="likes" id="' .$source . '"></button>
                                <span class="likecount">' . static::getLikes($source) . '</span>';
                                if (static::ownPic($source))
                                {
                                    echo '<button class="deletes" id="' . $source . '"></button>';
                                }
                                echo '<span class="commcount"><a href="pro-gallery?post=' . $source . '">' . static::getCommentCount($source) . '</a></span>
                                <button class="comments" id="' .$source . '"></button>
                            </div>
                        </figcaption>
                    </figure>
                </span>
                </div>';
        }
    }

    public static function deletePic($imgname)
    {
        if (static::ownPic($imgname))
        {
            if (static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname)))
            {
                $post_id = static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['id'];
                if (static::query('SELECT id FROM ' . static::get_db_name() . '.likes WHERE post_id=:post_id', array(':post_id'=>$post_id)))
                {
                    static::query('DELETE FROM ' . static::get_db_name() . '.likes WHERE post_id=:post_id', array(':post_id'=>$post_id));
                }
                static::query('DELETE FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname));
            }
        }
    }

    public static function likePic($imgname)
    {
        if (static::isLoggedIn())
        {
            if (static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname)))
            {
                $post_id = static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['id'];
                $likes = static::getLikes($imgname);
                $user_id = static::isLoggedIn();
                if (static::query('SELECT id FROM ' . static::get_db_name() . '.likes WHERE post_id=:post_id AND user_id=:user_id',
                                array(':post_id'=>$post_id, ':user_id'=>$user_id)))
                {
                    $id = static::query('SELECT id FROM ' . static::get_db_name() . '.likes WHERE post_id=:post_id AND user_id=:user_id',
                                    array(':post_id'=>$post_id, ':user_id'=>$user_id))[0]['id'];
                    static::query('UPDATE ' . static::get_db_name() . '.posts SET likes=:likes WHERE imgname=:imgname', 
                                array(':likes'=>(--$likes), ':imgname'=>$imgname));
                    static::query('DELETE FROM ' . static::get_db_name() . '.likes WHERE id=:id', array(':id'=>$id));
                }
                else
                {
                    static::query('UPDATE ' . static::get_db_name() . '.posts SET likes=:likes WHERE imgname=:imgname', 
                                array(':likes'=>(++$likes), ':imgname'=>$imgname));
                    static::query('INSERT INTO ' . static::get_db_name() . '.likes (post_id, user_id) VALUES (:post_id, :user_id)',
                                array(':post_id'=>$post_id, ':user_id'=>$user_id));
                }
            }
        }
    }

    public static function commentBox($imgname)
    {
        if (static::isLoggedIn())
        {
            echo '<div class="row"><form method="post" class="comment">
                    <textarea id="' . $imgname . '" maxlength="140" name="commentstring" class="textbox">enter your comment here...</textarea>
                    <input type="submit" name="comment" value="post">
                    </form>
                    </div>';
        }
    }

    public static function commentPic($imgname, $comment)
    {
        if (static::isLoggedIn())
        {
            if (static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname)))
            {
                $post_id = static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['id'];
                $poster = static::query('SELECT user_id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname', array(':imgname'=>$imgname))[0]['user_id'];
                $comments = static::getCommentCount($imgname);
                $commenter = static::isLoggedIn();
                static::query('UPDATE ' . static::get_db_name() . '.posts SET comments=:comments WHERE imgname=:imgname', 
                                array(':comments'=>(++$comments), ':imgname'=>$imgname));
                static::query('INSERT INTO ' . static::get_db_name() . '.comments (comment, post_id, user_id) VALUES (:comment, :post_id, :user_id)',
                                array(':comment'=>$comment, ':post_id'=>$post_id, ':user_id'=>$commenter));
                static::commentNotification($poster, $comment);
            }
        }
    }

    public static function commentNotification($poster, $comment)
    {
        if (static::isLoggedIn())
        {
            $commenter = static::isLoggedIn();
            $commenter_name = static::query('SELECT username FROM ' . static::get_db_name() . '.users WHERE id =:id', array(':id'=>$commenter))[0]['username'];
            if (static::query('SELECT email FROM ' . static::get_db_name() . '.users WHERE id=:id', array(':id'=>$poster)))
            {
                $email = static::query('SELECT email FROM ' . static::get_db_name() . '.users WHERE id=:id', 
                                    array(':id'=>$poster))[0]['email'];
                $subject = "your post received a comment";
                $message = $commenter_name . " commented on your post: " . $comment;
                mail($email, $subject, $message); 
            }
        }
    }

    public static function parseUserInput()
    {
        if (static::isLoggedIn())
        {
            if (isset($_POST['delete']))
            {
                static::deletePic($_POST['delete']);
            }
            if (isset($_POST['like']))
            {
                static::likePic($_POST['like']);
            }
            if (isset($_POST['comment']))
            {
                if (isset($_GET['post']))
                {
                    $imgname = $_GET['post'];
                    static::commentPic($imgname, $_POST['commentstring']);
                }
            }
        }
    }

    public static function picExists($imgname)
    {
        if (file_exists("uploads"))
        {
            if (count(scandir("uploads")))
            {
                $uploads = scandir("uploads");
                if (in_array($imgname, $uploads))
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public static function pic_in_db($imgname)
    {
        if (static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname',
                        array(':imgname'=>$imgname)))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function displayPost()
    {
        if(isset($_GET['post']))
        {
            $post = $_GET['post'];
            if (static::picExists($post))
            {
                static::displayPic($post);
                if (static::isLoggedIn())
                {
                    static::commentBox($post);
                }
                static::displayComments($post);
            }        
        }
    }

    public static function displayComments($imgname)
    {
        if (static::picExists($imgname))
        {
            $post_id = static::query('SELECT id FROM ' . static::get_db_name() . '.posts WHERE imgname=:imgname',
                                    array(':imgname'=>$imgname))[0]['id'];
            if (static::query('SELECT id FROM ' . static::get_db_name() . '.comments WHERE post_id=:post_id', array(':post_id'=>$post_id)))
            {
                $poster_id = static::query('SELECT user_id FROM ' . static::get_db_name() . '.comments WHERE post_id=:post_id',
                                        array(':post_id'=>$post_id))[0]['user_id'];
                $poster_name = static::query('SELECT username FROM ' . static::get_db_name() . '.users WHERE id=:id',
                                        array(':id'=>$poster_id))[0]['username'];
                $comment = static::query('SELECT comment FROM ' . static::get_db_name() . '.comments WHERE post_id=:post_id',
                                        array(':post_id'=>$post_id));
                foreach ($comment as $comm) {echo '<div class="commentlist">' . $poster_name . ': ' . htmlspecialchars($comm['comment']) . '</div>';}
            }
        }
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
        echo '<span id="prev"><a href="' . static::get_project_root($caller) . '' . $caller . '?page=' . (--$current) . '">prev</a></span>';
    }

    private static function displayNext($current, $caller)
    {
        echo '<span id="next"><a href="' . static::get_project_root($caller) . '' . $caller . '?page=' . (++$current) . '">next</a></span>';
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