var video = document.getElementById('video');
    
    document.getElementById('camera-on').addEventListener("click", function()
    {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) 
        {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream)
            {
                video.srcObject = stream;
                video.play();
            });
        }
    })

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    
    document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 320, 240);
        });

    document.getElementById("save").addEventListener("click", function() {
        var image = canvas.toDataURL("image/png");
        var xhr = new XMLHttpRequest();

        xhr.open('POST', 'pics');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        /*
        xhr.onreadystatechange = function (res) {
            if (xhr.status === 200 && xhr.readyState === xhr.DONE) {
                console.log('Response Text: ' + res.target.response);
                console.log('Response:', res);
            }
        }*/
        xhr.send("image=" + image);
        })