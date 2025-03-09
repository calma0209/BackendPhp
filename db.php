<?php
$servername = "bd-proyecto.c34uuwe8uska.eu-north-1.rds.amazonaws.com";
$username = "root";
$password = "kimochi2024";
$db = "db_proyecto";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
