var categoriesToAdd = [];
var currentContent = "";
var request = new XMLHttpRequest();

// Posts new category to the server
function createCategory() {

	newCategory = document.getElementById('new-category').value;

	request.open("POST", "api/categories/index.php?category=" + newCategory, false);
	request.send();

	newCategory = "";

	refreshCategoriesForBlogger();

}

// Takes an array with categories and displays them
function displayCategories(categories) {

	document.getElementById('categories').innerHTML = "";
	for (i = 0 ; i < categories.length ; i++) {
		document.getElementById('categories').innerHTML += 
		"<div class=category-element onclick=displayContentByCategory('" + categories[i][1] + "')>" + categories[i][1] + "</div>";
	}

}

// Returns true if Login credentials match
function checkLogin(username, password) {

	request.open("GET", "api/credentials/index.php?username=" + username + "&password=" + password, false);
	request.send();

	if (request.response === "1") {

		return true;

	} else {

		return false;

	}
	
}

//post new message to the server
function submitMessage() {

	categories = categoriesToAdd;

	if (categories.length == 0) {

		alert("Please provide at least one category");

	} else {

		//message = tinymce.get('write-message').getContent({format: 'raw'});
		message = document.getElementById('write-message').value;

		request.open("POST", "api/messages/index.php?categories=" + categories + "&message=" + message, false);
		request.send();

		if (request.response === "blogger not logged in") {

			alert("Please log in to post messages")

		}

		console.log(request.response);

		location.reload();

	}

}

// Stores the categories that the blogger selected so it can add it to the message.
function addCategoryToMessage() {

	category = document.getElementById("categories-message").value;

	// Adds the selected category to a list of categories that have been stored and will be forwarded along with the message
	categoriesToAdd.push(category);

	categories = getAllCategoriesFromAPI();

	redactedCategories = [];

	// Removes categories from the drop-down menu so the blogger can't add the same category twice, also check if category isn't nothing
	for (i = 0 ; i < categories.length ; i++) {

		if (!categoriesToAdd.includes(categories[i][1]) && categories[i][1]!= "undefined") {

			redactedCategories.push(categories[i][1])

		}

	}

	// Display leftover categories in the drop down menu
	document.getElementById("categories-message").innerHTML = "<option value=" + redactedCategories[0] + ">" + redactedCategories[0] + "</option>";
	
	for (i = 1 ; i < redactedCategories.length ; i++) {

		document.getElementById("categories-message").innerHTML += "<option value=" + redactedCategories[i] + ">" + redactedCategories[i] + "</option>";

	}

}

// function that displays blog posts and adds the appropriate category/categories to it.
function displayBlogContent(content) {

	document.getElementById('content').innerHTML = "";

	comments = getCommentsFromServer();

	for (i = content.length - 1 ; i > 0 ; i--) {

		document.getElementById('content').innerHTML += "<div class='blogpost' id=blogpost" + content[i][0] + "></div>";
		document.getElementById('blogpost' + content[i][0]).innerHTML = "<div class='blogpostcategory' id='blogpostcategory" + content[i][0] + "'></div>";
		document.getElementById('blogpost' + content[i][0]).innerHTML += "<div class='blogpost-message'>" + content[i][2] + "</div>";

		categories = content[i][1];

		for (j = 0 ; j < categories.length ; j++) {

			document.getElementById("blogpostcategory" + content[i][0]).innerHTML += " " + categories[j][0];
			document.getElementById("blogpost" + content[i][0]).classList.add(categories[j][0]);

		}

		document.getElementById('blogpost' + content[i][0]).innerHTML += "<hr><div class='comments' id=comments" + content[i][0] + "></div>";

		if (content[i][3] === "1") {

			commentsForThisPost = content[i][4];
			console.log(commentsForThisPost);

			for (j = 0 ; j < commentsForThisPost.length ; j++) {

					document.getElementById('comments' + content[i][0]).innerHTML += "<div class='comment'>" + commentsForThisPost[j][0] + "</div>";

				}

				document.getElementById('comments' + content[i][0]).innerHTML += "<input id=post-comment" + content[i][0] + " placeholder='comment'></input>";
				document.getElementById('comments' + content[i][0]).innerHTML += "<button onclick=postComment(" + content[i][0] + ")>Post comment</button>";

			} else {

				document.getElementById('comments' + content[i][0]).innerHTML = "<div class=nocomments>No comments allowed</div>"

			}
		

		/*

		if (content[i][3] === "1") {

			commentsForThisPost = getCommentsById(comments, content[i][0]);

			for (j = 0 ; j < commentsForThisPost.length ; j++) {

				document.getElementById('comments' + content[i][0]).innerHTML += "<div class='comment'>" + commentsForThisPost[j] + "</div>";

			}

			document.getElementById('comments' + content[i][0]).innerHTML += "<input id=post-comment" + content[i][0] + " placeholder='comment'></input>";
			document.getElementById('comments' + content[i][0]).innerHTML += "<button onclick=postComment(" + content[i][0] + ")>Post comment</button>";

		} else {

			document.getElementById('comments' + content[i][0]).innerHTML = "<div class=nocomments>No comments allowed</div>"

		}
		*/

	}

}

