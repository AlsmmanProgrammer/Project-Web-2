<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $stmt = $conn->prepare("SELECT image FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if ($event && !empty($event["image"])) {
        $imagePath = "../assets/img/events/" . $event["image"];
        if (file_exists($imagePath)) {
            unlink($imagePath); 
        }
    }

    $delete_sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit;
?>
