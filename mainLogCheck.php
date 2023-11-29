<!--
    Use as an external file
    Need to replace some of the comments.
    Rename the file so its less confusing.
-->

<?php
//if form submitted
session_start();
$b=False;
if (isset($_SESSION['username'])) {
    $b=True;
}

if (isset($_POST['submitted'])) {
    if (!isset($_POST['username'], $_POST['password'])) {
        // Could not get the data that should have been sent.
        exit('Please fill both the username and password fields!');
    }
    // connect to DB
    require_once("connectdb.php");
    try {
        //Query DB to find matching username/password
        //using prepare/bindparameter to prevent SQL injection.
        $stat = $db->prepare('SELECT password FROM users WHERE username = ?');
        $stat->execute(array($_POST['username']));

        // fetch the results row and check 
        if ($stat->rowCount() > 0) {  // matching username
            $row = $stat->fetch();

            if (password_verify($_POST['password'], $row['password'])) { //matching password

                //recording the user session variable and go to the projects page
                session_start();
                $_SESSION["username"] = $_POST['username'];
                // echo $_SESSION["username"];
                // echo $_POST["username"];
                header("Location:project.php");
                exit();
            } else {
                echo "<p style='color:red'>Error logging in, password does not match </p>";
            }
        } else {
            //else display an error
            echo "<p style='color:red'>Error logging in, Username not found </p>";
        }
    } catch (PDOException $ex) {
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    }
}