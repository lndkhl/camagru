<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ChangePassword extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            if (isset($_POST['changepassword']))
            {
                $password = $_POST['oldpassword'];
                $newpword = $_POST['newpassword'];
                $reppword = $_POST['reppassword'];
                if (password_verify($password, static::query('SELECT password FROM camagru.users WHERE id=:user_id',
                    array(':user_id'=>static::isLoggedIn()))[0]['password']))
                {
                    if (static::validPasswordComplexity($newpword))
                    {
                        if (static::validPasswordLength($newpword))
                        {
                            if ($newpword == $reppword)
                            {
                                if(strlen($newpword) >= 8 && strlen($newpword) <= 30)
                                {
                                    static::query('UPDATE camagru.users SET password=:newpassword WHERE id=:user_id',
                                        array(':newpassword'=>password_hash($newpword, PASSWORD_BCRYPT), ':user_id'=>static::isLoggedIn()));
                                    echo "Password changed successfully";
                                }
                                else
                                {
                                    echo "Invalid new password (minimum 8 characters)";
                                }
                            }
                            else
                            {
                                echo "new password not duplicated accurately, please try again";
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
                    echo "<br>You have entered an incorrect password<br>";
                }
            }
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("change-password");
    }
}
?>