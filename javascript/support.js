var currentContent = "";
var request = new XMLHttpRequest();

function navigateTo(contentToDisplay) {

	if (currentContent != contentToDisplay) {

		if (contentToDisplay == 'blog-content') {

			request.open("GET", "api.php", false);
			request.send();

			//console.log(request.response);

			//console.log(request.response);

			response = JSON.parse(request.response);

			displayBlogContent(response);

			request.open("get", "api.php?getcategories=yes", false);
			request.send();

			//console.log(request.response);

			categories = JSON.parse(request.response);

			displayCategories(categories);
			
		}

		if (contentToDisplay == "add-category") {

			refreshCategoriesForBlogger();

		}

		if (contentToDisplay == "create-article") {


			request.open("get", "api.php?getcategories=yes", false);
			request.send();

			categories = JSON.parse(request.response);

			document.getElementById("categories-message").innerHTML = "<option value=" + categories[0][1] + ">" + categories[0][1] + "</option>";
			
			for (i = 1 ; i < categories.length ; i++) {

				document.getElementById("categories-message").innerHTML += "<option value=" + categories[i][1] + ">" + categories[i][1] + "</option>";

			}

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

	//console.log(content);

	content.sort(sortFunction);

	//content.reverse();
	highestid = 5000000000;

	console.log(content);

	document.getElementById('content').innerHTML = "";
	for (i = content.length - 1 ; i > 0 ; i--) {
		console.log(content[i]);
		if (content[i][0] < highestid) {
			document.getElementById('content').innerHTML += "<div class='blogpost' id=blogpost" + content[i][0] + "></div>";
			document.getElementById('blogpost' + content[i][0]).innerHTML = "<div class='blogpostcategory' id='blogpostcategory" + content[i][0] + "'>" + content[i][1] + "</div>";
			document.getElementById('blogpost' + content[i][0]).innerHTML += "<div class='blogpost-message'>" + content[i][2] + "</div>";
			highestid = content[i][0];
			console.log("greater than");
		} else if (content[i][0] == highestid) {
			document.getElementById("blogpostcategory" + content[i][0]).innerHTML += ", " + content[i][1];
			console.log("equals");
		}
	}
}

//post een message naar de sever
function submitMessage() {
	categories = categoriesToAdd;
	message = tinymce.get('write-message').getContent({format: 'raw'});

	request.open("POST", "api.php?categories=" + categories + "&message=" + message, false);
	request.send();

	console.log(request.response);
}

function displayCategories(categories) {
	document.getElementById('categories').innerHTML = "";
	for (i = 0 ; i < categories.length ; i++) {
		document.getElementById('categories').innerHTML += 
		"<div class=category-element onclick=displayContentByCategory('" + categories[i][1] + "')>" + categories[i][1] + "</div>";
	}
}

function createCategory() {
	newCategory = document.getElementById('new-category').value;

	request.open("POST", "api.php?category=" + newCategory, false);
	request.send();

	newCategory = "";

	refreshCategoriesForBlogger();
}

function refreshCategoriesForBlogger() {
	request.open("get", "api.php?getcategories=yes", false);
	request.send();

	categoriesObject = JSON.parse(request.response);

	categories = [];

	for (i = 0 ; i < categoriesObject.length ; i++) {
		categories[i] = categoriesObject[i][1];
	}

	displayCategories(categories);


	contentWindow = document.getElementById('add-category');

	contentWindow.innerHTML = "<table id='table'></table>";

	for (i = 0 ; i < categories.length ; i++) {


		tr = document.createElement("tr");
		td = document.createElement("td");
		txt = document.createTextNode(categories[i]);

		td.appendChild(txt);
		tr.appendChild(td);
		document.getElementById('table').appendChild(tr);


	}

	contentWindow.innerHTML += "<input id='new-category' placeholder='Add new category...'>";

	contentWindow.innerHTML += "<button onclick='createCategory()'>Add Category</button>";
}

function sortFunction(a, b) {
    if (parseInt(a[0]) === parseInt(b[0])) {
        return 0;
    }
    else {
        return (parseInt(a[0]) < parseInt(b[0])) ? -1 : 1;
    }
}