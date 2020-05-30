var video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = resizeCanvas.getContext('2d');
var video = document.getElementById('video');

if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        video.srcObject = stream;
        video.play();
    });
}

document.getElementById("snap").addEventListener("click", function() {
	context.drawImage(video, 0, 0, 240, 240);
});

function savepic () {
  var data = canvas.toDataURL();
	var xhttp = new XMLHttpRequest();
	var uri = "upload";

	xhttp.open("POST", uri, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
      		console.log(url);
    	} 
	};
	xhttp.send('key='+data);
	location.reload();
}
/*
document.getElementById("save").addEventListener("click", function(){
  savepic();
});
*/