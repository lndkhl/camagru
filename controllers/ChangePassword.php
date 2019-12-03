<?php
class ChangePassword extends Controller
{
    public static function main_()
    {
        $tokenIsValid = False;
        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            if (DB::query('SELECT user_id FROM camagru.password_tokens WHERE token=:token', array(':token'=>$token)))
            {
                $tokenIsValid = True;
                $user_id = DB::query('SELECT user_id FROM camagru.password_tokens WHERE token=:token', array(':token'=>$token))[0]['user_id'];
                if (isset($_POST['changepassword']))
                {
                    $newpassword = $_POST['newpassword'];
                    $newpassword_ = $_POST['newpassword_'];

                    if ($newpassword == $newpassword_)
                    {
                        if (strlen($newpassword) >= 8 && strlen($newpassword) <= 30)
                        {
                            $hashpassword = password_hash($newpassword, PASSWORD_BCRYPT);
                            DB::query('UPDATE camagru.users SET password=:newpassword WHERE id=:userid', array(":newpassword"=>$hashpassword, ":userid"=>$user_id));
                            echo "Password changed successfully!";
                            DB::query('DELETE FROM camagru.password_tokens WHERE user_id=:user_id', array(':user_id'=>$user_id));
                        }
                        else
                        {
                            echo "Password must be minimum 8 characters long";
                        }
                    }  
                }
            }
            else
            {
                die("Invalid token!");
            }
        }
        else if (Login::isLoggedIn())
        {
            if (isset($_POST["oldpassword"]))
            {
                $oldpassword = $_POST["oldpassword"];
                $newpassword = $_POST["newpassword"];
                $newpassword_ = $_POST["newpassword_"];
                $user_id = Login::isLoggedIn();

                if (password_verify($oldpassword, DB::query('SELECT password FROM camagru.users WHERE id=:user_id', array(":user_id"=>$user_id))[0]['password']))
                {
                    echo "Old password verified <br>";
                    if ($newpassword == $newpassword_)
                    {
                        if (strlen($newpassword) >= 8 && strlen($newpassword) <= 30)
                        {
                            $hashpassword = password_hash($newpassword, PASSWORD_BCRYPT);
                            DB::query('UPDATE camagru.users SET password=:newpassword WHERE id=:userid', array(":newpassword"=>$hashpassword, ":userid"=>$user_id));
                            echo "Password changed successfully!";
                        }
                        else
                        {
                            echo "Password must be minimum 8 characters long";
                        }
                    }
                    else
                    {
                        echo "Passwords don't match!";
                    }
                }
                else
                {
                    echo "Incorrect password entered!";
                }
            }
        }
        else
        {
            die("You are not logged in!");
        }
    }
}
?>