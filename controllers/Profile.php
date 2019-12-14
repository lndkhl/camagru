<?php
    class Profile extends Controller
    {
        public static function main_()
        {
            $username = "";

            if ($username = Database::isLoggedIn())
            {
                
            }
            else
            {
                die ("<br>You are not logged in!<br>");
            }

        }
    }
?>