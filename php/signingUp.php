<?php
	require_once("php/connectdb.php");

	$fName = isset($_POST['fName']) ? $_POST['fName'] : false;
	$lName = isset($_POST['lName']) ? $_POST['lName'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$pNumber = isset($_POST['pNumber']) ? $_POST['pNumber'] : false;
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
		$stat = $db->prepare("insert into customer values(default,?,?,?,?,?,?)");
		$stat->execute(array($fName, $lName, $email, $pNumber, $username, $password));
		echo "$fName, $lName, $email, $pNumber, $username, $password";

		require_once("php/loggingIn.php");
	} catch (PDOexception $ex) {
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>" . $ex->getMessage() . "</em>";
	}
?>