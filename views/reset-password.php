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
                    <li><a href="home">home</a></li>
                    <li><a href="login">login</a></li>
                    <li><a href="create-account">create account</a><li>
                    <li><a href="gallery">gallery</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <p>
            <form action="reset-password" method="post">
            <fieldset id="field_2">
            <legend>reset password</legend>
            <p><input type="email" name="email" placeholder="e-mail..." maxlength="50" required="required"></p>
            <p><input type="submit" name="resetpassword" value="reset password" id="resetpassword"></p>
            </fieldset>
            </form>
        </p>

        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>