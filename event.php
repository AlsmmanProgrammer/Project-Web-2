<?php
include "config/lang_config.php";
include "database/db.php";

// التحقق من وجود ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: events.php");
  exit;
}

$id = (int)$_GET['id'];

// جلب تفاصيل الحدث
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  header("Location: events.php");
  exit;
}

$event = $result->fetch_assoc();

// جلب فعاليات مشابهة بنفس التصنيف
$related_sql = "SELECT * FROM events WHERE category = ? AND id != ? LIMIT 3";
$stmt2 = $conn->prepare($related_sql);
$stmt2->bind_param("si", $event['category'], $id);
$stmt2->execute();
$related_result = $stmt2->get_result();

// إعداد رابط اللغة
$current_path = strtok($_SERVER["REQUEST_URI"], '?');
$query = $_GET;
$query['lang'] = ($lang_code === 'ar') ? 'en' : 'ar';
$lang_link = $current_path . '?' . http_build_query($query);
?>

<!doctype html>
<html lang="<?php echo htmlspecialchars($lang_code); ?>" dir="<?php echo $lang_code == 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($event['title']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">

  <style>
    .event-details-hero {
      background: linear-gradient(120deg, rgba(0, 123, 255, 0.12), rgba(255, 193, 7, 0.08));
      padding: 80px 0 60px;
      text-align: center;
    }

    .event-image {
      border-radius: 14px;
      max-height: 420px;
      object-fit: cover;
      width: 100%;
    }

    .related-card img {
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>

<body class="light-mode">

  <?php include "includes/navbar.php"; ?>

  <!-- HERO -->
  <section class="event-details-hero">
    <div class="container">
      <h1 class="fw-bold mb-3" data-aos="fade-up"><?php echo htmlspecialchars($event['title']); ?></h1>
      <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
        <i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($event['event_date']); ?> •
        <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['location']); ?>
      </p>
    </div>
  </section>

  <!-- DETAILS -->
  <div class="container my-5" data-aos="fade-up">
    <div class="row align-items-start gy-4">
      <div class="col-lg-6">
        <img src="assets/img/events/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image shadow-sm">
      </div>
      <div class="col-lg-6">
        <h3 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['details']); ?></h3>
        <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <p><span class="fw-bold"><?php echo htmlspecialchars($lang['category']); ?>:</span> <?php echo htmlspecialchars($event['category']); ?></p>
        <a href="events.php" class="btn btn-secondary mt-3">
          <i class="fa-solid fa-arrow-right"></i>
          <?php echo ($lang_code == 'ar') ? 'العودة إلى الفعاليات' : 'Back to Events'; ?>
        </a>
      </div>
    </div>
  </div>

  <!-- RELATED EVENTS -->
  <?php if ($related_result && $related_result->num_rows > 0): ?>
    <div class="container my-5">
      <h4 class="mb-4 text-center" data-aos="fade-up">
        <?php echo ($lang_code == 'ar') ? 'فعاليات مشابهة' : 'Related Events'; ?>
      </h4>
      <div class="row justify-content-center">
        <?php while ($r = $related_result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4 related-card" data-aos="fade-up">
            <div class="card h-100 card-hover">
              <img src="assets/img/events/<?php echo htmlspecialchars($r['image']); ?>" class="card-img-top" alt="">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($r['title']); ?></h5>
                <p class="text-muted small"><?php echo htmlspecialchars($r['event_date']); ?> • <?php echo htmlspecialchars($r['location']); ?></p>
                <a href="event.php?id=<?php echo (int)$r['id']; ?>" class="btn btn-outline-secondary btn-sm"><?php echo ($lang_code == 'ar') ? 'عرض التفاصيل' : 'View Details'; ?></a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php include "includes/footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
