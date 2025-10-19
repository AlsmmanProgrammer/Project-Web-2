<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// جلب التصنيفات
$categoriesResult = $conn->query("SELECT DISTINCT category FROM events ORDER BY category ASC");

// البحث والتصفية
$search = "";
$filterCategory = "";
$whereClauses = [];

if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $whereClauses[] = "(title LIKE '%$search%' OR location LIKE '%$search%')";
}

if (!empty($_GET['category'])) {
    $filterCategory = $conn->real_escape_string($_GET['category']);
    $whereClauses[] = "category = '$filterCategory'";
}

$sql = "SELECT * FROM events";
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY event_date DESC";

$result = $conn->query($sql);
?>
<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>لوحة التحكم - إدارة الفعاليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/dash.css" rel="stylesheet">
</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <button class="toggle-btn d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>

    <div class="main">
        <!-- المسار -->
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
            </ol>
        </nav>



        <div class="row">
            <!-- القسم الأيمن: العنوان والوصف -->
            <div class="col-lg-3 mb-4">
                <div class="p-4 bg-white shadow-sm rounded-4">
                    <h4 class="fw-bold text-primary"><i class="fa-solid fa-calendar-days me-2"></i> إدارة الفعاليات</h4>
                    <p class="text-muted mt-3">
                        يمكنك من خلال هذه الصفحة إدارة جميع الفعاليات، تعديلها أو حذفها، وكذلك البحث والتصفية بحسب التصنيف.
                    </p>
                    <a href="event_add.php" class="btn btn-gradient w-100 mt-3">
                        <i class="fa fa-plus"></i> إضافة فعالية جديدة
                    </a>
                </div>
            </div>

            <!-- القسم الأيسر: الجدول والفلترة -->
            <div class="col-lg-9">
                <div class="mb-3">
                    <form method="get" class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="ابحث عن فعالية..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="">جميع التصنيفات</option>
                                <?php while ($cat = $categoriesResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php if ($filterCategory == $cat['category']) echo "selected"; ?>>
                                        <?php echo htmlspecialchars($cat['category']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-gradient w-100 "><i class="fa fa-search"></i> بحث</button>
                        </div>
                    </form>
                </div>

                <div class="table-container">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-primary">
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
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                        <td class="action-btns">
                                            <a href="event_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="event_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-muted">لا توجد فعاليات لعرضها.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        © <?php echo date('Y'); ?> جميع الحقوق محفوظة | نظام إدارة الفعاليات
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>

</body>

</html>