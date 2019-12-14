<?php

class Home extends Controller
{
    public static function reset_db()
    {
        require_once ('./resources/setup.php');
        create_view('home');
    }

    public static function main_()
    {

    }
}
?>