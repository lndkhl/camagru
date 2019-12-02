<?php
class Log_in extends Controller
{
    public static function isLoggedIn()
    {
        if(isset($_COOKIE["CID"]))
        {
            $hashcookie = sha1($_COOKIE["CID"]);
            if (DB::query('SELECT user_id FROM camagru.tokens WHERE token= :token', array(':token'=>$hashcookie)))
            {
                $idnum = DB::query('SELECT user_id FROM camagru.tokens WHERE token= :token', array(':token'=>$hashcookie))[0]['user_id'];
                $uid = DB::query('SELECT username FROM camagru.users WHERE id= :user_id', array(':user_id'=>$idnum))[0]['username'];
                
                if (isset($_COOKIE["CID_REFRESH"]))
                {
                    return $idnum;
                }
                else
                {
                    $bother = True;
                    $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
                    $token = sha1($toke);
                    DB::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$idnum));
                    DB::query('DELETE FROM camagru.tokens WHERE token=:token', array(':token'=>$hashcookie));
                    
                    setcookie("CID", $toke, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                    setcookie("CID_REFRESH", 'irrelevant', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                    
                     return $idnum;
                }
            }
        }
        return false;
    }
    public static function main_()
    {
        self::create_table_users();
        self::create_table_tokens();

        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
        
            if (DB::query('SELECT username FROM camagru.users WHERE username= :username', array(':username'=>$username)))
            {
                if(password_verify($password, DB::query('SELECT password FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['password']))
                {
                    $bother = True;
                    $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
                    $user_id = DB::query('SELECT id FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['id'];
                    $token = sha1($toke);
                    DB::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$user_id));
            
                    setcookie("CID", $toke, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                    setcookie("CID_REFRESH", 'irrelevant', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                    echo "Welcome ";
                    echo $username;
                    echo "!<br>";
                }
                else
                {
                    echo "Password incorrect!";
                }
            }
            else
            {
                echo "User not registered!";
            }
        }
    }

}
?>