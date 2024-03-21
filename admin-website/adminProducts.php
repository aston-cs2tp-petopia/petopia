<?php
    session_start();
    require_once('../php/connectdb.php');

    $isAdmin=include('../php/isAdmin.php');
    // echo "$isAdmin"

    if(!$isAdmin || isset($_SESSION['username'])){
        header("Location: ../index.php");
        exit();
        echo'being redirected';
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--CSS-->
    <link rel="stylesheet" href="css/admin-dashboard.css">
</head>

<body>
    <!--Navigation-->
    <nav class="admin-nav">

    </nav>

    <section id="textholder1">
            <?php
                try {
                    $query = "select * from  product";
                    //run  the query
                    $rows =  $db->query($query);

                    //step 3: display the course list in a table 	
                    if ($rows && $rows->rowCount() > 0) {
            ?>
                    <table cellspacing="10" cellpadding="15" id="projectTable">
                        <tr>
                            <th align='left'><b>Image</b></th>
                            <th align='left'><b>Product ID</b></th>
                            <th align='left'><b>Name</b></th>
                            <th align='left'><b>Stock</b></th>
                        </tr>
                <?php
                    foreach ($rows as $row) {
                        echo  "<td align='left'>" .$row['Image'] . "</td>";
                        echo "<td align='left'>" . $row['Product_ID'] . "</td>";
                        echo "<td align='left'>" . $row['Name'] . "</td>";
                        echo "<td align='left'>" . $row['Num_In_Stock'] . "</td>";
                        $customerID=$row['Product_ID'];
                        echo '<td align="left"><a href="editDetails.php?pid=' . $customerID . '"><Button type="button">Edit Details</Button></a></td></tr>';
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

        <a href="editDetails.php">
            <Button type="button">Edit Infromation</Button>;
        </a>
</body>

</html>