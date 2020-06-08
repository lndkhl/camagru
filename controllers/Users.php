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

    public static function displayCommentBox($imgname)
    {
        if (static::isLoggedIn())
        {
            echo '<div class="row"><form method="post" class="comment">
                    <textarea id="' . $imgname . '" maxlength="140" name="commentstring" class="textbox"></textarea>
                    <input type="submit" name="comment" value="post">
                    </form>
                    </div>';
        }
    }

    public static function displayPic($source)
    {
        if (static::picExists($source))
        {
            $location = "gallery"; 
            echo '<div class="row">
                    <span class= "post">
                    <figure class="cap">        
                        <img src="uploads/' . $source . '" class="pic" />
                        <figcaption>
                            <div class="caption">
                                <button class="likes" id="' .$source . '"></button>
                                <span class="likecount">' . static::getLikes($source) . '</span>';
                                if (static::ownPic($source))
                                {
                                    echo '<button class="deletes" id="' . $source . '"></button>';
                                    echo '<a href=uploads/' . $source . ' download><button class="downloads" id="' . $source . '"></button></a>';
                                }
                                echo '<span class="commcount">' . static::getCommentCount($source) . '</span>
                                <a href="' . $location . '?post=' . $source . '"><button class="comments" id="' .$source . '"></button></a>
                            </div>
                        </figcaption>
                    </figure>
                </span>
                </div>';
        }
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
                    static::displayCommentBox($post);
                }
                static::displayComments($post);
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
            if ((($page_index + 1) * $ppp) < count($actual)) { static::displayNext($page_index, $caller); }
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