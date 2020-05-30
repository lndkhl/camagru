    navigator.getUserMedia = ( navigator.getUserMedia ||
         navigator.webkitGetUserMedia ||
         navigator.mozGetUserMedia ||
         navigator.msGetUserMedia);

    var video;

    function startWebcam() 
    {
        if (navigator.getUserMedia) 
        {
           navigator.getUserMedia 
           (
                {
                    video: true,
                    audio: false
                },

                function(localMediaStream) 
                {
                    video = document.getElementById('video-player');
                    video.src = localMediaStream;
                    video.play();
                },
             
                function(error)
                {
                    console.log("The following error occured: " + error);
                }
           );
        } 
        else 
        {
            console.log("getUserMedia not supported");
        }  
      }

    function stopWebcam()
    {
        video.stop();
    }
    
    var canvas; 
    var ctx;

    function init() 
    {
        canvas = document.getElementById("myCanvas");
        ctx = canvas.getContext('2d');
    }

    function snapshot() 
    {
        ctx.drawImage(video, 0,0, canvas.width, canvas.height);
    }