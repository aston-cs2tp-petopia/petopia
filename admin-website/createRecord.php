<?php
    session_start();
    if (!isset($_SESSION['username'])){
            header("Location: index.php");
            exit();
        }

    if (isset($_POST['submitted'])) {

        require_once('connectdb.php');

        $title = isset($_POST['title']) ? $_POST['title'] : false;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : false;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : false;
        $phase = isset($_POST['phase']) ? $_POST['phase'] : false;
        $desc = isset($_POST['desc']) ? $_POST['desc'] : false;

        $sql = "select * from users where username like " . "'" . $_SESSION["username"] . "'";
        $result = $db->query($sql);
        $user = $result->fetch(PDO::FETCH_ASSOC);
        $uid = $user['uid'];

        // echo $uid;

        if (!($title)) {
            echo "no title";
            exit;
        }
        if (!($start_date)) {
            echo "no start date";
            exit;
        }
        if (!($end_date)) {
            exit("no end date");
        }
        if (!($phase)) {
            exit("no phase");
        }
        if (!($desc)) {
            exit("no description");
        }
        try {

            $stat = $db->prepare("insert into projects values(default,?,?,?,?,?,?)");
            $stat->execute(array($title, $start_date, $end_date, $phase, $desc, $uid));

            $id = $db->lastInsertId();
            // echo "Project Created. Project ID is: $id  ";
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
    <title>The Thought Cabinet</title>
    <link href="css/style.css" rel="stylesheet" type="text/css" href="CSS/style.css" />
</head>

<body>
    <div id="contentHolder">
        <header id="main-header">
            <h1 id="TCheading"><a href="index.php" open="onClick"> The Thought Cabinet</a></h1>

            <a href="signout.php">
                <Button type="button">Sign out</Button>
            </a>
        </header>

        <div id="textholder1">
            <h2>Project Creator</h2>
            <form method="post" action="projectcreate.php">
                Project Title: <input type="text" name="title" /><br>
                Start date: <input type="date" name="start_date" /><br>
                End date: <input type="date" name="end_date" /><br>
                Phase: <select name="phase">
                            <option value="Design">Design</option>
                            <option value="Development">Development</option>
                            <option value="Testing">Testing</option>
                            <option value="Complete">Complete</option>
                        </select><br>
                Description: 
                <textarea name="desc" cols="30" rows="10"></textarea><br><br>

                <input type="submit" value="Create" />
                <input type="reset" value="Clear" />
                <input type="hidden" name="submitted" value="true" />
            </form>
            <a href="project.php"><button>Back</button></a>
        </div>

    </div>
</body>

</html>