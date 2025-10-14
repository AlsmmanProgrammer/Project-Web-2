<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $location = trim($_POST["location"]);
    $category = trim($_POST["category"]);
    $event_date = $_POST["event_date"];

    $upload_dir = "../assets/img/events/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image = "";
    if (!empty($_FILES["image"]["name"])) {
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            $image = $image_name;
        }
    }

    $sql = "INSERT INTO events (title, description, location, category, event_date, image)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $title, $description, $location, $category, $event_date, $image);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>إضافة فعالية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h3 class="mb-4 text-center">إضافة فعالية جديدة</h3>

        <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">الموقع</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">التصنيف</label>
                <input type="text" name="category" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ الفعالية</label>
                <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الصورة</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button class="btn btn-primary w-100">حفظ</button>
        </form>
    </div>

</body>
</html>
