loginUser();

shortcuts = getListOfAbbreviations();

// Navigates to a specific page
function navigateTo(contentToDisplay) {

	switch (contentToDisplay) {

		case "blog-content":
			blogContent = getAllBlogContentFromAPI();
			displayBlogContent(blogContent);

			categoryContent = getAllCategoriesFromAPI();
			displayCategories(categoryContent);
			break;
		case "add-category":
			refreshCategoriesForBlogger();
			break;
		case "create-article":
			categories = getAllCategoriesFromAPI();
			createDropDownCategories(categories);
			break;
		case "manage-articles":
			blogposts = getAllBlogContentFromAPI();
			createTableWithBlogposts(blogposts);
			break;
		case "add-abbreviation":
			abbreviations = getListOfAbbreviations();
			createAbbreviationList(abbreviations);
			break;
		case "remove-comment":
			comments = getCommentsFromServer();
			createCommentsList(comments);
			break;

	}

	$('.content').hide();
	$('#' + contentToDisplay).show();

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

		document.getElementById('user-interface').style.display = "none";
		document.getElementById('blogger-interface').style.display = "block";

		navigateTo('create-article');

	} else {

		alert("Please provide valid login credentials");

	}
	
}

// Displays blogposts depending on the category provided
function displayContentByCategory(category) {

	$(".blogpost").hide();
	$("." + category).show();

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