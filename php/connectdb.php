<?php

// $db_host = 'cs2410-web01pvm.aston.ac.uk';
// $db_name = 'u_210075069_petopiadb';
// $username = 'u-210075069';
// $password = 'VY7WdmJnGlj/m02g';

$db_host = 'localhost';
$db_name = 'petopia2';
$username = 'root';
$password = '';

try{
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
} catch(PDOException $ex){
    echo("Failed to connect to the database.<br>");
    echo($ex->getMessage());
}

?>