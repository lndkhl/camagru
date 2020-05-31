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
                    <li><a href="logout">logout</a></li>
                    <li><a href="gallery">gallery</a></li>
                    <li><a href="settings">settings</a></li>
                </ul>
            </p>
        </nav><!-- end of links -->  

        <div class="inner">
        <section class="main">
        
            <div class="imgbox">
            
                <video id="video"></video>
                <canvas id="resizeCanvas"></canvas>

                <aside>
                    <h2>pick a sticker:</h2>
                    <ul>
                        <li><button id="sticker1"></button></li>
                        <li><button id="sticker2"></button></li>
                        <li><button id="sticker3"></button></li>
                        <li><button id="sticker4"></button></li>
                        <li><button id="sticker5"></button></li>
                    </ul>
                </aside>
            
            </div><!-- end of imgbox -->
            
            <div class="options">
            
                <span id="capture">
                    <button id="snap">snap</button>
                    <button id="camera">camera</button>
                    <button id="pause">pause</button>
                </span>
            
            </div><!-- end of options -->
            
            <div id="process">
                <button id="store">upload</button>
            </div><!-- end of alts -->
        
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

    </body>

    </div><!-- end of wrapper -->
    
    <script src="./js/profile.js"></script>

</html>