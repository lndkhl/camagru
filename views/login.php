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
                    <li><a href="reset-password">reset password</a></li>
                    <li><a href="gallery">gallery</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <p>
            <form action="profile" method="post">    
            <fieldset id="field_1">
            <legend>login</legend>
            <p><input type="text" name="username" placeholder="username..." maxlength="20" required="required"></p>
            <p><input type="password" name="password" placeholder="password..." maxlength="30" required="required"></p>
            <p><input type="submit" name="login" value="login" id="submit_login"></p>
            </fieldset>
            </form>
        </p>

        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>