<?php
    $username = $_SESSION['username'];

    $query=$db->prepare("SELECT `Is_Admin` FROM `customer` WHERE `Username` = '$username';");

    $query->execute();
    
    $result=$query->fetch(PDO::FETCH_ASSOC);
    $isAdminCheck = False;
    if ($result['Is_Admin']==2){
        $isAdminCheck = True;
        return true;
    } else {
        return false;
    }
?>