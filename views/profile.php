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
                    <li><a href="logout">logout</a></li>
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="settings">settings</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->  

        <div class="inner">
            <section class="main">
            
                <ul>
                    <li><button id="sticker1" class="buttons"></button></li>
                    <li><button id="sticker2" class="buttons"></button></li>
                    <li><button id="sticker3" class="buttons"></button></li>
                    <li><button id="sticker4" class="buttons"></button></li>
                    <li><button id="sticker5" class="buttons"></button></li>
                </ul>

                <div class="imgbox">
                    <video id="video" autoplay="on"></video>
                    <canvas id="canvas"></canvas>
                    <div class="media-buttons">
                        <button id="snap">snap</button>
                        <button id="store">upload</button>
                    </div>
                </div>
                
            </section><!-- end of main -->
            
            <div id="alts">
                <p>No webcam?</p>
                <form  action="upload" method="post" enctype="multipart/form-data">
                <input type="file" id="img" name= "img" />
                <input type=submit name="upload" value="submit" id="upload">
                </form>
            </div>

        </div><!-- end of inner -->

        <footer>
            <p>"<em>oop</em>"</p>
        </footer><!-- end of footer -->
        
        <div class="hidden">
        <img id="img1" src="stickers/growmoney.png" />
        <img id="img2" src="stickers/crown.png" />
        <img id="img3" src="stickers/pause.png" />
        <img id="img4" src="stickers/trash.png" />
        <img id="img5" src="stickers/ring.png" />
        </div>

    </body>

    </div><!-- end of wrapper -->
    
    <script src="./js/profile.js"></script>

</html>