<!DOCTYPE html>
<html>
    <head>
        <title>
            camagru
        </title>
        <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
    </head>
    
    <div class="wrapper">
    <body>
        <header>
        <h1 class="title">camagru</h1>
        </header><!-- end of header -->

        <nav>
            <p> 
                <ul>
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="profile">profile</a></li>
                    <li><a href="logout">logout</a></li>
                    <li><a href="settings">settings</a></li>
                    </ul>
            </p>
        </nav><!-- end of links -->

        <div class="inner">
        <p>
            <form action="change-username" method="post">
            <fieldset id="field_1">
            <legend>change username</legend>
            <p><input type="text" name="username" placeholder="new username..." maxlength="30" required="required"></p>
            <p><input type="submit" name="changeusername" value="change username" id="changeusername"></p>
            </form>
        </p>
        </div>

        <footer>
            <hr />
            <p>"<em>oop</em>"</p>
        </footer><!-- end of footer -->
    </body>
    </div><!-- end of wrapper -->
</html>