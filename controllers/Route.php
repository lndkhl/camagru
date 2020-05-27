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
    Route::set("home", function () {Home::create_view("home");});
    Route::set("gallery", function () {Gallery::create_view("gallery");});
    Route::set("profile", function () {Profile::create_view("profile");});
    Route::set("create-account", function () {CreateAccount::create_view("create-account");});
    Route::set("reset-password", function () {ResetPassword::create_view("reset-password");});
    Route::set("login", function () {Profile::create_view("login");});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>