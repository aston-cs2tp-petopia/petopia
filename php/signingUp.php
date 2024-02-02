<?php
	require_once("php/connectdb.php");

	$fName = isset($_POST['fName']) ? $_POST['fName'] : false;
	$lName = isset($_POST['lName']) ? $_POST['lName'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$pNumber = isset($_POST['pNumber']) ? $_POST['pNumber'] : false;
	$username = isset($_POST['username']) ? $_POST['username'] : false;
	$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;

	if (!($email)) {
		exit("Email wrong!");
	}
	if (!($username)) {
		exit("Username wrong!");
	}
	if (!($password)) {
		exit("Password wrong!");
	}

	try {
		#prepare the following sql checking if the email exists
		$stat = $db->prepare('SELECT Contact_Email FROM Customer WHERE Contact_Email = ?');
		$stat = $stat->execute(array($_POST[$email])); #execute the previous statement including the email given by the user. store in stat

		#if the email exists in the db, then tell the user and exit signingUP
		if ($stat == $email){
			// header("Location:login.php");
			exit("Email already exists");
		}else{ #if the email is new, then create account
			$stat = $db->prepare("insert into customer values(default,?,?,?,?,?,?)");
			$stat->execute(array($fName, $lName, $email, $pNumber, $username, $password));
			echo "$fName, $lName, $email, $pNumber, $username, $password";

			require_once("php/loggingIn.php"); #log the user into their account
		}
	} catch (PDOexception $ex) { #if there is an issue with creating $db
		echo "Sorry, a database error occurred! <br>"; #display this message
		echo "Error details: <em>" . $ex->getMessage() . "</em>"; #display the details of the error
	}
?>