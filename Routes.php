<?php
Route::set('change-password', function () { ChangePassword::create_view("change-password");});

Route::set('reset-password', function() { ResetPassword::create_view("reset-password");});

Route::set('create-account', function() { CreateAccount::create_view("create-account");});

Route::set('login', function() { Log_in::create_view("login");});

Route::set('index', function() { CreateDatabase::reset();});

Route::set('logout', function() { Logout::create_view("logout");});
?>