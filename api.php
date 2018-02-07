<?php

	session_start();

	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];

	$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
	$user_name = 'root';
	$pass_word = "";

	if ($verb == 'GET') {
		//returns message of specific category
		if (isset($_GET['getcategories'])) {

			echo JSON_encode(get_categories_from_database());

		//checks if the login is correct
		} elseif (isset($_GET['username']) and isset($_GET['password'])) {

			http_response_code(200);

			$provided_username = $_GET['username'];
			$provided_password = $_GET['password'];

			$_SESSION["username"] = $provided_username;
			$_SESSION["password"] = $provided_password;

			$credentials = get_blogger_credentials_from_database();

			if ($provided_username == $credentials['username'] and $provided_password == $credentials['password']) {

				echo true;

			} else {

				echo false;

			}

		// Gets all messages from database with specific category
		} elseif (isset($_GET['category'])) {

			http_response_code(200);

			$messages = get_messages_from_database_by_category($_GET['category']);

			$response = array();

			foreach ($messages as $row) {

				$response[] = array($row['id'], $row['category'], $row['message']);

			}

			echo json_encode($response);

		} elseif (isset($_GET['abbreviations'])) {

			$abbreviations = get_abbreviations_from_database();
			echo json_encode($abbreviations);

		} elseif (isset($_GET['comments'])) {

			http_response_code(200);
			$comments = get_comments_from_database();

			echo json_encode($comments);

		} else {

			http_response_code(200);
			echo get_all_messages_from_API();

		}

	} elseif ($verb == 'POST') {

		if(checklogin()) {

			if (isset($_GET['categories']) and (isset($_GET['message']))) {

				http_response_code(200);
				write_message_to_database($_GET['categories'], $_GET['message']);

			} elseif (isset($_GET['category'])) {

				http_response_code(200);
				write_category_to_database($_GET['category']);

			} elseif (isset($_POST['abbreviation']) and isset($_POST['text'])) {

				http_response_code(200);
				write_abbreviation_to_database($_POST['abbreviation'], $_POST['text']);

			} elseif (isset($_GET['id']) and isset($_GET['comment'])) {

				http_response_code(200);
				write_comment_to_database($_GET['id'], $_GET['comment']);

			} elseif (isset($_GET['commentremove']) and isset($_GET['id'])) {

				http_response_code(200);
				remove_comment_from_database($_GET['id']);

			} elseif (isset($_GET['setcommentallowed']) and isset($_GET['id']) and isset($_GET['value'])) {

				http_response_code(200);
				allow_disallow_comments_for_post($_GET['id'], $_GET['value']);

			} else {

				echo "not so great succes :(";
				http_response_code(400);

			}

		} else {

			http_response_code(401);
			echo "blogger not logged in";

		}

	} elseif ($verb == 'DELETE') {

		if(checklogin()) {

			if (isset($_GET['article']) and isset($_GET['id'])){

				http_response_code(200);
				remove_message_from_database($_GET['id']);

			} else {

				http_response_code(400);

			}

		} else {

			http_response_code(401);
			echo "blogger not logged in";

		}

	} else {

		http_response_code(400);

	}

	function get_all_messages_from_API() {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT b.id, b.message, c.category, b.comments_allowed FROM blogposts b, categories c, blogpost_categories bc WHERE bc.category_id = c.id AND bc.blogpost_id = b.id";

		$result = $connection->query($sql);

		$response = array();

		foreach ($result as $row) {
			$response[] = array($row['id'], $row['category'], $row['message'], $row['comments_allowed']);
		}

		$json_response = json_encode($response);

		return $json_response;

		$connection = null;

	}

	function write_message_to_database($categories, $message) {

			global $dsn;
			global $user_name;
			global $pass_word;

			$connection = new PDO($dsn, $user_name, $pass_word);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$category_id_list = get_category_id($categories);

			//var_dump($category_id_list);

			$sql = "INSERT INTO blogposts (message) " . "VALUES ('$message')";
			//echo $message . " added to database";
			$connection->exec($sql);

			$sql = "SELECT blogposts.id FROM blogposts";

			$result = $connection->query($sql);

			$ids=[];

			foreach ($result as $row) {

				$ids[] = $row['id'];

			}

			$last_id = $ids[count($ids) - 1];

			for ($i = 0 ; $i < count($category_id_list) ; $i++) {
				$sql = "INSERT INTO blogpost_categories (blogpost_id, category_id) " . "VALUES ('$last_id', '$category_id_list[$i]')";
				$connection->exec($sql);
			}

			$connection = null; // Close connection



	}

	function write_category_to_database($category) {

		global $dsn;
		global $user_name;
		global $pass_word;

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

		global $dsn;
		global $user_name;
		global $pass_word;

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

		$category_id_list = [];

		$categories = explode(",", $categories);

		for ($i = 0 ; $i < count($category_list) ; $i++) {
			for ($j = 0 ; $j < count($categories) ; $j++) {
				if ($categories[$j] == $category_list[$i][1]) {
					$category_id_list[] = $category_list[$i][0];
				}
			}
		}

		return $category_id_list;
	}

	// Gets blogger credentials from the database
	function get_blogger_credentials_from_database() {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT * FROM bloggercredentials where id = 1";

		$result = $connection->query($sql);

		foreach ($result as $row) {

			$credentials = array(

				"username" => $row['username'],
				"password" => $row['password']

			);	

		}

		return $credentials;

	}

	function get_messages_from_database_by_category($category) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT b.id, c.category, b.message FROM blogposts b, categories c, blogpost_categories bc 
				where bc.blogpost_id = b.id AND bc.category_id = c.id AND
				c.category = '$category'";

		return $connection->query($sql);
	}

	function remove_message_from_database($id) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "DELETE FROM blogpost_categories WHERE blogpost_categories.blogpost_id = '$id';
				DELETE FROM blogposts WHERE blogposts.id = '$id';";

		$connection->exec($sql);

	}

	function get_abbreviations_from_database() {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT s.abbreviation, s.text FROM shortcuts s";

		$result = $connection->query($sql);

		$abbreviations = array("bararpadpads" => "testestsegtst");

		foreach ($result as $row) {

			$addition = array($row['abbreviation'] => $row['text']);

			$abbreviations = array_merge($abbreviations, $addition);

		}

		return $abbreviations;

	}

	function write_abbreviation_to_database($abbreviation, $text) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "INSERT INTO shortcuts (abbreviation, text)" . "VALUES ('$abbreviation', '$text')";
		$connection->exec($sql);

		echo $abbreviation . " : " . $text . " added to database";

		$connection = null; // Close connection

	}

	function get_comments_from_database() {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT c.blogpost_id, c.comment, c.id FROM comments c";

		$result = $connection->query($sql);

		$comments = [];

		foreach ($result as $row) {

			$comments[] = array($row['blogpost_id'], $row['comment'], $row['id']);
		}

		return $comments;		

	}

	function write_comment_to_database($id, $comment) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "INSERT INTO comments (blogpost_id, comment)" . "VALUES ('$id', '$comment')";
		$connection->exec($sql);

		echo $comment . " added to database";

		$connection = null; // Close connection

	}

	function remove_comment_from_database($id) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "DELETE FROM comments WHERE comments.id = '$id'";

		$connection->exec($sql);

	}

	function allow_disallow_comments_for_post($id, $value) {

		global $dsn;
		global $user_name;
		global $pass_word;

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($value === "true") {

			$sql = "UPDATE blogposts SET comments_allowed = '1' WHERE id = '$id'";

		} elseif ($value === 'false') {

			$sql = "UPDATE blogposts SET comments_allowed = '0' WHERE id = '$id'";

		}

		$connection->exec($sql);

	}

	function checkLogin() {

		$credentials = get_blogger_credentials_from_database();

		if ($_SESSION["username"] == $credentials['username'] and $_SESSION["password"] == $credentials['password']) {

			return true;


		} else {

			return false;

		}

	}
?>