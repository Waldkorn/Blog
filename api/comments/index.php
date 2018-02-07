<?php include '../globalFunctions.php';?>
<?php

	$verb = $_SERVER['REQUEST_METHOD'];

	if ($verb == 'GET') {
		if (isset($_GET['comments'])) {

			http_response_code(200);
			$comments = get_comments_from_database();

			echo json_encode($comments);

		}

	} elseif ($verb == 'POST') {

		if(checklogin()) {

			if (isset($_GET['id']) and isset($_GET['comment'])) {

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

	} else {

		http_response_code(400);

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

?>