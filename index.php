<!DOCTYPE html>
<head>
    <title>Camagru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
    <h1>CAMAGRU</h1>
    
    </div>
    <?php
    $nameErr = "";
    $emailErr = "";
    $passwdErr = "";
    $name = "";
    $email = "";
    $password = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["name"]))
        {
           $nameErr = "Name is required";
        } 
        else if (empty($_POST["email"])) 
        {
            $emailErr = "Email is required";
        }
        else if (empty($_POST("passwd")))
        {
            $passwdErr = "Password is required";
        }
        else 
        {
            $name = test_input($_POST["name"]);
            $email = test_input($_POST["email"]);
            $passwd = test_input($_POST["password"]);
        }
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Name: <input type="text" name="name">
    <span class="error">* <?php echo $nameErr;?></span>
    <br><br>
    E-mail: <input type="text" name="email">
    <span class="error">* <?php echo $emailErr;?></span>
    <br><br>
    Password:   <input type="password" name="password">
    <span class="error">* <?php echo $passwdErr;?></span>
    <br>
    <input type="submit"/>
    </form>
</body>