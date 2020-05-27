<?php
class Login extends Users
{
    function main_()
    {
        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (static::query('SELECT username FROM camagru.users WHERE username=:username',
                array(':username'=>$username)))
            {    
                if(password_verify($password, static::query('SELECT password FROM camagru.users WHERE username=:username',
                 array(':username'=>$username))[0]['password']))
                {
                    echo "Welcome back ". $username . "<br>";
                    $cryptographically_strong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                    $user_id = static::query('SELECT id FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['id'];
                    static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                }
                else
                {
                    echo "Incorrect Password";
                }
            }
            else
            {
                echo "User not found";
            }
        }
    }
}
?>