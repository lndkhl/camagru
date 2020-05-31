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
                    <li><a href="settings">settings</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->
        
        <div class="inner">
        <h2>Confirm logout</h2>
        <p>
            Are you sure you want to logout?
        </p>
        <p>
            <form method="post">
            <fieldset id="field_1">
            <legend>logout</legend>
            <p><input type="checkbox" name="alldevices" value="alldevices">Logout of all devices?</p>
            <p><input type="submit" name="confirm" value="Confirm" id="confirm"></p>
            </fieldset>
            </form>
        </p>
        </div><!-- end of inner -->

        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
    </div><!-- end of wrapper -->
</html>