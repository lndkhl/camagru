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
    //Route::set("", function () {Controller::create_view("home");});
    Route::set("home", function () {Home::create_view("home");});
    Route::set("gallery", function () {Gallery::create_view("gallery");});
    Route::set("profile", function () {Profile::create_view("profile");});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>