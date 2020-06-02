<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ChangeUsername extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['changeusername']))
            {
                $username = $_POST['username'];
                if (static::validUsername($username))
                {
                    if (!static::userExists($username))
                    {
                        if(static::validUsernameLength($username))
                        {
                            static::query('UPDATE ' .  static::get_db_name()  .  '.users SET username=:username WHERE id=:user_id',
                                array(':username'=>$username, ':user_id'=>$user_id));
                            echo "Username changed successfully";
                            Settings::main_();
                            exit();
                        }
                        else
                        {
                            echo "Invalid username<>Username must be at least 3 characters long";
                        }
                    }
                    else
                    {
                        echo "That username is unavailable";
                    }
                }
                else
                {
                    echo "Invalid username<br>Username can only consist of lower case letters and (optionally) the underscore character";
                }
            }
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("change-username");
    }
}
?>