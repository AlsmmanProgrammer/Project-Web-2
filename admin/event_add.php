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
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

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
  <title>إضافة فعالية جديدة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/dash.css" rel="stylesheet">

</head>

<body>

  <!-- Sidebar -->
  <?php include "../includes/sidebar.php"; ?>


  <!-- Toggle Button (mobile) -->
  <button class="toggle-btn d-lg-none" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- Main Content -->
  <div class="main">

    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb bg-white p-3 rounded-3 shadow-sm">
        <li class="breadcrumb-item">
          <a href="index.php" class="text-decoration-none text-primary">
            <i class="fa-solid fa-house me-1"></i> لوحة التحكم
          </a>
          <span class="text-muted mx-1">/</span>
        </li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
          الفعاليات
        </li>
        <li class="active text-dark fw-semibold" aria-current="page">
          إضافة فعالية
        </li>
      </ol>
    </nav>

    <div class="form-container">
      <h3><i class="fa-solid fa-plus-circle text-primary"></i> إضافة فعالية جديدة</h3>

      <form method="post" enctype="multipart/form-data">
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

        <button type="submit" class="btn-gradient mt-2">
          <i class="fa-solid fa-save me-2"></i> حفظ الفعالية
        </button>
      </form>
    </div>
  </div>


</body>

</html>