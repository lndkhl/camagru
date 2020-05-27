<?php
class Login extends Users
{
    function main_()
    {
        setup::initialize();
        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (static::query('SELECT username FROM camagru.users WHERE username=:username', array(':username'=>$username)))
            {
                if(strlen($password) >= 8 && strlen($password) <= 30)
                {
                    static::query('INSERT INTO camagru.users (username, password, email) VALUES (:username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                    echo "User succesfully registered!<br>";
                            
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