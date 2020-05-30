<!DOCTYPE html>
<html>
    <head>
        <title>
            camagru
        </title>
        <link href="./CSS/fonts.css" type="text/css" rel="stylesheet" />
        <script src="./js/profile.js"></script>
    </head>
    
    <body onload="init();">
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

        <h1>ft_snapshot</h1>
        <p>
            <button onclick="startWebcam();">Start WebCam</button>
            <button onclick="stopWebcam();">Stop WebCam</button> 
            <button onclick="snapshot();">Take Snapshot</button> 
        </p>
    
        <video onclick="snapshot(this);" width=400 height=400 id="video-player" controls autoplay></video>
    
        <p>Screenshots : <p>
        <canvas  id="myCanvas" width="400" height="350"></canvas>  

        <footer>
            <hr />
            <p>"geeked <em>oop!</em>"</p>
        </footer><!-- end of footer -->
    </body>
</html>