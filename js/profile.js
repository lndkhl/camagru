var	video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var camshot = 0;

var sticker1 = document.getElementById('img1');
var sticker2 = document.getElementById('img2');
var sticker3 = document.getElementById('img3');
var sticker4 = document.getElementById('img4');
var sticker5 = document.getElementById('img5');

var preview = [sticker1, sticker2, sticker3, sticker4, sticker5];

var stickers = document.getElementsByClassName("buttons");

const render = document.getElementById("store");
render.disabled = true;

const fromFile = document.getElementById("img");
fromFile.disabled = true;

const shoot = document.getElementById("snap");
shoot.disabled = true;

const clear = document.getElementById("clear");
clear.disabled = true;

for (var i = 0; i < stickers.length; i++){
	stickers[i].addEventListener("click", function () {		
		j = 0;
		for (var k = 0; k < preview.length; k++) {
			if (stickers[k] == this) {
				j = k;
			}
		}
		if (preview[j])
		{
			//onCanvas(preview[j]);
			fromFile.disabled = false;
			shoot.disabled = false;
		}});}

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
	navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
		video.srcObject = stream;
		video.play();
	}).catch(e => console.error(e));
}

document.getElementById("snap").addEventListener("click", function () {
	var vbox = document.getElementById("video");
	var hRatio = canvas.width / vbox.videoWidth;
	var vRatio = canvas.height / vbox.videoHeight;
	var ratio  = Math.min ( hRatio, vRatio );
	
	context.drawImage(video, 0, 0, vbox.videoWidth, vbox.videoHeight, (canvas.width*ratio)/2, 0, vbox.videoWidth*ratio, vbox.videoHeight*ratio);
	camshot = 1;
	render.disabled = false;
	clear.disabled = false;
});

document.getElementById("img").addEventListener("click", function () {
	camshot = 0
	render.disabled = false;
})

document.getElementById("store").addEventListener("click", function() {		
	var image = canvas.toDataURL("image/png");
	let imgForm = new FormData(document.getElementById('uploadForm'));
	
	if (camshot == 1) { imgForm.set("image", image); }
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'upload', 'true');
	xhr.send(imgForm);
	xhr.onreadystatechange = function (res) {
		if (xhr.status === 200 && xhr.readyState === xhr.DONE) {
			var data = xhr.response;
			data = data.slice(data.indexOf('{'));
			data = JSON.parse(data);
			window.alert(data.message);
			context.clearRect(0, 0, canvas.width, canvas.height);
			render.disabled = true;
			fromFile.disabled = true;
			shoot.disabled = true;
			location.reload();
		}
	}
})

function prepImage(image)
{
	context.clearRect(0, 0, canvas.width, canvas.height);
	onCanvas(image);
}

function onCanvas(image)
{
	var hRatio = canvas.width / image.width;
	var vRatio = canvas.height / image.height;
	var ratio  = Math.min ( hRatio, vRatio );
	
	context.drawImage(image, 0, 0, image.width, image.height, 0, 0, image.width*ratio, image.height*ratio);
}

fromFile.addEventListener("change", handleFileSelect, false);

function handleFileSelect(event)
{
    var files = event.target.files;

    if(files.length === 0) {
            return;
    }

    var file = files[0];
    if(file.type !== '' && !file.type.match('image.*')) {
            return;
	}
	window.URL = window.URL || window.webkitURL;

	var imageURL = window.URL.createObjectURL(file);
	loadAndDrawImage(imageURL);
}

clear.addEventListener("click", function () {
	context.clearRect(0, 0, canvas.width, canvas.height);
	render.disabled = true;
	fromFile.disabled = true;
	shoot.disabled = true;
	location.reload();
})

function loadAndDrawImage(url)
{
    var image = new Image();

    image.onload = function() {
		prepImage(image);
    }
    image.src = url;
}