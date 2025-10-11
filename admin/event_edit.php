<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"] ?? 0;
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    die("⚠️ الفعالية غير موجودة");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $category = $_POST["category"];
    $event_date = $_POST["event_date"];
    $image = $event["image"]; // الصورة القديمة كافتراضية

    // تحديث الصورة إن تم رفع واحدة جديدة
    if (!empty($_FILES["image"]["name"])) {
        $upload_dir = "../assets/img/events/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $new_image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_path = $upload_dir . $new_image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            // حذف القديمة
            if (!empty($image) && file_exists($upload_dir . $image)) {
                unlink($upload_dir . $image);
            }
            $image = $new_image;
        }
    }

    $sql = "UPDATE events SET title=?, description=?, location=?, category=?, event_date=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $title, $description, $location, $category, $event_date, $image, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تعديل فعالية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h3 class="mb-4 text-center">تعديل الفعالية</h3>

    <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">الموقع</label>
            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">التصنيف</label>
            <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($event['category']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">تاريخ الفعالية</label>
            <input type="date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الصورة الحالية</label><br>
            <?php if (!empty($event['image'])): ?>
                <img src="../assets/img/events/<?php echo htmlspecialchars($event['image']); ?>" width="160" class="rounded mb-2">
            <?php else: ?>
                <p class="text-muted small">لا توجد صورة</p>
            <?php endif; ?>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary w-100">حفظ التعديلات</button>
    </form>
</div>

</body>
</html>
