<?php
class Controller extends Users
{
    public static function create_view($viewName)
    {
        require_once("./views/$viewName.php");
        static::main_();
    }
}
?>