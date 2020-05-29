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
                                            static::query('INSERT INTO camagru.users (username, password, email) VALUES (:username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                            echo "User succesfully registered!<br>";
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