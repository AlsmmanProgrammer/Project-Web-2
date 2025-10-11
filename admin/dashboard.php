<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">لوحة التحكم</span>
            <div class="d-flex">
                <a href="event_add.php" class="btn btn-success btn-sm me-2"><i class="fa fa-plus"></i> إضافة فعالية</a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h3 class="mb-4">جميع الفعاليات</h3>

        <div class="table-responsive bg-white shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>التاريخ</th>
                        <th>الموقع</th>
                        <th>التصنيف</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>
                                <a href="events_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                <a href="events_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>