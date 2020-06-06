<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProGallery extends Users
{
    private static function commentPic($imgname, $comment)
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
                Route::redirect("pro-gallery?post=" . $imgname);
            }
        }
    }

    private static function commentNotification($poster, $comment)
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

    private static function deletePic($imgname)
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

    private static function parseUserInput()
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

    public static function main_()
    {
        if (static::isLoggedIn())
        {
            static::displayLoggedInHeader();
            if (isset($_GET['post']))
            {
                if (static::pic_in_db($_GET['post']))
                {
                    static::displayPost();
                }
                else
                {
                    route::redirect("pro-gallery");
                }
            }
            else if (static::countRows("posts"))
            {
                $actual = static::populateGallery();
                if (count($actual))
                {
                    static::displayPage($actual, "pro-gallery");
                }
            }
            echo '<script src="./js/gallery.js"></script>';
            static::parseUserInput();
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