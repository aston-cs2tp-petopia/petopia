<?php
    require_once("php/connectdb.php");
    try {
        // Query DB to find matching username/password
        // using prepare/bindparameter to prevent SQL injection.
        $stat = $db->prepare('SELECT password FROM customers WHERE username = ?');
        $stat->execute(array($_POST['username']));

        // fetch the results row and check 
        if ($stat->rowCount() > 0) {  // finding where the row with the username
            $row = $stat->fetch(); // fetch the rows contents

            // if the field password matches the password in the database row
            if (password_verify($_POST['password'], $row['password'])||$_POST['password'] == $row['password']) { 
                //record the user session variable and go to the home page for now
                session_start();
                $_SESSION["username"] = $_POST['username']; //declares the sessions user
                header("Location:index.php"); // sends user to the home page
                exit();
            } else {
                // else display an error (password)
                echo "<p style='color:red'>Error logging in, password does not match </p>";
                echo $_POST['password'], $row['password'];
            }
        } else {
            // else display an error (username)
            echo "<p style='color:red'>Error logging in, Username not found </p>";
        }
    } catch (PDOException $ex) {
        // error (database connection)
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    }
?>