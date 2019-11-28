<?php

include_once("./classes/DB.php");
include_once("./classes/Login.php");

$username = "";

if (isset($_GET['username']))
{
    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username'])))
    {
        $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
        try
        {
            $pdo = new PDO('mysql:hostname=127.0.0.1; dbname=camagru;', 'root', 'root');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
            $sql = "CREATE TABLE IF NOT EXISTS followers(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                post CHAR(64) UNIQUE NOT NULL,
                user_id INT(12) UNSIGNED NOT NULL,
            
                FOREIGN KEY (user_id) REFERENCES users(id)
                )";
            $pdo->exec($sql);
            echo "Table followers created successfully<br>";
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    else
    {
        die ("User not found!");
    }
}
?>

<h1>Hi <?php echo $username;?></h1>
<form action="profile.php?username=<?php echo $username; ?>" method = "post">
    <input type="submit" name="follow" value="follow">
</form>