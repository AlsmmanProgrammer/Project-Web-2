<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// جلب جميع التصنيفات الفريدة
$categoriesResult = $conn->query("SELECT DISTINCT category FROM events ORDER BY category ASC");

$search = "";
$filterCategory = "";
$whereClauses = [];

// معالجة البحث النصي
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $conn->real_escape_string($_GET['search']);
    $whereClauses[] = "(title LIKE '%$search%' OR location LIKE '%$search%')";
}

// معالجة فلترة التصنيف
if (isset($_GET['category']) && $_GET['category'] !== "") {
    $filterCategory = $conn->real_escape_string($_GET['category']);
    $whereClauses[] = "category = '$filterCategory'";
}

// بناء الاستعلام
$sql = "SELECT * FROM events";
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY event_date DESC";

$result = $conn->query($sql);

// تقسيم أحدث 3 فعاليات والباقي
$latestEvents = [];
$otherEvents = [];
if ($result->num_rows > 0) {
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i < 3) {
            $latestEvents[] = $row;
        } else {
            $otherEvents[] = $row;
        }
        $i++;
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>لوحة التحكم - إدارة الفعاليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #f5f7fb; display: flex; flex-direction: column; min-height: 100vh; margin:0; }
        .sidebar { width: 250px; background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; display: flex; flex-direction: column; padding: 25px 20px; position: fixed; top:0; bottom:0; right:0; z-index:1000; transition: 0.3s; }
        .sidebar h4 { font-weight:bold; margin-bottom:40px; text-align:center; }
        .sidebar a { color:white; text-decoration:none; padding:12px 15px; border-radius:8px; display:block; margin-bottom:10px; transition:0.3s; font-weight:500; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); transform:translateX(-4px); }
        .sidebar .logout { margin-top:auto; background: rgba(255,255,255,0.15); }

        .main { flex:1; margin-right:250px; padding:40px; transition: margin-right 0.3s; }

        .dashboard-header { text-align:center; margin-bottom:30px; }
        .dashboard-header h3 { font-weight:bold; color:#333; }

        .filters { max-width:600px; margin:0 auto 30px; display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }

        .cards-container { display:grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap:20px; margin-bottom:40px; }
        .event-card { background:#fff; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.08); overflow:hidden; transition: transform 0.3s; display:flex; flex-direction:column; }
        .event-card:hover { transform:translateY(-5px); }
        .event-card img { width:100%; height:180px; object-fit:cover; }
        .event-card .card-body { padding:15px; flex:1; }
        .event-card h5 { font-weight:bold; color:#2575fc; }
        .event-card p { margin-bottom:8px; color:#555; }
        .event-card .badge { font-size:0.85rem; }
        .action-btns a { margin:0 4px; transition:0.3s ease; }
        .action-btns a:hover { transform:scale(1.1); }

        .table-container { background:#fff; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.08); padding:20px; margin-bottom:40px; }
        table thead { background:#2575fc; color:#fff; }
        table th, table td { vertical-align:middle; }

        .footer { text-align:center; padding:15px; color:#666; font-size:0.9rem; background:#f5f7fb; margin-top:auto; }

        @media (max-width:992px) {
            .sidebar { right:-250px; }
            .sidebar.active { right:0; }
            .main { margin-right:0; }
            .toggle-btn { position:fixed; top:20px; right:20px; background:#2575fc; color:#fff; border:none; border-radius:8px; padding:8px 12px; z-index:1100; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h4><i class="fa-solid fa-gear"></i> لوحة التحكم</h4>
    <a href="dashboard.php" class="active"><i class="fa-solid fa-calendar-days me-2"></i> الفعاليات</a>
    <a href="event_add.php"><i class="fa-solid fa-plus me-2"></i> إضافة فعالية</a>
    <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket me-2"></i> تسجيل الخروج</a>
</div>

<button class="toggle-btn d-lg-none" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>

<div class="main">
    <div class="dashboard-header">
        <h3><i class="fa-solid fa-calendar-days text-primary"></i> جميع الفعاليات</h3>
        <p class="text-muted">إدارة وتعديل جميع الفعاليات بسهولة</p>
    </div>

    <!-- البحث وفلترة التصنيف -->
    <form method="get" class="filters">
        <input type="text" name="search" class="form-control" placeholder="ابحث عن فعالية..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="category" class="form-select">
            <option value="">جميع التصنيفات</option>
            <?php while ($cat = $categoriesResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php if ($filterCategory == $cat['category']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($cat['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button class="btn btn-primary"><i class="fa fa-search"></i> تطبيق</button>
    </form>

    <!-- أحدث 3 فعاليات -->
    <div class="cards-container">
        <?php foreach ($latestEvents as $event): ?>
            <?php
            $imagePath = "../assets/img/events/" . $event['image'];
            $imgSrc = (!empty($event['image']) && file_exists($imagePath)) ? $imagePath : "https://via.placeholder.com/400x180?text=No+Image";
            ?>
            <div class="event-card">
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p><i class="fa fa-calendar-days me-1"></i> <?php echo htmlspecialchars($event['event_date']); ?></p>
                    <p><i class="fa fa-location-dot me-1"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                    <p><span class="badge bg-secondary"><?php echo htmlspecialchars($event['category']); ?></span></p>
                    <div class="action-btns mt-2">
                        <a href="event_edit.php?id=<?php echo $event['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> تعديل</a>
                        <a href="event_delete.php?id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');"><i class="fa fa-trash"></i> حذف</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- باقي الفعاليات -->
    <?php if (!empty($otherEvents)): ?>
        <div class="table-container">
            <table class="table table-bordered align-middle text-center">
                <thead>
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
                    <?php foreach ($otherEvents as $row): ?>
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="footer">
    © <?php echo date('Y'); ?> جميع الحقوق محفوظة | نظام إدارة الفعاليات
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }
</script>
</body>
</html>
