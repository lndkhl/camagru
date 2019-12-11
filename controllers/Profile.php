<?php
    class Profile extends Controller
    {
        public static function main_()
        {
            $username = "";

            if (isset($_GET['username']))
            {
                if (query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$_GET['username'])))
                {
                    $username = query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                }
                else
                {
                    die ("<br>User not found!<br>");
                }
            }
            else
            {
                die ("<br>You are not logged in!<br>");
            }

        }
    }
?>