<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Profile extends Users
{
    public static function main_()
    {
        static::create_view("profile");
    }
}
?>