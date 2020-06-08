<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Gallery extends Users
{
    private static function likePic($imgname)
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
                $notify = static::query('SELECT notifications FROM ' . static::get_db_name() . '.users WHERE id=:id', array(':id'=>$poster))[0]['notifications'];
                static::query('UPDATE ' . static::get_db_name() . '.posts SET comments=:comments WHERE imgname=:imgname', 
                                array(':comments'=>(++$comments), ':imgname'=>$imgname));
                static::query('INSERT INTO ' . static::get_db_name() . '.comments (comment, post_id, user_id) VALUES (:comment, :post_id, :user_id)',
                                array(':comment'=>$comment, ':post_id'=>$post_id, ':user_id'=>$commenter));
                if ($notify)
                {
                    static::commentNotification($poster, $comment);
                }
                Route::redirect("gallery?post=" . $imgname);
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
                if (static::query('SELECT id FROM ' . static::get_db_name() . '.comments WHERE post_id=:post_id', array(':post_id'=>$post_id)))
                {
                    static::query('DELETE FROM ' . static::get_db_name() . '.comments WHERE post_id=:post_id', array(':post_id'=>$post_id));
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

    private static function displayLoggedOutHeader()
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

    private static function displayLoggedInHeader()
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
                        <li><a href="profile">profile</a></li>
                        <li><a href="settings">settings</a></li>
                    </ul>
                </p>
                </nav><!-- end of links -->
                <section class="main">';
    }

    public static function main_()
    {
        if (static::isLoggedIn()) { static::parseUserInput(); }
        if (static::isLoggedIn()){ static::displayLoggedInHeader(); }
        else { static::displayLoggedOutHeader(); }
        if (isset($_GET['post']))
        {
            if (static::pic_in_db($_GET['post']))
            {
                static::displayPost();
            }
            else
            {
                route::redirect("gallery");
            }
        }
        else if (static::countRows("posts"))
        {
            $actual = static::populateGallery();
            if (count($actual))
            {
                static::displayPage($actual, "gallery");
            }
        }
        echo '<script src="./js/gallery.js"></script>';
        static::displayFooter();
    }
}
?>