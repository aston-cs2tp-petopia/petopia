<?php

$db_host = '134.151.21.205';
$db_name = 'u_210075069_petopiadb';
$username = 'u-210075069';
$password = 'nPcCNcViPrL7oBX';

try{
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
} catch(PDOException $ex){
    echo("Failed to connect to the database.<br>");
    echo($ex->getMessage());
}

?>