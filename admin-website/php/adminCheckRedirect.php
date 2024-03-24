<?php
    if(!$isAdmin || !isset($_SESSION['username'])){
        header("Location: ../index.php");
        exit();
    }
?>