// Returns an array with all blog posts
function getAllBlogContentFromAPI() {

	request.open("GET", "api/messages/", false);
	request.send();

	console.log(JSON.parse(request.response));

	return JSON.parse(request.response);

}

// Returns an array with all categories
function getAllCategoriesFromAPI() {

	request.open("get", "api/categories/", false);
	request.send();

	return JSON.parse(request.response);

}

// Supporting function which helps sort the array of messages
function sortFunction(a, b) {
    if (parseInt(a[0]) === parseInt(b[0])) {
        return 0;
    }
    else {
        return (parseInt(a[0]) < parseInt(b[0])) ? -1 : 1;
    }
}

// Grabs all message from the API and displays them in a table, also adds an "add new category" button
function refreshCategoriesForBlogger() {

	categoriesObject = getAllCategoriesFromAPI();
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

function removeArticle(id) {

	if (confirm("Are you sure you want to remove blogpost with id " + id + " from the database?")  == true) {

		request.open("POST", "api/messages/index.php?article=yes&id=" + id, false);
		request.send();

		alert("Article succesfully deleted");

		navigateTo("create-article");

	}
}

function getListOfAbbreviations() {

	request.open("GET", "api/abbreviations/", false);
	request.send();

	return JSON.parse(request.response);
}

function getCommentsFromServer() {

	request.open("GET", "api/comments/index.php?comments=yes", false);
	request.send();

	return JSON.parse(request.response);

}

/*
function getCommentsById(comments, index) {

	result = [];

	for (k = 0 ; k < comments.length ; k++) {

		if (comments[k][0] == index) {

			result.push(comments[k][1]);

		}

	}

	return result;

}
*/

function postComment(id) {

	comment = document.getElementById("post-comment" + id).value;

	request.open("POST", "api/comments/index.php?id=" + id + "&comment=" + comment , false);
	request.send();

	console.log(request.response);

	blogContent = getAllBlogContentFromAPI();
	displayBlogContent(blogContent);

}

function removeComment(id) {

	if (confirm("Are you sure you want to remove comment from blogpost " + id + " from the database?") == true) {

		request.open("POST", "api/comments/index.php?commentremove=yes&id=" + id, false);
		request.send();

		console.log(request.response);

		comments = getCommentsFromServer();

		$('#comment-list').empty();

		var tableHeader = "<tr><th>Blogpost</th><th>Comment</th><th>Remove</th></tr>";

		document.getElementById("comment-list").innerHTML += tableHeader;

		for (i = 0 ; i < comments.length ; i++) {

			var tableRow = "<tr><td>" + comments[i][0] + "</td><td>" + comments[i][1] + "</td><td><div class='remove-comment-cell' onclick=removeComment(" 
								+ comments[i][2] + ")>Remove</div>"
			document.getElementById("comment-list").innerHTML += tableRow;

		}

	}

}

function setComments(id) {

	outcome = $('input[name=comments]:checked', '#radioform' + id).val();

	request.open("POST", "api/comments/index.php?setcommentallowed=yes&id=" + id + "&value=" + outcome, false);
	request.send();

	alert("comments allowed for blogpost " + id + " set to " + outcome);

	navigateTo("create-article");

}