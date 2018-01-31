var currentContent = "";
var request = new XMLHttpRequest();

function navigateTo(contentToDisplay) {

	if (currentContent != contentToDisplay) {

		if (contentToDisplay == 'blog-content') {

			request.open("GET", "api.php", false);
			request.send();

			console.log(request.response);

			response = JSON.parse(request.response);

			displayBlogContent(response);

			request.open("get", "api.php?getcategories=yes", false);
			request.send();

			categories = JSON.parse(request.response);

			displayCategories(categories);
			
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
		document.getElementById('blogpost' + content[i][0]).innerHTML = "<div class='blogpostcategory'>Category: " + content[i][1] + "</div>";
		document.getElementById('blogpost' + content[i][0]).innerHTML += "<div class='blogpost-message'>" + content[i][2] + "</div>";
	}
}

//post een message naar de sever
function submitMessage() {
	categories = document.getElementById("categories-message").value;
	message = document.getElementById("write-message").value;

	request.open("POST", "api.php?categories=" + categories + "&message=" + message, false);
	request.send();
}

function displayCategories(categories) {
	document.getElementById('categories').innerHTML = "";
	for (i = 0 ; i < categories.length ; i++) {
		document.getElementById('categories').innerHTML += "<div class=category-element onclick=displayContentByCategory('" + categories[i] + "')>" + categories[i] + "</div>";
	}
}