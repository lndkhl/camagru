<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ChangeEmail extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['changeemail']))
            {
                $email = $_POST['email'];
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    if (!static::query('SELECT email FROM ' . static::get_db_name . '.users WHERE email=:email', array(':email'=>$email)))
                    {
                        $verified = 0;
                        static::query('UPDATE camagru.users SET email=:email, verified=:verified WHERE id=:user_id',
                        array(':email'=>$email, ':verified'=>$verified, ':user_id'=>$user_id));
                        echo "New email address saved!<br>";
                        $subject = "Camagru email address update";
                        $cryptographically_strong = true;
                        $message = "Click the following link, or copy and paste it into your browser, to verify your email address: ";
                        $project_root = static::get_project_root("change-email");
                        $link = $project_root . "home?voken=";
                        $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                        if (mail($email, $subject, $message . $link . $voken))
                        {
                            static::query('INSERT INTO camagru.vokens (voken, user_id) VALUES (:voken, :user_id)',
                                array(':voken'=>sha1($voken), ':user_id'=>static::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email))[0]['id']));
                            echo "<br>Email verification link sent, veirfy your email before attempting to log in again<br>";
                        }
                    }
                    else
                    {
                        echo "That email address is unavailable";
                    }
                }
                else
                {
                    echo "Invalid email address";
                }
            }
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("change-email");
    }
}
?>