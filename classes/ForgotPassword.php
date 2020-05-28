<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$change_id;

class ForgotPassword extends Users
{
    public static function main_()
    {
        global $change_id;
        $redirect = FALSE;
        if (isset($_GET['poken']))
        {
            if (static::query('SELECT user_id FROM camagru.pokens WHERE poken=:poken', array(':poken'=>sha1($_GET['poken']))))
            {
                $GLOBALS['change_id'] = static::query('SELECT user_id FROM camagru.pokens WHERE poken=:poken', array(':poken'=>sha1($_GET['poken'])))[0]['user_id'];
                echo "changing password for user " . $change_id;
                if (isset($_POST['changepassword']))
                {
                    $newpword = $_POST['newpassword'];
                    $reppword = $_POST['reppassword'];
                    if ($newpword == $reppword)
                    {
                        if(strlen($newpword) >= 8 && strlen($newpword) <= 30)
                        {
                            static::query('UPDATE camagru.users SET password=:newpassword WHERE id=:user_id',
                                array(':newpassword'=>password_hash($newpword, PASSWORD_BCRYPT), ':user_id'=>$change_id));
                            echo "Password changed successfully";
                            static::query('DELETE FROM camagru.pokens WHERE user_id=:user_id', array(':user_id'=>$change_id));
                            Home::main_();
                            $redirect = TRUE;
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
            }
            else
            {
                die ("Invalid password-reset token");
            }
        }
        if (!$redirect)
        {
            static::create_view("forgot-password");
        }
    }
}
?>