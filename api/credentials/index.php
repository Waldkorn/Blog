<?php include '../globalFunctions.php';?>
<?php

$verb = $_SERVER['REQUEST_METHOD'];
$credentials = get_blogger_credentials_from_database();

if ($verb == "GET") {

	if (isset($_GET['username']) and isset($_GET['password'])) {

		http_response_code(200);

		$provided_username = $_GET['username'];
		$provided_password = $_GET['password'];

		$_SESSION["username"] = $provided_username;
		$_SESSION["password"] = $provided_password;

		if ($provided_username == $credentials['username'] and $provided_password == $credentials['password']) {

			echo true;

		} else {

			echo false;

		}
		
	} elseif ($_SESSION["username"] == $credentials['username'] and $_SESSION["password"] == $credentials['password']) {

		echo true;

	} else {

		echo false;

	}

}

?>