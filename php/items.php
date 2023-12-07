<?php
session_start();
$b=False;
if (isset($_SESSION['username'])) {
    $b=True;
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

            <?php
                if ($b==true) {
                    echo '<a href="signout.php"><Button type="button">Sign out</Button></a>';
                }else{
                    echo '<form id="Form" name="signInForm" action="index.php" method="post">
                            <label>Username</label>
                            <input type="text" name="username" size="15" maxlength="25" />
                            <label>Password:</label>
                            <input type="password" name="password" size="15" maxlength="25" />

                            <input type="submit" value="Login" />
                            <input type="hidden" name="submitted" value="TRUE" />
                        </form>';
                }       
            ?>

        </header>

        <section id="textholder1">
            <?php
            require_once('connectdb.php');
            try {
                $query = "select * from  projects ";
                //run  the query
                $rows =  $db->query($query);

                //step 3: display the course list in a table 	
                if ($rows && $rows->rowCount() > 0) {

            ?>
                    <table cellspacing="10" cellpadding="15" id="projectTable">
                        <tr>
                            <th align='left'><b>project title</b></th>
                            <th align='left'><b>start date</b></th>
                            <th align='left'><b>description</b></th>
                        </tr>
                <?php
                    foreach ($rows as $row) {
                        echo  "<td align='left'>" . $row['title'] . "</td>";
                        echo "<td align='left'>" . $row['start_date'] . "</td>";
                        echo "<td align='left'>" . $row['description'] . "</td>";
                        // $titleTemp='title';
                        $pidTemp=$row['pid'];
                        echo '<td align="left"><a href="projectdetails.php?pid=' . $pidTemp . '"><Button type="button">Further Details</Button></a></td></tr>';
                    }
                    echo  '</table>';
                } else {
                    echo  "<p>No  course in the list.</p>\n"; //no match found
                }
            } catch (PDOexception $ex) {
                echo "Sorry, a database error occurred! <br>";
                echo "Error details: <em>" . $ex->getMessage() . "</em>";
            }
                ?>
        </section>

        <br>

        <div id="buttons">
            <a href="projectcreate.php">
                <?php
                    if ($b==true) {
                        echo '<Button type="button">Add Project</Button>';
                    }               
                ?>
            </a>
        </div>
    </div>
</body>

</html>