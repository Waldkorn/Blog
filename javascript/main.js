loginUser();

shortcuts = getListOfAbbreviations();

// Navigates to a specific page
function navigateTo(contentToDisplay) {

	if (currentContent != contentToDisplay) {

		if (contentToDisplay == 'blog-content') {

			blogContent = getAllBlogContentFromAPI();
			displayBlogContent(blogContent);

			categoryContent = getAllCategoriesFromAPI();
			displayCategories(categoryContent);
			
		} else if (contentToDisplay == "add-category") {

			refreshCategoriesForBlogger();

		} else if (contentToDisplay == "create-article") {

			categories = getAllCategoriesFromAPI();

			// Creates drop down menu of all the categories for blogger
			document.getElementById("categories-message").innerHTML = "<option value=" + categories[0][1] + ">" + categories[0][1] + "</option>";
			
			for (i = 1 ; i < categories.length ; i++) {

				document.getElementById("categories-message").innerHTML += "<option value=" + categories[i][1] + ">" + categories[i][1] + "</option>";

			}

		} else if (contentToDisplay == "remove-article") {

			//maakt een tabel met alle blogposts
			blogposts = getAllBlogContentFromAPI();
			blogposts.sort(sortFunction);
			maxid = -1;
			blogpostsNonDuplicates = [];

			for (i = 0 ; i < blogposts.length ; i++) {
				if (parseInt(blogposts[i][0]) > maxid) {
					blogpostsNonDuplicates.push(blogposts[i]);
					maxid = parseInt(blogposts[i][0]);
				}
			}

			table = document.getElementById("remove-article-table");

			table.innerHTML = "";

			for (i = 0 ; i < blogpostsNonDuplicates.length ; i++) {

				if (blogpostsNonDuplicates[i][3] === "1") {

					commentSelection = "<td><form id=radioform"+ blogpostsNonDuplicates[i][0] + ">" +
						"<input type=radio name=comments value='true' checked='checked'>Comments allowed<br>" +
						 "<input type=radio name=comments value='false'>No comments allowed" +
						 "<br>" +
						 "<button type=button onclick=setComments(" + blogpostsNonDuplicates[i][0] + "); return false;>submit</button></td></form>";

					} else {

						commentSelection = "<td><form id=radioform"+ blogpostsNonDuplicates[i][0] + ">" +
							"<input type=radio name=comments value='true'>Comments allowed<br>" +
							 "<input type=radio name=comments value='false' checked='checked'>No comments allowed" +
							 "<br>" +
							 "<button type=button onclick=setComments(" + blogpostsNonDuplicates[i][0] + "); return false;>submit</button></td></form>";						

					}

				table.innerHTML += "<tr>";
				table.innerHTML += "<td>" + blogpostsNonDuplicates[i][0] + "</td><td>" + blogpostsNonDuplicates[i][2].substring(0,180) 
									+ "...</td><td><div class='remove' onclick='removeArticle(" + blogpostsNonDuplicates[i][0] + "); return false'>Remove</td>" +
									commentSelection;
				table.innerHTML += "</tr>";

			}

		} else if (contentToDisplay == "add-abbreviation") {

			shortcuts = getListOfAbbreviations();

			$('#abbreviation-list').empty();

			var tableHeader = "<tr><th>Abbreviation</th><th>Text</th></tr>";

			document.getElementById("abbreviation-list").innerHTML += tableHeader;

			for (i = 0 ; i <  Object.keys(shortcuts).length ; i++) {

				var newRow = "<tr><td>" + Object.keys(shortcuts)[i] + "</td><td>" + shortcuts[Object.keys(shortcuts)[i]] + "</td></tr>";

				document.getElementById('abbreviation-list').innerHTML += newRow;

			}

		} else if (contentToDisplay == "remove-comment") {

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

		if (currentContent != "") {

			// Hides the content it is currently showing
			$("#" + currentContent).toggle('slow');

		}

		// Shows the content you are navigating to
		$("#" + contentToDisplay).toggle('slow');

		currentContent = contentToDisplay;

	}

}

// Navigates the page to the user interface
function loginUser() {

	document.getElementById('user-interface').style.display = "block";
	navigateTo('home-content');

}
// Checks blogger credentials and logs in the blogger if the API returns true
function loginBlogger() {

	username = document.getElementById("username").value;
	password = document.getElementById("password").value;

	if (checkLogin(username, password)) {

		document.getElementById("remove-comment").style.display = "none";
		document.getElementById('user-interface').style.display = "none";
		document.getElementById('add-category').style.display = "none";
		document.getElementById('add-abbreviation').style.display = "none";
		document.getElementById('blogger-interface').style.display = "block";
		navigateTo('create-article');

	} else {

		alert("Please provide valid login credentials");

	}
	
}

// Displays blogposts depending on the category provided
function displayContentByCategory(category) {

	$("#content").toggle('fast');

	request.open("GET", "api/messages/index.php?category=" + category, false);
	request.send();

	response = JSON.parse(request.response);

	displayBlogContent(response);

	$("#content").toggle('fast');

}

window.onload = function() {
    var ta = document.getElementById("write-message");
    var timer = 0;
    var re = new RegExp("\\b(" + Object.keys(shortcuts).join("|") + ")\\b", "g");
    

    
    update = function() {
        ta.value = ta.value.replace(re, function($0, $1) {
            return shortcuts[$1];
        });
    }
    
    ta.onkeydown = function() {
    	
        clearTimeout(timer);
        timer = setTimeout(update, 200);

    }

}