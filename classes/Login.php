<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Login extends Users
{
    private static function authenticateLogin($username, $password)
    {
       if (password_verify($password, static::query('SELECT password FROM ' .  static::get_db_name()  .  '.users WHERE username=:username',
       array(':username'=>$username))[0]['password']))
       {
           return TRUE;
       }
       return FALSE;
    }

    private static function checkVerification($username)
    {
        $verified = static::query('SELECT verified FROM ' .  static::get_db_name()  .  '.users WHERE username=:username', 
                                array(':username'=>$username))[0]['verified'];
        return $verified;
    }

    private static function setCookies($username)
    {
        $cryptographically_strong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        $user_id = static::query('SELECT id FROM ' .  static::get_db_name()  .  '.users WHERE username=:username', array(':username'=>$username))[0]['id'];
        
        static::query('INSERT INTO ' .  static::get_db_name()  .  '.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
        setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
        setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
    }

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
                        if (static::checkVerification($username))    
                        {
                            if(static::authenticateLogin($username, $password))
                            {
                                static::setCookies($username);
                                echo '<meta http-equiv="refresh" content="100;url=' . static::get_project_root("login") . '/login">';
                                Route::redirect("home");
                                exit();
                            }
                            else { echo "Incorrect Password"; }
                        }
                        else { echo "Please follow the verification link sent to your email address"; }
                    }
                    else { echo "User not found"; }
                }
                else { echo "Invalid characters entered in username"; }
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