<?php
try
{
    Route::set('change-password', function () { ChangePassword::create_view("change-password");});

    Route::set('reset-password', function() { ResetPassword::create_view("reset-password");});

    Route::set('create-account', function() { CreateAccount::create_view("create-account");});

    Route::set('login', function () { Login::create_view("login");});

    Route::set('index', function () { CreateDatabase::reset();});

    Route::set('logout', function () { Logout::create_view("logout");});

    Route::set('profile', function () { Profile::create_view("profile");});

    Route::set('change-password_tokenized', function () { ChangePassword::create_view("change-password_tokenized");});

    Route::set('change-username', function () { ChangeUsername::create_view("change_username");});

    Route::set('pics', function () { Pics::upload();});

    Route::set('home', function () { Home::create_view("home");});

    Route::set('reset', function () { Home::reset_db();});

    Route::set('gallery', function () {Gallery::create_view("gallery");});
}
catch (Exception $e)
{
    echo "Error: " . $e->getMessage();
}
?>