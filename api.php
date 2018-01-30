<?php

	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb == 'GET') {

		if (isset($_GET['category'])) {

		} else {

			http_response_code(200);
			$json_response = get_all_messages_from_API();
			echo $json_response;

		}

	} elseif ($verb == 'POST') {

		var_dump(array(isset($_GET['categories']), isset($_GET['message'])));

		if (isset($_GET['categories']) and (isset($_GET['message']))) {

			http_response_code(200);
			write_message_to_database($_GET['categories'], $_GET['message']);

		} else {

			http_response_code(400);

		}

	} else {

		http_response_code(400);

	}

	function get_all_messages_from_API () {

	$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
	$user_name = 'root';
	$pass_word = "";

	$connection = new PDO($dsn, $user_name, $pass_word);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT * FROM blogposts";

	$result = $connection->query($sql);

	$answer = array();

	foreach ($result as $row) {
		$answer[] = array($row['id'], $row['categories'], $row['message']);
			//$answer . $row['id'] . ",";
	}

	$json_answer = json_encode($answer);

	return $json_answer;

	$connection = null;

	}

	function write_message_to_database($categories, $message) {

		$dsn = 'mysql:host=127.0.0.1;dbname=blogdb';
		$user_name = 'root';
		$pass_word = "";

		$connection = new PDO($dsn, $user_name, $pass_word);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		try {
		$sql = "INSERT INTO blogposts (categories, message) " . "VALUES ('$categories', '$message')";
		$connection->exec($sql);
		echo $message . " added to database";
		}
		catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
		}
		$connection = null; // Close connection

	}
?>