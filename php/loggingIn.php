<?php
    // Include the database connection file
    require_once("connectdb.php");

    try {
        // Check if both username and password are provided
        if (!isset($_POST['login-username'], $_POST['login-password'])) {
            // Throw an exception if either field is missing
            throw new Exception('Username and password are required.');
        }

        $stat = $db->prepare('SELECT Password FROM customer WHERE Username = ?');

        // Execute the prepared statement with the provided username
        $stat->execute(array($_POST['login-username']));

        // Check if the username exists in the database
        if ($stat->rowCount() > 0) {
            // Fetch the user's hashed password from the database
            $row = $stat->fetch();

            // Verify the provided password against the hashed password in the database
            if (password_verify($_POST['login-password'], $row['Password'])) {
                // Set the session username to indicate the user is logged in
                $_SESSION["username"] = $_POST['login-username'];

                // Redirect to the home page after successful login
                header("Location: ./index.php");
                exit();
            } else {
                // Display an error message if the password does not match
                $_SESSION['error_message'] = "Incorrect Password.";
                header("Location: login.php"); // Redirect to the customer form page

            }
        } else {
            // Display an error message if the username is not found
            // Display an error message if the password does not match
            $_SESSION['error_message'] = "Username does not exist.";
            header("Location: login.php"); // Redirect to the form page
        }
    } catch (PDOException $ex) {
        // Handle any PDO (database related) errors
        echo "Failed to connect to the database.<br>";
        echo "Error details: " . $ex->getMessage();
    } catch (Exception $ex) {
        // Handle other general exceptions
        echo "Error: " . $ex->getMessage();
    }
?>
