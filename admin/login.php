<?php
session_start();
include "../database/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  $sql = "SELECT * FROM admins WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    if ($password === $admin["password"]) {
      $_SESSION["admin_id"] = $admin["id"];
      $_SESSION["admin_name"] = $admin["username"];
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "كلمة المرور غير صحيحة";
    }
  } else {
    $error = "اسم المستخدم غير موجود";
  }
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تسجيل الدخول - لوحة التحكم</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow-sm" style="width: 360px;">
    <h4 class="mb-3 text-center">تسجيل الدخول</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">اسم المستخدم</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">تسجيل الدخول</button>
    </form>
  </div>
</div>

</body>
</html>
