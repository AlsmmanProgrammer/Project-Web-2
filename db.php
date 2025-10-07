<?php
$host = "localhost";
$user = "root"; 
$password = "";
$db_name   = "bwp501_f24_homework";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>