<!--
    comment more
    Use as an external file
-->

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
		exit("password wrong!");
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

<div id="">
			<h2>Register</h2>
			<form method="post" action="signup.php">
				Email: <input type="email" name="email" /><br>
				Username: <input type="text" name="username" /><br>
				Password: <input type="password" name="password" /><br><br>

				<input type="submit" value="Register" />
				<input type="reset" value="clear" />
				<input type="hidden" name="submitted" value="true" />
			</form>
		</div>