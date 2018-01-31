function loginUser() {
	document.getElementById('user-interface').style.display = "block";
	navigateTo('home-content');
}

loginUser();

function loginBlogger() {

	username = document.getElementById("username").value;
	password = document.getElementById("password").value;

	if (checkLogin(username, password) === "1") {
		document.getElementById('user-interface').style.display = "none";
		document.getElementById('blogger-interface').style.display = "block";
		navigateTo('create-article');
	}
}

function checkLogin(username, password) {
	request.open("GET", "api.php?username=" + username + "&password=" + password, false);
	request.send();
	console.log(request);
	return request.response;
}