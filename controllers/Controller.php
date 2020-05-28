<?php
class Controller extends database
{
    public static function create_view($viewName)
    {
        require_once("./views/$viewName.php");
    }
}
?>