<?php
include_once('classes/DB.php');

try
{
    $pdo = new PDO('mysql:hostname=127.0.0.1; dbname=camagru;', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
    $sql = "CREATE TABLE IF NOT EXISTS users(
        id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(32) NOT NULL,
        password VARCHAR(64) NOT NULL,
        email VARCHAR(64),
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    $pdo->exec($sql);
    echo "Table users created successfully<br>";
}
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}

if(isset($_POST['createaccount']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!DB::query('SELECT username FROM users WHERE username= :username', array(':username'=>$username)))
    {
        if (strlen($username) >= 3 && strlen($username) <=30 && preg_match('/[a-zA-Z_]+/', $username))
        {
            if(strlen($password) >= 8 && strlen($password) <= 30)
            {
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    DB::query('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                    echo "User succesfully registered!";
                }    
                else
                {
                    echo "Invalid Email Address";
                }
            }
            else
            {
                echo "Invalid Password (minimum 8 characters)";
            }
        }
        else
        {
            echo "Invalid Username";
        }
    }
    else
    {
        echo "User already exists!";
    }
}
?>

<h1>Register</h1>
<form action="create-account.php" method="post">
<input type="text" name="username" value="" placeholder="Username"></p>
<input type="password" name="password" value="" placeholder="Password"></p>
<input type="email" name="email" value="" placeholder="E-mail"></p>
<input type="submit" name="createaccount" value="Create Account">
</form>