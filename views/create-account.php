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
                    <li><a href="gallery">gallery</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <p>
            <form action="home" method="post">    
            <fieldset id="field_1">
            <legend>create account</legend>
            <p><input type="text" name="username" placeholder="username..." maxlength="20" required="required"></p>
            <p><input tpye="email" name="email" placeholder="e-mail..." maxlength="30" required="required"></p>
            <p><input type="password" name="password" placeholder="password..." maxlength="30" required="required"></p>
            <p><input type="password" name="reenterpassword" placeholder="reenter password..." maxlength= "30" required="required"></p>
            <p><input type="submit" name="create-account" value="create-account" id="create-account"></p>
            </fieldset>
            </form>
        </p>
    
        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>