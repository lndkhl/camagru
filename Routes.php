<?php
//Route::set('change-password', function () { ChangePassword::create_view("change-password");});

Route::set('password-reset', function() { echo "password-reset";});

Route::set('create-account', function() { CreateAccount::create_view("create-account");});

Route::set('login', function() { echo "login";});

Route::set('index', function() { CreateDatabase::reset();});

//Route::set('logout', function() { Logout::create_view("logout");});
?>