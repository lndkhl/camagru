<?php
class ResetPassword extends Controller
{
    public static function main_()
    {
        if (isset($_POST["resetpassword"]))
        {
            $email = $_POST['email'];
            $bother = True;
            $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
            if (DB::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email)))
            {
                $user_id = DB::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email))[0]['id'];
                $token = sha1($toke);
                DB::query('INSERT INTO password_tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$user_id));
                mail($email, "Password Reset", "http://localhost:8888/Camagru/change-password_tokenized/?token=$token");
                echo 'Email sent!';
            }
            else
            {
                echo 'User not found!';
            }
        }
    }
}
?>