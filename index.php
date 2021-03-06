<html>

<head>

	<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />

</head>

<body>

	<?php include 'api/globalFunctions.php';?>

	<div id="user-interface">

		<div class="navbar" id="user-navbar">
			<div class="nav-element" onclick="navigateTo('home-content')">
				<p>Home</p>
			</div>

			<div class="nav-element" onclick="navigateTo('blog-content')">
				<p>Blog</p>
			</div>

			<?php if (checklogin()): ?>

				<div class="nav-element last-element" onclick="logoutBlogger()">
					<p>Log out</p>
				</div>

				<div class="nav-element last-element" onclick="manageArticles()">
					<p>Manage articles</p>
				</div>

			<?php else: ?>

				<div class="nav-element last-element" onclick="navigateTo('login-interface')">
					<p>Login</p>
				</div>

			<?php endif ?>

			<div class="nav-element last-element" onclick="navigateTo('about-content')">
				<p>About</p>
			</div>

		</div>

		<div id="home-content" class="content">

			<h1>Lorem Ipsum!</h1>

			<img src="images/blogger.jpg" id="blogger-picture">

			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla laoreet dui. Sed viverra velit sit amet lectus placerat, quis auctor sem malesuada. Fusce hendrerit nisi ut pulvinar volutpat. Nulla tristique eget ante eu lacinia. Suspendisse porta tincidunt magna nec posuere. Sed nec dictum lectus. Cras dapibus eget diam vel consequat. Praesent auctor ex eget dui porta egestas. Aliquam vulputate dapibus tincidunt. Donec dapibus nunc id tellus congue, scelerisque mattis orci commodo. Nulla luctus mi ut elit cursus hendrerit. Nullam eget arcu non eros convallis sollicitudin tristique sed sapien. Quisque non dui leo.</p>

			<p>Sed congue fringilla justo, quis fermentum dui egestas nec. Duis laoreet ante ligula. Vivamus leo magna, tincidunt quis velit nec, mattis fermentum metus. Sed vitae urna sollicitudin, convallis dui nec, rutrum quam. Proin viverra viverra facilisis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed at vestibulum neque. Phasellus orci mi, viverra eu fermentum a, blandit ac nisi. Duis id urna eget tortor auctor vehicula. Cras justo odio, porttitor ut ullamcorper id, consectetur nec nisl. Nulla dignissim nisl ipsum, feugiat pellentesque ante vestibulum in. Pellentesque quis vestibulum massa. Etiam cursus, arcu non ullamcorper tristique, leo orci tincidunt ipsum, et tempus mi massa eu diam. Donec in mi ac diam bibendum faucibus nec at elit.</p>

			<p>Fusce interdum nisi sit amet sollicitudin rhoncus. Nulla ultricies, augue sit amet fringilla cursus, nibh ligula dapibus mi, vel tincidunt sem sapien mattis neque. Vivamus molestie dapibus massa, vitae malesuada massa molestie a. Nam sollicitudin orci blandit tellus elementum tristique. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at lorem a ex condimentum iaculis ut vel lacus. Vivamus tempus eros id diam viverra, at lobortis ligula molestie. Nulla nec nunc fermentum sem efficitur tincidunt auctor eget lacus. In varius, nulla sit amet vulputate auctor, sem tortor suscipit risus, nec pellentesque felis magna nec ex. Mauris quis dapibus sem, in eleifend libero. Sed id gravida sapien. Vivamus mauris enim, convallis scelerisque dignissim in, rutrum pellentesque leo. Sed tortor sem, tristique eget ante eu, semper condimentum urna. Suspendisse vitae ligula porta, accumsan erat sed, faucibus ante. In ac ornare arcu. Nullam vehicula leo sit amet dolor mattis imperdiet.</p>

			<p>Mauris volutpat, nisi sit amet suscipit auctor, nulla tortor sodales ligula, quis commodo lacus nibh vitae metus. Nulla facilisi. Aenean id sodales risus. Integer euismod, dui ac tempor mattis, arcu dui vehicula nisi, vel luctus eros diam non tortor. Nam sit amet pulvinar orci. Aliquam blandit arcu mauris, vel ultricies enim facilisis nec. Sed a est vitae sem sodales fringilla a cursus purus.</p>

		</div>

		<div id="blog-content" class="content">

			<section id="categories">

			</section>

			<aside id="content">

			</aside>

		</div>

		<div id="login-interface" class="content">
			<form>
				<table>
					<tr>
						<td>Username:</td>
						<td><input id="username"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input id="password" type="password"></td>
					</tr>
				</table>
				<button onclick="loginBlogger(); return false"> log in </button>
			</form>
		</div>

		<div id="about-content" class="content">

			<h1>About me!</h1>

			<p>Phasellus ornare ex libero, et interdum justo interdum vitae. Fusce elit dui, accumsan eleifend mi at, gravida hendrerit orci. In sed augue et elit mollis scelerisque eget varius felis. Etiam ut odio eu mi blandit molestie id vel neque. Suspendisse sit amet urna malesuada ipsum tempor convallis. Integer viverra laoreet mi, ac mollis dolor. Pellentesque semper lorem at luctus egestas. Curabitur tellus sapien, venenatis eget pulvinar iaculis, lacinia id lectus. Sed et iaculis lacus, at pharetra turpis. Fusce at tellus vel lacus varius aliquam. Donec pretium nunc erat, fringilla lobortis turpis lobortis at. Vestibulum lacus mi, imperdiet a mattis vel, pharetra nec est. Curabitur sit amet fermentum libero. Ut rutrum quam nec bibendum pretium. Suspendisse purus ante, congue eu tempus facilisis, efficitur bibendum neque.</p>

			<p>Integer vehicula, justo et auctor eleifend, lectus tellus tempus risus, vehicula aliquam purus nunc nec quam. Phasellus eget est eget sem sagittis ornare id sed quam. Duis a porttitor enim. Proin cursus massa id dictum vehicula. Proin tincidunt, nisi gravida condimentum porttitor, ex risus mattis tellus, vitae maximus dui dolor in mi. Donec imperdiet orci orci, sed tempor massa venenatis eu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus ac euismod augue, ac porttitor orci. Praesent nisl ex, luctus sed vestibulum vel, fringilla id risus. Nullam nunc erat, finibus sed sollicitudin convallis, ultrices vel dui. Pellentesque nec tincidunt elit, ut dapibus odio. Donec rhoncus hendrerit pulvinar. Fusce non gravida risus. Duis interdum magna ipsum, in vestibulum augue rhoncus quis. Proin dolor eros, ultricies eu dictum eget, ultrices vel lacus.</p>

			<p>Nulla vehicula, mi eget blandit ullamcorper, magna odio ullamcorper lectus, sit amet varius augue eros ut lectus. Vivamus vel molestie dolor. Donec at commodo augue, non tincidunt turpis. Sed nisi ipsum, porttitor a est quis, auctor finibus augue. Vestibulum consectetur orci erat, quis consectetur neque ultricies ut. Pellentesque tempor ipsum id magna viverra pretium. Ut porttitor sagittis nisl, nec volutpat neque mollis vitae. Maecenas mollis semper magna. Nullam id sapien vitae velit dapibus porta. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed iaculis, nunc sit amet volutpat suscipit, massa lacus laoreet sapien, vel rhoncus arcu odio vel enim.</p>

			<p>In hac habitasse platea dictumst. Ut sit amet elit sodales, sagittis ipsum a, sodales orci. In id nisl blandit, malesuada mauris vel, pulvinar tortor. Proin et fermentum tortor. Pellentesque massa sapien, luctus vel felis at, placerat venenatis ipsum. Aliquam nec gravida ante, id facilisis lectus. In imperdiet laoreet varius. Vivamus pellentesque mauris nunc, et ullamcorper arcu iaculis vitae. Quisque blandit lorem vel arcu finibus, a vehicula orci ultrices. Fusce eu euismod felis, non dapibus ex. Donec ut ex sapien. Aenean in arcu non ligula auctor dignissim eu id sapien. Curabitur vulputate mollis fringilla.</p>

			<p>Mauris non metus sit amet neque varius convallis auctor non tortor. Nulla non neque lorem. Praesent vulputate eleifend purus. Nullam massa quam, ultrices vitae tellus et, fermentum tempor dui. Donec finibus tempor est vitae dignissim. Phasellus posuere, magna quis tincidunt posuere, justo nisl mollis erat, in bibendum erat lectus eget justo. Integer imperdiet consequat odio eget faucibus. Nunc ac nisi sit amet eros faucibus vulputate a non felis.</p>


		</div>

	</div>

	<?php 

		if (checklogin()) {

			include 'blogger-page.php';

	?>

	<script type="text/javascript" src="javascript/bloggerInterface.js"></script>

	<?php

		}

	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
	<script type="text/javascript" src="javascript/support.js"></script>
	<script type="text/javascript" src="javascript/main.js"></script>
	<!--
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<script>
	        tinymce.init({
	            selector: "textarea",
	            plugins: [
	                "advlist autolink lists link image charmap print preview anchor",
	                "searchreplace visualblocks code fullscreen",
	                "insertdatetime media table contextmenu paste"
	            ],
	            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	            height: "380",
			    resize: false
			});

	</script>
-->


</body>

</html>