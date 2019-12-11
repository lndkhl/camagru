<?php
     require_once('./resources/routes.php');

    function __autoload($class_name)
    {
        if (file_exists('./classes/'.$class_name.'.php'))
        {
            require_once './classes/'.$class_name.'.php';
        }
        if (file_exists('./controllers/'.$class_name.'.php'))
        {
            require_once './controllers/'.$class_name.'.php';
        }
    }
?>
