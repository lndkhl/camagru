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
                        if (static::confirmVerification($username))    
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