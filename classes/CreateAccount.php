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
                            echo "Invalid password<br>Password must consist of at least:<br>- 1 lower case letter<br>- 1 upper case letter<br>- 1 digit<br>";
                        }
                    }
                    else
                    {
                        echo "Invalid username<br>Username must be at least 3 characters long";
                    }
                }
                else
                {
                    echo "Invalid username<br>Username can only consist of lower case letters and (optionally) digits and/or the underscore character";
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