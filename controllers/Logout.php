<?php
class Logout extends Controller
{
    public static function main_()
    {
        if(!Login::isLoggedIn())
        {
            die ("You are not logged in!"); 
        }
        if (isset($_POST['confirm']))
        {    
            $hashcookie = sha1($_COOKIE["CID"]);
            if (isset($_POST["alldevices"]))
            {
                DB::query('DELETE FROM camagru.tokens WHERE user_id=:user_id', array(':user_id'=>Login::isLoggedIn()));
                echo "You have been logged out of all devices!<br>";
            }
            else
            {
                if (isset($_COOKIE["CID"]))
                {
                    DB::query('DELETE FROM camagru.tokens WHERE token=:token', array(':token'=>$hashcookie));
                    echo "You are now logged out!<br>";
                    setcookie("CID", 1, time()-1000);
                    setcookie("CID_REFRESH", 1, time()-1000);
                }     
            }
        }
    }
}
?>