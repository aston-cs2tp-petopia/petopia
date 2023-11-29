<?php
//if the form has been submitted
if (isset($_POST['submitted'])) {
	#prepare the form input

	// connect to the database
	require_once('connectdb.php');

	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$username = isset($_POST['username']) ? $_POST['username'] : false;
	$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;

	if (!($email)) {
		echo "Email wrong!";
		exit;
	}
	if (!($username)) {
		echo "Username wrong!";
		exit;
	}
	if (!($password)) {
		exit("Password wrong!");
	}
	try {

		#register user by inserting the users info 
		$stat = $db->prepare("insert into users values(default,?,?,?)");
		$stat->execute(array($username, $password, $email));

		$id = $db->lastInsertId();
		echo "Congratulations! You are now registered. Your ID is: $id  ";
	} catch (PDOexception $ex) {
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>" . $ex->getMessage() . "</em>";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Petopia</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" href="CSS/style.css" />
</head>

<body>
	
</body>

</html>