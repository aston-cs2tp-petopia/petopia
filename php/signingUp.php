<?php
    require_once("php/connectdb.php");
	require_once("validateSignup.php");

    try {
        $fName = isset($_POST['signup-firstname']) ? $_POST['signup-firstname'] : false;
        $lName = isset($_POST['signup-lastname']) ? $_POST['signup-lastname'] : false;
        $email = isset($_POST['signup-email']) ? $_POST['signup-email'] : false;
        $pNumber = isset($_POST['signup-number']) ? $_POST['signup-number'] : false;
        $username = isset($_POST['signup-username']) ? $_POST['signup-username'] : false;
        $password = isset($_POST['signup-password']) ? password_hash($_POST['signup-password'], PASSWORD_DEFAULT) : false;
		
		// Call the validation function
		$validationErrors = validateSignupData($fName, $lName, $email, $pNumber, $username, $password);

		// Check for validation errors
		if (!empty($validationErrors)) {
			throw new Exception(implode("<br>", $validationErrors));
		}

        $checkUser = $db->prepare("SELECT Username FROM customer WHERE Username = ?");
		$checkUser->execute(array($username));
		if ($checkUser->rowCount() > 0) {
			$_SESSION['error_message'] = "Username already exists. Please choose another one.";
			header("Location: ../login.php"); // Redirect to the form page
			exit;
		}

		// Check if the email already exists
		$checkEmail = $db->prepare("SELECT Contact_Email FROM customer WHERE Contact_Email = ?");
		$checkEmail->execute(array($email));
		if ($checkEmail->rowCount() > 0) {
			$_SESSION['error_message'] = "Email already exists. Please choose another one.";
			header("Location: ../login.php"); // Redirect to the form page
			exit;
		}

        // Insert new customer record
		$stat = $db->prepare("INSERT INTO customer (First_Name, Last_Name, Contact_Email, Phone_Number, Username, Password) VALUES (?, ?, ?, ?, ?, ?)");
		$stat->execute(array($fName, $lName, $email, $pNumber, $username, $password));


        // Log the user in and redirect
        $_SESSION["username"] = $username;
        header("Location:index.php");
        exit();

    } catch (PDOException $ex) {
		$_SESSION['error_message'] = "Failed to connect to the database. Error details: " . $ex->getMessage();
		header("Location: ..login.php"); // Redirect to the form page
		exit;
	} catch (Exception $ex) {
		$_SESSION['error_message'] = $ex->getMessage();
		header("Location: ..login.php"); // Redirect to the form page
		exit;
	}
?>
