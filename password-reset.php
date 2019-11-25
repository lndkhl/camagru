<?php
    include_once("./classes/DB.php");

    try
    {
        $pdo = new PDO('mysql:hostname=127.0.0.1; dbname=camagru;', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
        $sql = "CREATE TABLE IF NOT EXISTS password_tokens(
            id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            token CHAR(64) UNIQUE NOT NULL,
            user_id INT(12) UNSIGNED NOT NULL,
            
            FOREIGN KEY (user_id) REFERENCES users(id)
            )";
        $pdo->exec($sql);
        echo "Table password_tokens created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

    if (isset($_POST["resetpassword"]))
    {
        $email = $_POST['email'];
        $bother = True;
        $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
        $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
        $token = sha1($toke);
        DB::query('INSERT INTO password_tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$user_id));
        mail($email, "Password Reset", "http://localhost:8888/Camagru/change-password.php?token=$token");
        echo 'Email sent!';
    }
?>

<h1>Reset Password</h1>
<form method="post">
    <input type="email" name="email" value ="" Placeholder="Email address"></p>
    <input type="submit" name="resetpassword" value="Reset Password">
</form>