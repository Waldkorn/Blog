function loginUser() {
	document.getElementById('user-interface').style.display = "block";
	navigateTo('home-content');
}

loginUser();

function loginBlogger() {

	username = document.getElementById("username").value;
	password = document.getElementById("password").value;

	if (checkLogin(username, password)) {
		document.getElementById('user-interface').style.display = "none";
		document.getElementById('blogger-interface').style.display = "block";
		navigateTo('create-article');
	}
}

function checkLogin(username, password) {
	request.open("GET", "api.php?username=" + username + "&password=" + password, false);
	request.send();
	console.log(request);
	if (request.response === "1") {
		return true;
	} else {
		return false;
	}
}

function displayContentByCategory(category) {
	request.open("GET", "api.php?category=" + category, false);
	request.send();

	console.log(request.response);

	response = JSON.parse(request.response);

	displayBlogContent(response);
}