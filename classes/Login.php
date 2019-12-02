<?php
class Login
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
}
?>