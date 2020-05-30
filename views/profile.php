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
                    <li><a href="logout">logout</a></li>
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="settings">settings</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->  

        <p>
            <video id="video" width="360" height="360" autoplay></video>
            <button id="snap">Take Photo</button>
            <!-- <canvas id="canvas" width="360" height="360"></canvas> -->
            <canvas id="resizeCanvas" height="360" width="360"></canvas>
        </p>

        <p>
            <form  action="upload/image" method="post" enctype="multipart/form-data">
            <input id="img" name= "img" type="file" />
            <input type=submit name="upload" id="save">Upload</button>
            </form>
        </p>
        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
    <script src="./js/profile.js"></script>
</html>