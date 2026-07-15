<?php

$host = "localhost";
$dbname = "edumanage";
$username = "root";
$password = "";

try {

    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Enable error reporting
    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );


} catch(PDOException $e){

    die("Database connection failed: " . $e->getMessage());

}

?>