<?php
    include("classes/DB.php");

    try
    {
        $pdo = new PDO('mysql:hostname=127.0.0.1; dbname=camagru;', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
        $sql = "CREATE TABLE IF NOT EXISTS tokens(
            id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            token CHAR(64) UNIQUE NOT NULL,
            user_id INT(12) UNSIGNED NOT NULL,
            
            FOREIGN KEY (user_id) REFERENCES users(id)
            )";
        $pdo->exec($sql);
        echo "Table tokens created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

    if (isset($_POST['login']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (DB::query('SELECT username FROM users WHERE username= :username', array(':username'=>$username)))
        {
            if(password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password']))
            {
                $bother = True;
                $toke = bin2hex(openssl_random_pseudo_bytes(64, $bother));
                $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                $token = sha1($toke);
                DB::query('INSERT INTO tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>$token, ':user_id'=>$user_id));
            
                setcookie("CID", $toke, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE );
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
?>

<h1>Login</h1>
<form action="login.php" method="post">
<input type="text" name="username" value="" placeholder="Username"></p>
<input type="password" name="password" value="" placeholder="Password"></p>
<input type="submit" name="login" value="Login">
</form>