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
        if (isset($_GET['poken']))
        {
            if (static::query('SELECT user_id FROM ' .  static::get_db_name()  .  '.pokens WHERE poken=:poken', array(':poken'=>sha1($_GET['poken']))))
            {
                $GLOBALS['change_id'] = static::query('SELECT user_id FROM ' .  static::get_db_name()  .  '.pokens WHERE poken=:poken', array(':poken'=>sha1($_GET['poken'])))[0]['user_id'];
                if (isset($_POST['changepassword']))
                {
                    $newpword = $_POST['newpassword'];
                    $reppword = $_POST['reppassword'];
                    if (static::validPasswordComplex($newpword))
                    {
                        if (static::validPasswordLength($newpword))
                        {
                            if ($newpword == $reppword)
                            {
                               
                                static::updatePassword($newpword);
                                static::query('delete from ' .  static::get_db_name()  .  '.pokens where user_id=:user_id', array(':user_id'=>$change_id));
                                unset($GLOBALS['change_id']);
                                Home::main_();
                                exit();
                               
                            }
                            else
                            {
                                echo "Passwords do not match";
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