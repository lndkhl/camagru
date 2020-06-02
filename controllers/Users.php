<?php
class Users extends Controller
{
    public static function isLoggedIn()
    {
        if (isset($_COOKIE['CamagruID']))
        {
            if (static::query('SELECT user_id FROM camagru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID']))))
            {
                $user_id = static::query('SELECT user_id FROM camagru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])))[0]['user_id'];
                if (isset($_COOKIE['CamagruID']))
                {
                    return $user_id;
                }
                else
                {
                    $cryptographically_strong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                    static::query('INSERT INTO camagru.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                    static::query('DELETE FROM camgru.tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CamagruID'])));
                    setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
                    setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
                    return $user_id;
                }
            }
        }
        return FALSE;
   }

   public static function getUsername($user_id)
   {
       if (static::query('SELECT username FROM camagru.users WHERE id=:user_id', array(':user_id'=>$user_id)))
       {
           return (static::query('SELECT username FROM camagru.users WHERE id=:user_id', array(':user_id'=>$user_id))[0]['username']);
       }
       else
       {
           return FALSE;
       }
   }

   public static function userExists($username)
   {
        if (static::validUsername($username))
        {
            if (static::query('SELECT username FROM camagru.users WHERE username=:username', 
                            array(':username'=>$username)))
            {
                return TRUE;
            }
        }
        return FALSE;
   }

   public static function verify($username)
   {
        $verified = static::query('SELECT verified FROM ' .  static::get_db_name()  .  '.users WHERE username=:username', 
                                array(':username'=>$username))[0]['verified'];
        return $verified;
   }

   public static function validUsername($username)
   {
        if (preg_match('/^[a-z0-9_]+$/', $username))
        {
            return TRUE;
        }
        return FALSE;
   }

   public static function validUsernameLength($username)
   {
        if (preg_match('/.{3}/', $username))
        {
            return TRUE;
        }
        return FALSE;
   }

   public static function validPasswordComplexity($password)
   {
        if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/', $password))
        {
            return TRUE;
        }
        return FALSE;
   }

   public static function validPasswordLength($password)
   {
        if (preg_match('/.{8}/', $password))
        {
            return TRUE;
        }
        return FALSE;
   }

   public static function updatePassword($newpword)
   {
        static::query('UPDATE ' .  static::get_db_name()  .  '.users SET password=:newpassword WHERE id=:user_id',
                    array(':newpassword'=>password_hash($newpword, PASSWORD_BCRYPT), ':user_id'=>static::isLoggedIn()));
        echo "Password changed successfully";
   }

   public static function emailExists($email)
   {
        if (static::query('SELECT email FROM camagru.users WHERE email=:email', array(':email'=>$email)))
        {
            return TRUE;
        }
        return FALSE;
   }

   public static function authenticateLogin($username, $password)
   {
       if (password_verify($password, static::query('SELECT password FROM ' .  static::get_db_name()  .  '.users WHERE username=:username',
       array(':username'=>$username))[0]['password']))
       {
           return TRUE;
       }
       return FALSE;
   }

   public static function setCookies($username)
   {
        $cryptographically_strong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        $user_id = static::query('SELECT id FROM ' .  static::get_db_name()  .  '.users WHERE username=:username', array(':username'=>$username))[0]['id'];
        
        static::query('INSERT INTO ' .  static::get_db_name()  .  '.tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
        setcookie('CamagruID',$token, time() + 604800 /*1 week*/, '/', NULL, NULL, TRUE);
        setcookie('StayIn', '1', time() + 259200 /*3 days*/, '/', NULL, NULL, TRUE);
   }

   public static function authenticate($password)
   {
       if (password_verify($password, static::query('SELECT password FROM ' .  static::get_db_name()  .  '.users WHERE id=:user_id',
       array(':user_id'=>static::isLoggedIn()))[0]['password']))
       {
           return TRUE;
       }
       return FALSE;
   }

   public static function registerUser($username, $password, $email)
   {
        $verified = 0;
        $notifications = 1;
        $subject = "Camagru user verification";
        $cryptographically_strong = true;
        $message = "Click the following link, or copy and paste it into your browser, to complete the registration process: ";
        $project_root = static::get_project_root("create-account");
        $link = $project_root . "home?voken=";
        $voken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
        
        static::query('INSERT INTO camagru.users (username, password, email, verified, notifications) VALUES (:username, :password, :email, :verified, :notifications)', 
                                    array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':verified'=>$verified, ':notifications'=>$notifications));
        echo "Registration succesfull!<br>";
        if (mail($email, $subject, $message . $link . $voken))
        {
            static::query('INSERT INTO camagru.vokens (voken, user_id) VALUES (:voken, :user_id)',
                array(':voken'=>sha1($voken), ':user_id'=>static::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email))[0]['id']));
            echo "Email verification link sent, verify your email to get started";
        }
        else
        {
            echo "We are experiencing difficulty sending you a verification email, please try again";
        }
    }

    public static function uploadPic($imgname, $user_id)
    {
        $likes = 0;
        static::query('INSERT INTO camagru.posts (imgname, likes, user_id) VALUES (:imgname, :likes, :user_id)',
                    array(':imgname'=>$imgname, ':likes'=>$likes, ':user_id'=>$user_id));
        echo "Image uploaded successfully<br>";
    }

    

    public static function parsePic()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['upload']) && !empty($_FILES['img']['name']))
            {
                $imgName = $_FILES['img']['name'];
                $fileType = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                $allowTypes = array('jpg','png','jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $img = static::getUsername(static::isLoggedIn()) . date('-Y-m-d_h:i:s.') . $fileType;
                    if(!file_exists("uploads"))
                    {
                        mkdir("uploads", 0700, TRUE);
                    }
                    $targetDir = "uploads/";
                    $targetFilePath = $targetDir . $img;
                    
                    if(move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath))
                    {
                        static::uploadPic($img, $user_id);            
                    }
                    else
                    {
                        echo "File upload failed, please try again.";
                    }
                }
                else
                {
                    echo "Sorry, only JPG, JPEG, PNG, files are allowed.";
                }
            }
            else if (isset($_POST['image']))
            {
                $image_string = $_POST['image'];
                $image_string = str_replace('data:image/png;base64,', '', $image_string);
                $image_string = str_replace(' ', '+', $image_string);
                $image_string = base64_decode($image_string);

                if (!file_exists("uploads"))
                {
                    mkdir("uploads", 0700, TRUE);
                }
                $pic = static::getUsername(static::isLoggedIn()). date('-Y-m-d_h:i:s') . '.png';
                $path = 'uploads/' . $pic;
                if (file_put_contents($path, $image_string) != FALSE)
                {
                    static::uploadPic($pic, $user_id);
                    
                    /*
                    echo '<meta http-equiv="refresh" content="100;url=' . static::get_project_root("profile") . '/profile">';
                    Route::redirect("profile");
                    */
                }
                else
                {
                    echo "File upload failed, please try again.";
                }
            }
        }
    }
}

?>