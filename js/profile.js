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

for (var i = 0; i < stickers.length; i++){
	stickers[i].addEventListener("click", function () {		
		j = 0;
		for (var k = 0; k < preview.length; k++) {
			if (stickers[k] == this) {
				j = k;
			}
		}
		var hRatio = canvas.width / preview[j].width;
		var vRatio = canvas.height / preview[j].height;
		var ratio  = Math.min ( hRatio, vRatio );
		//context.clearRect(0, 0, canvas.width, canvas.height);
		if (preview[j])
		{
			
			context.drawImage(preview[j], 0, 0, preview[j].width, preview[j].height, 0, 0, preview[j].width*ratio, preview[j].height*ratio);

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
	context.drawImage(video, 0, 0, canvas.width, canvas.height); 
	camshot = 1;
	render.disabled = false;
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