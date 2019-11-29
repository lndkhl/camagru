<?php
class CreateDatabase extends Controller
{
    public static function reset()
    {
        if (isset($_POST['recreate']))
        {
            require_once('./setup.php');
        }
    }
}