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

    public static function setroutes()
    {
        try
        {
            Route::set("index.php", function () {Home::main_();});
            Route::set("home", function () {Home::main_();});
            Route::set("gallery", function () {Gallery::main_();});
            Route::set("profile", function () {Profile::main_();});
            Route::set("change-password", function () {ChangePassword::main_();});
            Route::set("forgot-password", function () {ForgotPassword::main_();});
            Route::set("reset-password", function () {ResetPassword::main_();});
            Route::set("login", function () {Login::main_();});
            Route::set("logout", function () {Logout::main_();});
            Route::set("create-account", function () {CreateAccount::main_();});
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function redirect($page)
    {
        $update = "home";
        $projectRoot = "http://127.0.0.1/camagru/";
        header('Location:' . $projectRoot . $update);
        header('Location:' . $projectRoot . $page);
        exit();
    }
}
Route::setroutes();
?>