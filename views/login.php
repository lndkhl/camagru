<!DOCTYPE html>
<html>
    <head>
        <title>
            camagru
        </title>
        <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="forward.ico">
    </head>
   
    <div class="wrapper">
    <body>
        <header>
        <h1 class="title">camagru</h1>
        </header><!-- end of header -->

        <nav>
            <p> 
                <ul>
                    <li><a href="create-account"> create account</a></li>
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="home">home</a></li>
                    <li><a href="reset-password">reset password</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <div class="inner">
        <p>
            <form action="login" method="post">
            <fieldset id="field_1">
            <legend>login</legend>
            <p><input type="text" name="username" placeholder="username..." maxlength="20" required="required"></p>
            <p><input type="password" name="password" placeholder="password..." maxlength="30" required="required"></p>
            <p><input type="submit" name="login" value="login" id="login"></p>
            </fieldset>
            </form>
        </p>
        </div><!-- end of inner -->

        <footer>
            <hr />
            <p>"<em>oop</em>"</p>
        </footer><!-- end of footer -->
    </body>
    </div><!-- end of wrapper -->
</html>