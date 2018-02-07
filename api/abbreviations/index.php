<?php include '../globalFunctions.php';?>

<?php

	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb = "GET") {

		if (isset($_POST['abbreviation']) and isset($_POST['text'])) {

				if (checklogin()) {

				http_response_code(200);
				write_abbreviation_to_database($_POST['abbreviation'], $_POST['text']);

			}

		} else {

			$abbreviations = get_abbreviations_from_database();
			echo json_encode($abbreviations);

		}

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

?>