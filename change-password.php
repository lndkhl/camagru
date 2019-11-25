<?php
{
    include_once("./classes/DB.php");
    include_once("./classes/Login.php");

    $tokenIsValid = False;
    if (Login::isLoggedIn())
    {
        echo Login::isLoggedIn();
        echo " is logged in!";

        $oldpassword = $_POST["oldpassword"];
        $newpassword = $_POST["newpassword"];
        $newpassword_ = $_POST["newpassword_"];
        $user_id = Login::isLoggedIn();

        if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:user_id', array(":user_id"=>$user_id))[0]['password']))
        {
            echo "old password verified";
            if ($newpassword == $newpassword_)
            {
                if (strlen($newpassword) >= 8 && strlen($newpassword) <= 30)
                {
                    $hashpassword = password_hash($newpassword, PASSWORD_BCRYPT);
                    DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(":newpassword"=>$hashpassword, ":userid"=>$user_id));
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
    else
    {
        if (isset($_GET['token']))
        {
            $token = $_GET['token'];
            if (DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>$token)))
            {
                $tokenIsValid = True;
                $user_id = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>$token))[0]['user_id'];
                if (isset($_POST['changepassword']))
                {
                    $newpassword = $_POST['newpassword'];
                    $newpassword_ = $_POST['newpassword_'];

                    if ($newpassword == $newpassword_)
                    {
                        if (strlen($newpassword) >= 8 && strlen($newpassword) <= 30)
                        {
                            $hashpassword = password_hash($newpassword, PASSWORD_BCRYPT);
                            DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(":newpassword"=>$hashpassword, ":userid"=>$user_id));
                            echo "Password changed successfully!";
                            DB::query('DELETE FROM password_tokens WHERE user_id=:user_id', array(':user_id'=>$user_id));
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
        else
        {
            die("You are not logged in!");
        }
    }
}
?>
<h1>Change your Password</h1>
<form action="<?php if (!$tokenIsValid) {echo "change-password.php";} else {echo "change-password.php?token=$token";} ?>" method ='post'>
    <?php if (!$tokenIsValid) {echo "<input type='password' name='oldpassword' value='' placeholder='Current Password...'></p>";} ?>
    <input type='password' name='newpassword' value='' placeholder='New Password...'></p>
    <input type='password' name='newpassword_' value='' placeholder='New Password Again...'></p>
    <input type="submit" name="changepassword" value="Change Password">
</form>