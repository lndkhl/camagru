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
                    <li><a href="profile">home</a></li>
                    <li><a href="logout">logout</a></li>
                    <li><a href="gallery">gallery</a></li>
                    </ul>
            </p>
        </nav><!-- end of links -->

        <p>
            <form action="change-username" method="post">
            <fieldset id="field_1">
            <legend>change username</legend>
            <p><input type="text" name="username" placeholder="new username..." maxlength="30" required="required"></p>
            <p><input type="submit" name="changeusername" value="change username" id="changeusername"></p>
            </form>
        </p>

        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>