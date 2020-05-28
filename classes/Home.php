<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Home extends Users
{
    public static function main_()
    {
        setup::initialize();
        static::create_view("home");        
    }
}
?>