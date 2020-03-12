<!DOCTYPE html>
<html>
    <head>
        <title>
            camagru
        </title>
        <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
    </head>
    
    <body>
        <header>
        <h1 class="title">camagru</h1>
        </header><!-- end of header -->

        <nav>
            <p> 
                <ul>
                    <li><a href="./home.php">home</a></li>
                    <li><a href="./gallery.php">gallery</a></li>
                    <li><a href="./profile.php">profile</a></li>
                    <li><a href="./account.php">account</a></li>
                    <li><a href="./index.php">logout</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <p>
            <form action="./profile.html" method="post">    
            <fieldset id="field_1">
            <legend>login</legend>
            <p><input type="text" name="username" placeholder="username" maxlength="20" required="required"></p>
            <p><input tpye="email" name="email" placeholder="e-mail" maxlength="30" required="required"></p>
            <p><input type="password" name="password" placeholder="password" maxlength="30" required="required"></p>
            <p><input type="password" name="reenterpassword" placeholder="reenter password" maxlength= "30" required="required"></p>
            <p><input type="submit" name="create-account" value="create-account" id="create-account"></p>
            </fieldset>
            </form>
        </p>
    
        <footer>
            <hr />
            <p>"geeked <em>ooop!<em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>