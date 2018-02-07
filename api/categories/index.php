<?php include '../globalFunctions.php';?>

<?php

	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb == "GET") {

		echo JSON_encode(get_categories_from_database());

	} elseif ($verb == "POST") {

		if (checklogin()) {

			if (isset($_GET['category'])) {

				http_response_code(200);
				write_category_to_database($_GET['category']);

			} 

		} else {

			http_response_code(401);
			echo "blogger not logged in";

		}

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

?>

