<html>

<head>
</head>
<body>

<div id="blogger-interface">

	<div class="navbar" id="blogger-navbar">

		<div class="nav-element" onclick="navigateTo('create-article')">

			<p>Create article</p>

		</div>

		<div class="nav-element" onclick="navigateTo('manage-articles')">

			<p>Manage articles</p>

		</div>

		<div class="nav-element" onclick="navigateTo('add-category')">

			<p>Add category</p>

		</div>

		<div class="nav-element" onclick="navigateTo('add-abbreviation')">

			<p>Add abbreviation</p>

		</div>

		<div class="nav-element last-element" onclick="goHome()">

			<p>Home</p>

		</div>

	</div>

	<div id="create-article" class="content">

		<form id="create-article-form">

			Category:
			<select id="categories-message">
			</select>
			<div id="inserted-categories"></div>
			<button id="add-category-button" onclick="addCategoryToMessage(); return false;">Add category</button><br><br>
			<textarea id="write-message" rows=33 cols=160></textarea><br>

			<button onclick="submitMessage(); return false;">Submit message</button>

		</form>

	</div>

	<div id="manage-articles" class="content">

		<table id="remove-article-table" border="1">

		</table>

	</div>

	<div id="add-category" class="content">

	</div>

	<div id="add-abbreviation" class="content">

		<table id="abbreviation-list" border="1">

		</table>

		<input id="abbreviation-input" placeholder="Abbreviation"><br>
		<input id="abbreviation-text-input" placeholder="Text"><br>

		<button onclick="submitAbbreviation()">Submit</button>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
	<script type="text/javascript" src="javascript/support.js"></script>

</div>

</body>