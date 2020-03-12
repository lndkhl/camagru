<?php
require_once('./classes/Routes.php');

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
spl_autoload_register('__autoload');
?>