<?php
	require_once("php/connectdb.php");

	$fName = isset($_POST['fName']) ? $_POST['fName'] : false;
	$lName = isset($_POST['lName']) ? $_POST['lName'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$pNumber = isset($_POST['pNumber']) ? $_POST['pNumber'] : false;
	$username = isset($_POST['username']) ? $_POST['username'] : false;
	$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;

	

	try {
		#prepare the following sql checking if the email exists
		$stat = $db->prepare('SELECT Contact_Email FROM customer WHERE Contact_Email = ?');
		$stat = $stat->execute(array($_POST['email'])); #execute the previous statement including the email given by the user. store in stat

			$stat = $db->prepare("insert into customer values(default,?,?,?,?,?,?)");
			$stat->execute(array($fName, $lName, $email, $pNumber, $username, $password));
			echo "$fName, $lName, $email, $pNumber, $username, $password";

			require_once("php/loggingIn.php"); #log the user into their account
		
	} catch (PDOexception $ex) { #if there is an issue with creating $db
		// echo "Sorry, a database error occurred! <br>"; #display this message
		// echo "Error details: <em>" . $ex->getMessage() . "</em>"; #display the details of the error

		#create a string variable to send to the front end on login.php
		#or
		#call the div inside of this script and style it 
	}
?>