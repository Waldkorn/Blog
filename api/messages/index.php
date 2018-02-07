<?php include '../globalFunctions.php';?>

<?php

$verb = $_SERVER['REQUEST_METHOD'];

if ($verb == "GET") {

	if (isset($_GET['category'])) {

		http_response_code(200);
		echo get_messages_from_database_by_category($_GET['category']);

	} else {

		http_response_code(200);
		echo get_all_messages_from_API();

	}

} elseif ($verb == "POST") {

	if (checklogin()) {

		if (isset($_GET['categories']) and (isset($_GET['message']))) {

			http_response_code(200);
			write_message_to_database($_GET['categories'], $_GET['message']);

		} elseif (isset($_GET['article']) and isset($_GET['id'])){

			http_response_code(200);
			remove_message_from_database($_GET['id']); 
		}

	} else {

		http_response_code(401);
		echo "blogger not logged in";

	}

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

function get_messages_from_database_by_category($category) {

	global $dsn;
	global $user_name;
	global $pass_word;

	$connection = new PDO($dsn, $user_name, $pass_word);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT b.id, c.category, b.message, b.comments_allowed FROM blogposts b, categories c, blogpost_categories bc 
			where bc.blogpost_id = b.id AND bc.category_id = c.id AND
			c.category = '$category'";

	$messages = $connection->query($sql);

	$response = array();

	foreach ($messages as $row) {

		$response[] = array($row['id'], $row['category'], $row['message'], $row['comments_allowed']);

	}

	return json_encode($response);

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

?>