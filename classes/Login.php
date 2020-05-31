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
                if (static::validUsername($username))
                {
                    if (static::userExists($username))
                    {
                        $verified =static::query('SELECT verified FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['verified'];
                        if ($verified == 1)    
                        {
                            if(password_verify($password, static::query('SELECT password FROM camagru.users WHERE username=:username',
                                array(':username'=>$username))[0]['password']))
                            {
                                echo "Welcome back ". htmlspecialchars($username) . "<br>";
                                $cryptographically_strong = true;
                                $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                                $user_id = static::query('SELECT id FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['id'];
                                static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                                setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
                                setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
                                echo '<meta http-equiv="refresh" content="100;url=http://127.0.0.1/camagru/login">';
                                Route::redirect("home");
                                exit();
                            }
                            else
                            {
                                echo "Incorrect Password";
                            }
                        }
                        else
                        {
                            echo "Please follow the verification link sent to your email address";
                        }
                    }
                    else
                    {
                        echo "User not found";
                    }
                }
                else
                {
                    echo "Invalid characters entered in username";
                }
            }
        }
        else
        {
            Route::redirect("profile");
            exit();
        }
        static::create_view("login");
    }
}
?>