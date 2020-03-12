<?php
class Route
{
    public static $validRoutes = array();
    public static function set($route, $function)
    {
        self::$validRoutes[] = $route;
        if ($_GET["url"] == $route)
        {
            $function->__invoke();
        }
    }
}
try
{
    Route::set("", function () {Home::create_view("home");});
    Route::set("home", function () {Home::create_view("home");});
    Route::set("index", function () {Home::create_view("home");});
    Route::set("create-account", function () {CreateAccount::create_view("create-account");});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>