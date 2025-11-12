<?php
$host = "localhost";
$user = "root";       // default XAMPP MySQL user
$pass = "";           // default XAMPP MySQL password is empty
$db   = "crackers_db"; // make sure this DB exists in root

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
