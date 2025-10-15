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
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background: #f5f7fb;
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ===== Sidebar ===== */
    .sidebar {
      width: 250px;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      display: flex;
      flex-direction: column;
      padding: 25px 20px;
      position: fixed;
      top: 0;
      bottom: 0;
      right: 0;
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .sidebar h4 {
      font-weight: bold;
      margin-bottom: 40px;
      text-align: center;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 8px;
      display: block;
      margin-bottom: 10px;
      transition: 0.3s;
      font-weight: 500;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(-4px);
    }

    .sidebar .logout {
      margin-top: auto;
      background: rgba(255, 255, 255, 0.15);
    }

    /* ===== Main Content ===== */
    .main {
      flex: 1;
      margin-right: 250px;
      padding: 40px;
      transition: margin-right 0.3s ease;
    }

    .form-container {
      max-width: 700px;
      background: #fff;
      border-radius: 14px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      margin: auto;
    }

    .form-container h3 {
      text-align: center;
      font-weight: bold;
      color: #333;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 10px;
      transition: box-shadow 0.3s ease;
    }

    .form-control:focus {
      border-color: #2575fc;
      box-shadow: 0 0 6px rgba(37, 117, 252, 0.3);
    }

    .btn-gradient {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      border: none;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      padding: 10px;
      width: 100%;
      transition: all 0.3s ease;
    }

    .btn-gradient:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }

    /* ===== Responsive Sidebar ===== */
    @media (max-width: 992px) {
      .sidebar {
        right: -250px;
      }

      .sidebar.active {
        right: 0;
      }

      .main {
        margin-right: 0;
      }

      .toggle-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #2575fc;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        z-index: 1100;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h4><i class="fa-solid fa-gear"></i> لوحة التحكم</h4>
    <a href="dashboard.php"><i class="fa-solid fa-calendar-days me-2"></i> الفعاليات</a>
    <a href="event_add.php" class="active"><i class="fa-solid fa-plus me-2"></i> إضافة فعالية</a>
    <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket me-2"></i> تسجيل الخروج</a>
  </div>

  <!-- Toggle Button (mobile) -->
  <button class="toggle-btn d-lg-none" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>

  <!-- Main Content -->
  <div class="main">
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

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>

</body>
</html>
