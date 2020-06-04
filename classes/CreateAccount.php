<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CreateAccount extends Users
{
    private static function registerUser($username, $password, $email)
   {
        $verified = 0;
        $notifications = 1;
        $subject = "Camagru user verification";
        $cryptographically_strong = true;
        $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        $message = "Click the following link, or copy and paste it into your browser, to complete the registration process: ";
        $project_root = static::get_project_root("create-account");
        $link = $project_root . "home?voken=";
        
        static::query('INSERT INTO ' . static::get_db_name() . '.users (username, password, email, verified, notifications) VALUES (:username, :password, :email, :verified, :notifications)', 
                                    array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':verified'=>$verified, ':notifications'=>$notifications));
        echo "Registration succesfull!<br>";
        if (mail($email, $subject, $message . $link . $voken))
        {
            static::query('INSERT INTO ' . static::get_db_name() . '.vokens (voken, user_id) VALUES (:voken, :user_id)',
                array(':voken'=>sha1($voken), ':user_id'=>static::query('SELECT id FROM ' . static::get_db_name() . '.users WHERE email=:email', array(':email'=>$email))[0]['id']));
            echo "Email verification link sent, verify your email to get started";
        }
        else { echo "We are experiencing difficulty sending you a verification email, please try again"; }
    }

    public static function main_()
    {
        if (isset($_POST['createaccount']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppword = $_POST['reenterpassword'];
            $email = $_POST['email'];

            if (!static::userExists($username))
            {
                if (static::validUsername($username))
                {
                    if (static::validUsernameLength($username))
                    {
                        if (static::validPasswordComplexity($password))
                        {
                            if(static::validPasswordLength($password))
                            {
                                if ($password === $reppword)
                                {
                                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                                    {
                                        if (!static::emailExists($email))
                                        {
                                                static::registerUser($username, $password, $email);
                                        }
                                        else { echo "Email address in use"; }
                                    }    
                                    else { echo "Invalid email address"; }
                                }
                                else { echo "Passwords do not match"; }
                            }   
                            else { echo "Invalid password (minimum 8 characters)"; }
                        }
                        else { echo "Invalid password<br>Password must consist of at least:<br>- 1 lower case letter<br>- 1 upper case letter<br>- 1 digit<br>"; }
                    }
                    else { echo "Invalid username<br>Username must be at least 3 characters long"; }
                }
                else { echo "Invalid username<br>Username can only consist of lower case letters and (optionally) digits and/or the underscore character"; }
            }
            else { echo "User already exists"; }
        }
        static::create_view("create-account");
    }
}
?>