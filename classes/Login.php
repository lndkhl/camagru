<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Login extends Users
{
    public static function main_()
    {
        if(!static::isLoggedIn())
        {
            if (isset($_POST['login']))
            {
                $username = $_POST['username'];
                $password = $_POST['password'];

                if (static::query('SELECT username FROM camagru.users WHERE username=:username',
                    array(':username'=>$username)))
                {
                    $verified =static::query('SELECT verified FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['verified'];
                    echo "verified status = " . $verified;
                    if ($verified == 1)    
                    {
                        if(password_verify($password, static::query('SELECT password FROM camagru.users WHERE username=:username',
                            array(':username'=>$username))[0]['password']))
                        {
                            echo "Welcome back ". $username . "<br>";
                            $cryptographically_strong = true;
                            $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                            $user_id = static::query('SELECT id FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['id'];
                            static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                            setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
                            setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
                            Route::redirect("profile");
                            exit();
                        }
                        else
                        {
                            echo "Incorrect Password";
                        }
                    }
                    else
                    {
                        echo "Please verify your account";
                    }
                }
                else
                {
                    echo "User not found";
                }
            }
        }
        else
        {
            echo "Device logged in, logout if you would like to change user account";
        }
        static::create_view("login");
    }
}
?>