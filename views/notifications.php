<!DOCTYPE html>
<html>
    <head>
        <title>
            camagru
        </title>
        <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="favicon.ico">
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
            <form method="post">
            <fieldset id="field_1">
            <legend>allow notifications?</legend>
            <p>
                <select name="notifyme">
                    <option name="allownotifications" value="1" id="allow"> allow </option>
                    <option name="disallownotifications" value="0" id="disallow"> disallow </option>
                </select>
                <input type="submit" name="notifications" value="submit" />
            </p>
            </fieldset>
            </form>
        </p>   
        </div><!-- end of inner -->
    
        <footer>
            <p>"<em>oop</em>"</p>
        </footer><!-- end of footer -->
    </body>
    </div><!-- end of wrapper -->
</html>