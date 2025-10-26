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
  $image = $event["image"];

  if (!empty($_FILES["image"]["name"])) {
    $upload_dir = "../assets/img/events/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $new_image = time() . "_" . basename($_FILES["image"]["name"]);
    $target_path = $upload_dir . $new_image;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/dash.css" rel="stylesheet">
</head>

<body>

  <?php include "../includes/sidebar.php"; ?>

  <button class="toggle-btn d-lg-none" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>

  <div class="main">

    <!-- المسار -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb bg-white p-3 rounded-3 shadow-sm">
        <li class="breadcrumb-item">
          <a href="dashboard.php" class="text-decoration-none text-primary">
            <i class="fa-solid fa-house me-1"></i> لوحة التحكم
          </a>
          <span class="text-muted mx-1">›</span>
        </li>
        <li class="breadcrumb-item">
          <a href="dashboard.php" class="text-decoration-none text-primary">الفعاليات</a>
          <span class="text-muted mx-1">›</span>
        </li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">تعديل فعالية</li>
      </ol>
    </nav>

    <div class="row">
      <!-- العمود اليمين: الكاردين تحت بعض -->
      <div class="col-lg-4 mb-4 d-flex flex-column gap-3">

        <!-- كارد تعريف الصفحة -->
        <div class="info-card shadow-sm">
          <i class="fa-solid fa-pen-to-square"></i>
          <h4 class="mb-3">تعديل فعالية</h4>
          <p>
            يمكنك من خلال هذه الصفحة تعديل بيانات فعالية موجودة مسبقاً في النظام،
            بما في ذلك العنوان، الوصف، التصنيف، الصورة، وتاريخ الفعالية.
          </p>

        </div>

      

      </div>

      <!-- العمود اليسار: الفورم -->
      <div class="col-lg-8 mb-4">
        <div class="form-container bg-white p-4 rounded-4 shadow-sm">
          <h5 class="fw-bold text-primary mb-3">
            <i class="fa-solid fa-pen me-2"></i> نموذج تعديل الفعالية
          </h5>

          <form method="post" enctype="multipart/form-data">
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
              <label class="form-label">الصورة الحالية</label>
              <div class="current-image mb-2">
                <?php if (!empty($event['image'])): ?>
                  <img src="../assets/img/events/<?php echo htmlspecialchars($event['image']); ?>" width="200" class="rounded shadow-sm">
                <?php else: ?>
                  <p class="text-muted small">لا توجد صورة حالياً</p>
                <?php endif; ?>
              </div>
              <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-gradient mt-2">
              <i class="fa-solid fa-save me-2"></i> حفظ التعديلات
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/dash.js"></script>
</body>

</html>