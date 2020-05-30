<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CreateAccount extends Users
{
    public static function main_()
    {
        if (isset($_POST['createaccount']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppword = $_POST['reenterpassword'];
            $email = $_POST['email'];

            if (!static::query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$username)))
            {
                if (preg_match('/[a-z_]/', $username) && preg_match('/[a-z]/', $username))
                {
                    if (preg_match('/.{3}/', $username))
                    {
                        if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/', $password))
                        {
                            if(preg_match('/.{8}/', $password))
                            {
                                if ($password === $reppword)
                                {
                                    if (filter_var($email, FILTER_VALIDATE_EMAIL))
                                    {
                                        if (!static::query('SELECT email FROM camagru.users WHERE email=:email', array(':email'=>$email)))
                                        {
                                            $verified = 0;
                                            $notifications = 1;
                                            static::query('INSERT INTO camagru.users (username, password, email, verified, notifications) VALUES (:username, :password, :email, :verified, :notifications)', 
                                                            array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':verified'=>$verified, ':notifications'=>$notifications));
                                            echo "Registration succesfull!<br>";
                                            $subject = "Camagru user verification";
                                            $cryptographically_strong = true;
                                            $message = "Click the following link, or copy and paste it into your browser, to complete the registration process: ";
                                            $link = "http://127.0.0.1/camagru/home?voken=";
                                            $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                                            if (mail($email, $subject, $message . $link . $voken))
                                            {
                                                static::query('INSERT INTO camagru.vokens (voken, user_id) VALUES (:voken, :user_id)',
                                                    array(':voken'=>sha1($voken), ':user_id'=>static::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email))[0]['id']));
                                                echo "Email verification link sent, verify your email to get started";
                                            }
                                            else
                                            {
                                                echo "We are experiencing difficulty sending you a verification email, please try again";
                                            }
                                        }
                                        else
                                        {
                                            echo "Email address in use";
                                        }
                                    }    
                                    else
                                    {
                                        echo "Invalid email address";
                                    }
                                }
                                else
                                {
                                    echo "Passwords do not match";
                                }
                            }   
                            else
                            {
                                echo "Invalid password (minimum 8 characters)";
                            }
                        }
                        else
                        {
                            echo "Invalid password<br>Password must consist of at least:<br>- 1 lower case letter<br>- 1 upper case letter<br>- 1 number<br>";
                        }
                    }
                    else
                    {
                        echo "Invalid username<br>Username must be at least 3 characters long";
                    }
                }
                else
                {
                    echo "Invalid username<br>Username can only consist of lower case letters and (optionally) the underscore character";
                }
            }
            else
            {
                echo "User already exists";
            }
        }
        static::create_view("create-account");
    }
}
?>