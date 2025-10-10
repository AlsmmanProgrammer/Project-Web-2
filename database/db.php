<?php
$host = "localhost";
$user = "root";
$password = "";
$db_name   = "bwp501_f24_homework";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
