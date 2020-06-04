<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ChangeEmail extends Users
{
    private static function updateEmail($user_id, $email)
    {
        $verified = 0;
        static::query('UPDATE ' .  static::get_db_name()  .  '.users SET email=:email, verified=:verified WHERE id=:user_id',
        array(':email'=>$email, ':verified'=>$verified, ':user_id'=>$user_id));
        echo "New email address saved!<br>";
    }

    private static function sendVerificationLink($email)
    {
        $subject = "camagru email address update";
        $cryptographically_strong = true;
        $message = "Click the following link, or copy and paste it into your browser, to verify your email address: ";
        $project_root = static::get_project_root("change-email");
        $link = $project_root . "home?voken=";
        $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        if (mail($email, $subject, $message . $link . $voken))
        {
            static::query('INSERT INTO ' .  static::get_db_name()  .  '.vokens (voken, user_id) VALUES (:voken, :user_id)',
                array(':voken'=>sha1($voken), ':user_id'=>static::isLoggedIn()));
            echo "Email verification link sent, verify your email before attempting to log in again<br>";
            Settings::main_();
            exit();
        }
        else { echo "We are experiencing difficulty sending you a verification email, please try again"; }
    }

    public static function main_()
    {
        if (static::isLoggedIn())
        {
            if (isset($_POST['changeemail']))
            {
                $email = $_POST['email'];
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    if (!static::emailExists($email))
                    {
                        static::sendVerificationLink($email);
                    }
                    else { echo "That email address is unavailable"; }
                }
                else { echo "Invalid email address"; }
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