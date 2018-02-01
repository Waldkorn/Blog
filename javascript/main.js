var categoriesToAdd = [];

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
		document.getElementById('add-category').style.display = "none";
		document.getElementById('blogger-interface').style.display = "block";
		navigateTo('create-article');
	}
}

function checkLogin(username, password) {
	request.open("GET", "api.php?username=" + username + "&password=" + password, false);
	request.send();

	console.log(request.response);

	if (request.response === "1") {
		return true;
	} else {
		return false;
	}
}

function displayContentByCategory(category) {

	$("#content").toggle('fast');

	request.open("GET", "api.php?category=" + category, false);
	request.send();

	response = JSON.parse(request.response);

	displayBlogContent(response);

	$("#content").toggle('fast');
}

function addCategoryToMessage() {

	category = document.getElementById("categories-message").value;

	categoriesToAdd.push(category);

	request.open("get", "api.php?getcategories=yes", false);
	request.send();

	categories = JSON.parse(request.response);
	categoriesWithoutUsedOnes = [];

	for (i = 0 ; i < categories.length ; i++) {

		if (!categoriesToAdd.includes(categories[i][1]) && categories[i][1]!= "undefined") {
			categoriesWithoutUsedOnes.push(categories[i][1])
		}

	}

	document.getElementById("categories-message").innerHTML = "<option value=" + categoriesWithoutUsedOnes[0] + ">" + categoriesWithoutUsedOnes[0] + "</option>";
	
	for (i = 1 ; i < categoriesWithoutUsedOnes.length ; i++) {

		document.getElementById("categories-message").innerHTML += "<option value=" + categoriesWithoutUsedOnes[i] + ">" + categoriesWithoutUsedOnes[i] + "</option>";

	}

}