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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      font-family: 'Tajawal', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
      width: 380px;
      padding: 2rem;
      transition: all 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .login-card h4 {
      font-weight: 700;
      color: #333;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px 12px;
      border: 1px solid #ddd;
      transition: 0.3s ease;
    }

    .form-control:focus {
      border-color: #2575fc;
      box-shadow: 0 0 6px rgba(37, 117, 252, 0.4);
    }

    .btn-primary {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      border: none;
      border-radius: 10px;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 117, 252, 0.4);
    }

    .error-msg {
      background: #f8d7da;
      color: #842029;
      padding: 10px;
      border-radius: 8px;
      font-size: 0.9rem;
      text-align: center;
      margin-bottom: 15px;
    }

    .logo {
      text-align: center;
      margin-bottom: 1rem;
    }

    .logo i {
      font-size: 2.5rem;
      color: #2575fc;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <div class="logo">
      <i class="fa-solid fa-lock"></i>
    </div>
    <h4 class="text-center mb-4">تسجيل الدخول</h4>

    <?php if ($error): ?>
      <div class="error-msg"><?php echo $error; ?></div>
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
</body>
</html>
