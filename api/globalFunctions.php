<?PHP

session_start();

$dsn = 'mysql:host=localhost;dbname=tomklru270_ewoutblog';
$user_name = 'tomklru270_ewout';
$pass_word = "password";

function checkLogin() {

	$credentials = get_blogger_credentials_from_database();

	if ($_SESSION["username"] == $credentials['username'] and $_SESSION["password"] == $credentials['password']) {

		return true;

	} else {

		return false;

	}

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

?>