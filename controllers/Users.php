<?php
class Users extends Controller
{
    public static function isLoggedIn()
    {
        if (isset($_COOKIE['CamagruID']))
        {
            if (static::query('SELECT user_id FROM camagru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID']))))
            {
                $user_id = static::query('SELECT user_id FROM camagru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])))[0]['user_id'];
                if (isset($_COOKIE['CamagruID']))
                {
                    return $user_id;
                }
                else
                {
                    $cryptographically_strong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                    static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                    static::query('DELETE FROM camgru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])));
                    setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
                    setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
                    return $user_id;
                }
            }
        }
        return FALSE;
   }

   public static function userExists($username)
   {
        if (static::validUsername($username))
        {
            if (static::query('SELECT username FROM camagru.users WHERE username=:username', 
                            array(':username'=>$username)))
            {
                return TRUE;
            }
        }
        else
        {
            return FALSE;
        }
   }

   public static function validUsername($username)
   {
        if (preg_match('/^[a-z0-9_]+$/', $username))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
   }

   public static function validUsernameLength($username)
   {
        if (preg_match('/.{3}/', $username))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
   }

   public static function validPasswordComplexity($password)
   {
        if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/', $password))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
   }

   public static function validPasswordLength($password)
   {
        if (preg_match('/.{8}/', $password))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
   }

   public static function emailExists($email)
   {
        if (static::query('SELECT email FROM camagru.users WHERE email=:email', array(':email'=>$email)))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
   }

   public static function registerUser($username, $password, $email)
   {
        $verified = 0;
        $notifications = 1;
        $subject = "Camagru user verification";
        $cryptographically_strong = true;
        $message = "Click the following link, or copy and paste it into your browser, to complete the registration process: ";
        $link = "http://127.0.0.1/camagru/home?voken=";
        $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        
        static::query('INSERT INTO camagru.users (username, password, email, verified, notifications) VALUES (:username, :password, :email, :verified, :notifications)', 
        array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':verified'=>$verified, ':notifications'=>$notifications));
        echo "Registration succesfull!<br>";
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

}
?>