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
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="profile">profile</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->

        <p id="p_1">
            <form action="profile" method="post">    
            <fieldset id="field_1">
            <legend>login</legend>
            <p><input type="text" name="username" placeholder="username..." maxlength="20" required="required"></p>
            <p><input type="password" name="password" placeholder="password..." maxlength="30" required="required"></p>
            <p><input type="submit" name="login" value="login" id="submit_login"></p>
            </fieldset>
            </form>
        </p>
        <hr id="form_split"/>
        <p id="p_2">
            <form action="home" method="post">
            <fieldset id="field_2">
            <legend>reset password</legend>
            <p><input type="email" name="email" placeholder="e-mail..." maxlength="50" required="required"></p>
            <p><input type="submit" name="reset-password" value="reset password" id="submit_reset"></p>
            </fieldset>
            </form>
        </p>

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
            <p>"geeked <em>ooop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>