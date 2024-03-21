<?php
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $query=$db->prepare("SELECT `Is_Admin` FROM `customer` WHERE `Username` = '$username';");

    $query->execute();
    
    $result=$query->fetch(PDO::FETCH_ASSOC);

    if ($result['Is_Admin']==2){
        echo $result['Is_Admin'];
        echo"here";
        return true;
    } else {
        return false;
    }
}
?>