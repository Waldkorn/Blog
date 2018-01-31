var currentContent = "";
var request = new XMLHttpRequest();

function navigateTo(contentToDisplay) {

	if (currentContent != contentToDisplay) {

		if (contentToDisplay == 'blog-content') {

			request.open("GET", "api.php", false);
			request.send();

			response = JSON.parse(request.response);

			displayBlogContent(response);
			
		}

		if (currentContent != "") {
			$("#" + currentContent).toggle('slow');
		}

		$("#" + contentToDisplay).toggle('slow');

		currentContent = contentToDisplay;

	}

}

function displayBlogContent(content) {
	//meest recente content komt bovenaan te staan
	content.reverse();

	document.getElementById('content').innerHTML = "";
	for (i = 0 ; i < content.length ; i++) {
		document.getElementById('content').innerHTML += "<div class='blogpost' id=blogpost" + content[i][0] + "></div>";
		document.getElementById('blogpost' + content[i][0]).innerHTML = "<div class=blogpost-category>Category:" + content[i][1] + "</div>";
		document.getElementById('blogpost' + content[i][0]).innerHTML += "<div class=blogpost-message>" + content[i][2] + "</div>";
	}

	//style voor de make up, geen idee hoe je css responsive kunt maken
	blogposts = document.getElementsByClassName('blogpost');
	for (i = 0 ; i < blogposts.length ; i++) {
		blogposts[i].style.borderStyle = "solid";
		blogposts[i].style.borderWidth = "1px";
		blogposts[i].style.borderColor = "#0093ec";
		blogposts[i].style.margin = "2px";
		blogposts[i].style.borderRadius = "2px";
	}
	blogpostCategories = document.getElementsByClassName('blogpost-category');
	for (i = 0 ; i < blogpostCategories.length ; i++) {
		blogpostCategories[i].style.textAlign = "right";
		blogpostCategories[i].style.borderBottomStyle = "solid";
		blogpostCategories[i].style.borderBottomWidth = "1px";
		blogpostCategories[i].style.borderBottomColor = "#0093EC";
	}
}

//post een message naar de sever
function submitMessage() {
	categories = document.getElementById("categories-message").value;
	message = document.getElementById("write-message").value;

	request.open("POST", "api.php?categories=" + categories + "&message=" + message, false);
	request.send();
}