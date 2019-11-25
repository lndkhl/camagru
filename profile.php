<?php

include_once("./classes/DB.php");
include_once("./classes/Login.php");

if (Login::isLoggedIn())
{
    echo Login::isLoggedIn();
    echo " is logged in!";
}
else
{
    echo "You are not logged in!";
}
?>