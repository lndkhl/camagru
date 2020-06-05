var likes = document.getElementsByClassName("likes");
var comments = document.getElementsByClassName("comments");
var deletes = document.getElementsByClassName("deletes");

for (var i = 0; i < deletes.length; i++) {
	deletes[i].addEventListener("click", function () {		
		for (var j = 0; j < deletes.length; j++) {
			if (deletes[j] == this) {
                console.log(this.id);
	            if(confirm("are you sure you want\nto delete this post?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'pro-gallery', 'true');
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
                    xhr.send("delete=" + this.id);
                    xhr.onreadystatechange = function (res) {
                        if (xhr.status === 200 && xhr.readyState === xhr.DONE) {
                        console.log('Response:', res);
                        location.reload();
                        }
                    }
			    }
            }
		}
    });
}

for (var i = 0; i < likes.length; i++) {
	likes[i].addEventListener("click", function () {		
		for (var j = 0; j < likes.length; j++) {
			if (likes[j] == this) {
                console.log(this.id);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'pro-gallery', 'true');
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
                xhr.send("like=" + this.id);
                xhr.onreadystatechange = function (res) {
                    if (xhr.status === 200 && xhr.readyState === xhr.DONE) {
                    console.log('Response:', res);
                    location.reload();
                    }
                }
            }
		}
    });
}

/*
for (var i = 0; i < comments.length; i++) {
	comments[i].addEventListener("click", function () {		
		for (var j = 0; j < comments.length; j++) {
			if (comments[j] == this) {
                console.log(this.id);
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'pro-gallery', 'true');
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
                xhr.send("like=" + this.id);
                xhr.onreadystatechange = function (res) {
                    if (xhr.status === 200 && xhr.readyState === xhr.DONE) {
                    console.log('Response:', res);
                    location.reload();
                    }
                }
            }
		}
    });
}
*/