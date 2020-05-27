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
    Route::set("create-account", function () {CreateAccount::create_view("create-account");});
    Route::set("reset-password", function () {ResetPassword::create_view("reset-password");});
    Route::set("login", function () {Login::create_view("login");});
    Route::set("profile", function () {if (!Users::isLoggedIn()){die ("You are not logged in");} else {Profile::create_view("profile");}});
    Route::set("logout", function () {if (!Users::isLoggedIn()){die ("You are not logged in");} else {Logout::create_view("logout");}});
    Route::set("change-password", function () {if (!Users::isLoggedIn()){die ("You are not logged in");} else {ChangePassword::create_view("change-password");}});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>