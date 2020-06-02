<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Home extends Users
{
    public static function main_()
    {
        setup::initialize();
        if (isset($_GET['voken']))
        {
            if (static::query('SELECT user_id FROM ' .  static::get_db_name()  .  '.vokens WHERE voken=:voken', array(':voken'=>sha1($_GET['voken']))))
            {
                $user_id = static::query('SELECT user_id FROM ' .  static::get_db_name()  .  '.vokens WHERE voken=:voken', array(':voken'=>sha1($_GET['voken'])))[0]['user_id'];
                $verified = 1;
                static::query('UPDATE ' .  static::get_db_name()  .  '.users SET verified=:verified WHERE id=:user_id',
                    array(':verified'=>$verified, ':user_id'=>$user_id));
                static::deleteById($user_id, "vokens");
                echo "Email verification complete<br>";
            }
            else { echo "invalid token<br>"; }
        }
        if (!static::isLoggedIn())
        {
            static::create_view("home");
        }
        else
        {
            Profile::main_();
            exit();
        }        
    }
}
?>