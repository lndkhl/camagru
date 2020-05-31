var	video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = resizeCanvas.getContext('2d');
var video = document.getElementById('video');

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) 
{
		navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream)
	 	{
        	video.srcObject = stream;
        	video.play();
    	});
}

document.getElementById("snap").addEventListener("click", function() {
	context.drawImage(video, 0, 0, 360, 360);
});
/*
function savepic () {
  	var data = resizeCanvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
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

document.getElementById("store").addEventListener("click", function(){
  submit();
});

async function submit() {
	let blob = await new Promise(resolve => resizeCanvas.toBlob(resolve, 'image/png'));
	let response = await fetch('upload', {
	  method: 'POST',
	  body: blob
	});

	let result = await response.json();
	alert(result.message);
}
*/

const img = resizeCanvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
const formData = new FormData();

formData.append('file', img);

    const options = {
      method: 'POST',
      body: formData,
      // If you add this, upload won't work
      // headers: {
      //   'Content-Type': 'multipart/form-data',
      // }
    };

    fetch('upload', options);