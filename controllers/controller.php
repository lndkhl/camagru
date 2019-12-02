<?php
class Controller extends Database
{
    public static function create_view($viewName)
    {
        if ($viewName != "index")
        {
             require_once("./views/$viewName.html");
        }
        static::main_();
        //static::test();
    }
}
?>