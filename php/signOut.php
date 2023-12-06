<?php
    session_start();
    session_destroy();

    if(isset($_SESSION['username'])){
        header("Location:index.html");
        exit();
    }
?>