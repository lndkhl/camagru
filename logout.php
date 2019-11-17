<?php
include_once('./classes/DB.php');
include_once("./classes/Login.php");

if(!Login::isLoggedIn())
{
    die ("Not logged in"); 
}
if (isset($_POST['confirm']))
{    
    $hashcookie = sha1($_COOKIE["CID"]);
    if (isset($_POST["alldevices"]))
    {
        DB::query('DELETE FROM tokens WHERE user_id=:user_id', array(':user_id'=>Login::isLoggedIn()));
    }
    else
    {
        if (isset($_COOKIE["CID"]))
        {
            DB::query('DELETE FROM tokens WHERE token=:token', array(':token'=>$hashcookie));
            setcookie("CID", 1, time()-1000);
            setcookie("CID_REFRESH", 1, time()-1000);
        }    
    }
}
?>

<h1>Logout of your Account</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
    <input type="checkbox" name="alldevices" value="alldevices"> Logout of all devices? <br>
    <input type="submit" name="confirm" value="Confirm">
</form>