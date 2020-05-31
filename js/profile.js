var	video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');

var sticker1 = document.getElementById('img1');
var sticker2 = document.getElementById('img2');
var sticker3 = document.getElementById('img3');
var sticker4 = document.getElementById('img4');
var sticker5 = document.getElementById('img5');

var preview = [sticker1, sticker2, sticker3, sticker4, sticker5];

var stickers = document.getElementsByClassName("buttons");

const process = document.getElementById("store");
process.disabled = true;
const render = document.getElementById("upload");
render.disabled = true;

for (var i = 0; i < stickers.length; i++){
	stickers[i].addEventListener("click", function () {		
		j = 0;
		for (var k = 0; k < preview.length; k++)
		{
			if (stickers[k] == this)
			{
				j = k;
			}
		}
		var hRatio = canvas.width / preview[j].width;
		var vRatio = canvas.height / preview[j].height;
		var ratio  = Math.min ( hRatio, vRatio );
		context.clearRect(0, 0, canvas.width, canvas.height);
		if (preview[j])
		{
			context.drawImage(preview[j], 0, 0, preview[j].width, preview[j].height, 0, 0, preview[j].width*ratio, preview[j].height*ratio);
			process.disabled = false;
			render.disabled = false;}});}

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) 
{
	navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream)
 	{
       	video.srcObject = stream;
		document.getElementById("camera").addEventListener("click", function () {video.play()});
		document.getElementById("pause").addEventListener("click", function () {video.pause();})
	}).catch(e => console.error(e));

}

document.getElementById("snap").addEventListener("click", function () {context.drawImage(video, 0, 0, canvas.width, canvas.height);});


/*
function savepic () {
  	var data = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
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


const img = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
const formData = new FormData();

formData.append('file', img);

    const options = {
      method: 'POST',
      body: formData,
    };

    fetch('upload', options);*/