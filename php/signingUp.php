<?php
	require_once("php/connectdb.php");

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
		$stat = $db->prepare("insert into customers values(default,?,?,?)");
		$stat->execute(array($username, $password, $email));
		echo "$username, $password, $email";

		require_once("php/loggingIn.php");
	} catch (PDOexception $ex) {
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>" . $ex->getMessage() . "</em>";
	}
?>