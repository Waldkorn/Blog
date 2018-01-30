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
	document.getElementById('content').innerHTML = "";
	for (i = 0 ; i < content.length ; i++) {
		document.getElementById('content').innerHTML += content[i][1] + ": " + content[i][2] + "<br>";
	}
}

function submitMessage() {
	categories = document.getElementById("categories-message").value;
	message = document.getElementById("write-message").value;

	request.open("POST", "api.php?categories=" + categories + "&message=" + message, false);
	request.send();
}