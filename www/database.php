<?php

$host = "mysql";
$dbname = "bd_utilizadores";
$username = "root";
$password = "rootpass";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;