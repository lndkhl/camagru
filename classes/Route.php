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
    /*Route::set("", function () {});*/
    Route::set("home", function () {Home::main_();});
    Route::set("gallery", function () {Gallery::main_();});
    Route::set("profile", function () {Profile::main_();});
    Route::set("change-password", function () {ChangePassword::main_();});
    Route::set("forgot-password", function () {ForgotPassword::main_();});
    Route::set("reset-passsword", function () {ResetPassword::main_();});
    Route::set("login", function () {Login::main_();});
    Route::set("logout", function () {Logout::main_();});
    Route::set("create-account", function () {CreateAccount::main_();});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>