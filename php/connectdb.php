<?php


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