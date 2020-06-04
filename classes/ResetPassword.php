<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ResetPassword extends Users
{
    private static function reset($user_id, $email)
    {
        $subject = "Camagru password reset";
        $cryptographically_strong = true;
        $message = "Click the following link or copy and paste it into your browser to reset your password: ";
        $project_root = static::get_project_root("reset-password");
        $link = $project_root . "forgot-password?poken=";
        $poken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        if (mail($email, $subject, $message . $link . $poken))
        {
            static::query('INSERT INTO ' .  static::get_db_name()  .  '.pokens (poken, user_id) VALUES (:poken, :user_id)',
                        array(':poken'=>sha1($poken), ':user_id'=>$user_id));
            echo "Password-reset link sent";
        }
        else { echo "Something went wrong, please try again"; }
    }

    public static function main_()
    {
        if (isset($_POST['resetpassword']))
        {
            $email = $_POST['email'];
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if (static::emailExists($email))
                {
                    $user_id = static::emailExists($email);
                    static::reset($user_id, $email);                    
                }
                else{ echo "The email address entered does not belong to a registered user"; }
            }
            else{ echo "Invalid email address entered"; }
        }
        static::create_view("reset-password");
    }
}