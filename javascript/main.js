function loginUser() {
	document.getElementById('login-screen').style.display = "none";
	document.getElementById('user-interface').style.display = "block";
	navigateTo('home-content');
}

function loginBlogger() {
	document.getElementById('login-screen').style.display = "none";
	document.getElementById('blogger-interface').style.display = "block";
	navigateTo('create-article');
}