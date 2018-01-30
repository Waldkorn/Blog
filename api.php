<?php

	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb == 'GET') {

		if (isset($_GET['category'])) {

		} else {

			http_response_code(200);
			$json_response = getAllMessageFromAPI();
			echo $json_response;

		}

	} elseif ($verb == 'post') {

	} else {

		http_response_code(400);

	}

	function getAllMessageFromAPI () {

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
?>