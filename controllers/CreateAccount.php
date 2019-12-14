<?php
class CreateAccount extends Controller
{
    public static function main_()
    {
        if (isset($_POST['createaccount']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            if (!static::query('SELECT username FROM camagru.users WHERE username= :username', array(':username'=>$username)))
            {
                if (strlen($username) >= 3 && strlen($username) <=30 && preg_match('/[a-zA-Z_]+/', $username))
                {
                    if(strlen($password) >= 8 && strlen($password) <= 30)
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
                                echo "Email in use!";
                            }
                        }    
                        else
                        {
                            echo "Invalid Email Address";
                        }
                    }
                    else
                    {
                        echo "Invalid Password (minimum 8 characters)";
                    }
                }
                else
                {
                    echo "Invalid Username";
                }
            }
            else
            {
                echo "User already exists!";
            }
        }
    }
}
?>