<?php

	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb == 'GET') {
		//returns message of specific category
		if (isset($_GET['getcategories'])) {

			echo JSON_encode(get_categories_from_database());

		//checks if the login is correct
		} elseif (isset($_GET['username']) and isset($_GET['password'])) {

			$provided_username = $_GET['username'];
			$provided_password = $_GET['password'];

			$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
			$user_name = 'root';
			$pass_word = "";

			$connection = new PDO($dsn, $user_name, $pass_word);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT * FROM bloggercredentials where id = 1";

			$result = $connection->query($sql);

			foreach ($result as $row) {

				$username = $row['username'];
				$password = $row['password'];

			}

			if ($provided_username == $username and $provided_password == $password) {
				echo true;
			} else {
				echo false;
			}

		} elseif (isset($_GET['category'])) {

			$category = $_GET['category'];

			$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
			$user_name = 'root';
			$pass_word = "";

			$connection = new PDO($dsn, $user_name, $pass_word);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT b.id, c.category, b.message FROM blogposts b, categories c where c.category = '$category' AND b.category_id = c.id";

			$result = $connection->query($sql);

			$response = array();

			foreach ($result as $row) {
				$response[] = array($row['id'], $row['category'], $row['message']);
			}

			$json_response = json_encode($response);

			 echo $json_response;

		} else {

			http_response_code(200);
			$json_response = get_all_messages_from_API();
			echo $json_response;

		}

	} elseif ($verb == 'POST') {

		if (isset($_GET['categories']) and (isset($_GET['message']))) {

			http_response_code(200);
			write_message_to_database($_GET['categories'], $_GET['message']);

		} elseif (isset($_GET['category'])) {

			http_response_code(201);
			write_category_to_database($_GET['category']);

		} else {

			http_response_code(400);

		}

	} else {

		http_response_code(400);

	}

	function get_all_messages_from_API() {

		$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
		$user_name = 'root';
		$pass_word = "";

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT b.id, b.message, c.category FROM blogposts b, categories c WHERE b.category_id = c.id";

		$result = $connection->query($sql);

		$response = array();

		foreach ($result as $row) {
			$response[] = array($row['id'], $row['category'], $row['message']);
		}

		$json_response = json_encode($response);

		return $json_response;

		$connection = null;

	}

	function write_message_to_database($categories, $message) {


		$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
		$user_name = 'root';
		$pass_word = "";

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$category_id = get_category_id($categories);

		try {
			$sql = "INSERT INTO blogposts (category_id, message) " . "VALUES ('$category_id', '$message')";
			$connection->exec($sql);
			echo $message . " added to database";
		}
		catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}

		$connection = null; // Close connection

	}

	function write_category_to_database($category) {

		$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
		$user_name = 'root';
		$pass_word = "";

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		try {
			$sql = "INSERT INTO categories (category) " . "VALUES ('$category')";
			$connection->exec($sql);
			echo $category . " added to database";
		}
		catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}

		$connection = null; // Close connection

	}

	function get_categories_from_database() {
		$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
			$user_name = 'root';
			$pass_word = "";

			$connection = new PDO($dsn, $user_name, $pass_word);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "SELECT * FROM categories";

			$result = $connection->query($sql);

			$categories = [];

			foreach ($result as $row) {

				$categories[] = array($row['id'], $row['category']);

			}

			return $categories;
	}

	function get_category_id($categories) {

		$category_list = get_categories_from_database();

		for ($i = 0 ; $i < count($category_list) ; $i++) {
			if ($categories == $category_list[$i][1]) {
				return $category_list[$i][0];
			}
		}
	}
?>