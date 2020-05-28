<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ForgotPassword extends Users
{
    public static function main_()
    {
        if (isset($_GET['poken']))
        {
            if (static::query('SELECT user_id FROM camagru.pokens WHERE poken=:poken', array(':poken'=>sha1($_GET['poken']))))
            {
                $newpword = $_POST['newpassword'];
                $reppword = $_POST['reppassword'];
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
                    echo "\'new password\' does not match \'repeat new password\'";
                }
            }
            else
            {
                die ("Invalid password-reset token");
            }
        }
        static::create_view("forgot-password");
    }
}
?>