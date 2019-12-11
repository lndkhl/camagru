<?php
class Login extends Controller
{
    public static function main_()
    {
        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
        
            if (query('SELECT username FROM camagru.users WHERE username= :username', array(':username'=>$username)))
            {
                if(password_verify($password, query('SELECT password FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['password']))
                {
                    $bother = True;
                    $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
                    $user_id = query('SELECT id FROM camagru.users WHERE username=:username', array(':username'=>$username))[0]['id'];
                    $token = sha1($toke);
                    query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$user_id));
            
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