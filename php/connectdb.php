<?php

$db_host = 'cs2410-web01pvm.aston.ac.uk';
$db_name = 'u-210075069_petopiadb';
$username = 'root';
$password = 'nPcCncViPrL7oBX';

try{
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
    echo '$db';
} catch(PDOException $ex){
    echo("Failed to connect to the database.<br>");
    echo($ex->getMessage());
}

?>