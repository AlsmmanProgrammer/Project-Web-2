<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$categoriesResult = $conn->query("SELECT DISTINCT category FROM events ORDER BY category ASC");

$search = "";
$filterCategory = "";
$whereClauses = [];

if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $conn->real_escape_string($_GET['search']);
    $whereClauses[] = "(title LIKE '%$search%' OR location LIKE '%$search%')";
}

if (isset($_GET['category']) && $_GET['category'] !== "") {
    $filterCategory = $conn->real_escape_string($_GET['category']);
    $whereClauses[] = "category = '$filterCategory'";
}

$sql = "SELECT * FROM events";
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY event_date DESC";

$result = $conn->query($sql);

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

    <link href="../assets/css/dash.css" rel="stylesheet">
</head>
<body>



<?php include "../includes/sidebar.php"; ?>

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

</body>
</html>
