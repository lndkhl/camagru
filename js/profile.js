var	video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var realcanvas = document.getElementById('realcanvas');
var choice = 0;

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

context.scale(8, 8);
context.imageSmoothingEnabled = true;

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
			if (j == 0) { choice = choice | 1; }
			else if (j == 1) { choice = choice | 2; }
			else if (j == 2) { choice = choice | 4; }
			else if (j == 3) { choice = choice | 8; }
			else if (j == 4) { choice = choice | 16; }
			fromFile.disabled = false;
			shoot.disabled = false;
		}});}

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
	navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
		video.srcObject = stream;
		video.play();
	}).catch(e => console.error(e));
}

shoot.addEventListener("click", function () {
	var vbox = document.getElementById("video");
	var hRatio = canvas.width / vbox.videoWidth;
	var vRatio = canvas.height / vbox.videoHeight;
	var ratio  = Math.min ( hRatio, vRatio );
	realcanvas.width = vbox.videoWidth;
	realcanvas.height = vbox.videoHeight;
	var ctx = realcanvas.getContext('2d');
	
	context.clearRect(0, 0, canvas.width, canvas.height);
	context.drawImage(video, 0, 0, vbox.videoWidth*8, vbox.videoHeight*8, (canvas.width-vbox.videoWidth*ratio)/16, 0, vbox.videoWidth*ratio, vbox.videoHeight*ratio);
	ctx.drawImage(video, 0, 0, vbox.videoWidth, vbox.videoHeight, 0, 0, vbox.videoWidth, vbox.videoHeight);
	putStickers(canvas, context);
	putStickers(realcanvas, ctx);
	render.disabled = false;
	clear.disabled = false;
	shoot.disabled = true;
	fromFile.disabled = true;
});

render.addEventListener("click", function() {		
	var image = realcanvas.toDataURL("image/png");
	let imgForm = new FormData();
	
	imgForm.set("image", image);
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

fromFile.addEventListener("change", handleFileSelect, false);

function putStickers(canvas, context)
{
	if (choice & 1) { finalStep(preview[0], canvas, context); }
	if (choice & 2) { finalStep(preview[1], canvas, context); }
	if (choice & 4) { finalStep(preview[2], canvas, context); }
	if (choice & 8) { finalStep(preview[3], canvas, context); }
	if (choice & 16) { finalStep(preview[4], canvas, context); }
}

function finalStep(image, canvas, context)
{
	var hRatio = canvas.width / image.width;
	var vRatio = canvas.height / image.height;
	var ratio  = Math.min ( hRatio, vRatio );
	
	context.drawImage(image, 0, 0, image.width, image.height, Math.abs((canvas.width-image.width*ratio))/2, Math.abs((canvas.height-image.height*ratio))/2, image.width*ratio/2, image.height*ratio/2);
}

function onCanvas(image)
{
	var hRatio = canvas.width / image.width;
	var vRatio = canvas.height / image.height;
	var ratio  = Math.min ( hRatio, vRatio );
	
	context.drawImage(image, 0, 0, image.width, image.height, (canvas.width-image.width*ratio)/16, 0, image.width*ratio/8, image.height*ratio/8);
}

function onReal(image)
{
	realcanvas.width = image.width;
	realcanvas.height = image.height;
	var ctx = realcanvas.getContext('2d');

	ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, image.width, image.height);
	putStickers(realcanvas, ctx);
}

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
	render.disabled = false;
	clear.disabled = false;
	shoot.disabled = true;
	fromFile.disabled = true;
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
		context.clearRect(0, 0, canvas.width, canvas.height);
		onCanvas(image);
		onReal(image);
    }
    image.src = url;
}

