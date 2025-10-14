<?php
include "config/lang_config.php";
include "database/db.php";

$toggle_lang = $lang_code === 'ar' ? 'en' : 'ar';
$current_path = strtok($_SERVER["REQUEST_URI"], '?');
$query = $_GET;
$query['lang'] = $toggle_lang;
$lang_link = $current_path . '?' . http_build_query($query);

$today = date('Y-m-d');

$sql = "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

$cat_sql = "SELECT DISTINCT category FROM events";
$cat_result = $conn->query($cat_sql);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang_code); ?>" dir="<?php echo $lang_code == 'ar' ? 'rtl' : 'ltr'; ?>">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($lang['nav_events']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">

  <style>
    .event-hero {
      background: linear-gradient(120deg, rgba(0, 123, 255, 0.12), rgba(255, 193, 7, 0.08));
      padding: 80px 0 60px;
      text-align: center;
    }

    .filter-btn.active {
      background-color: var(--bs-secondary);
      color: white;
    }
  </style>
</head>

<body class="light-mode">

  <?php include "includes/navbar.php"; ?>


  <section class="event-hero">
    <div class="container">
      <h1 class="fw-bold mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['nav_events']); ?></h1>
      <p class="lead" data-aos="fade-up" data-aos-delay="100">
        <?php echo ($lang_code == 'ar') ? 'استكشف جميع الفعاليات القادمة في مدينتك' : 'Explore all upcoming events in your city'; ?>
      </p>
    </div>
  </section>

  <div class="container my-4 text-center" data-aos="fade-up">
    <div class="d-flex flex-wrap justify-content-center gap-2">
      <button class="btn btn-outline-secondary btn-sm filter-btn active" data-cat="all"><?php echo ($lang_code == 'ar') ? 'الكل' : 'All'; ?></button>
      <?php if ($cat_result && $cat_result->num_rows > 0): while ($cat = $cat_result->fetch_assoc()): ?>
          <button class="btn btn-outline-secondary btn-sm filter-btn" data-cat="<?php echo htmlspecialchars($cat['category']); ?>">
            <?php echo htmlspecialchars($cat['category']); ?>
          </button>
      <?php endwhile;
      endif; ?>
    </div>
  </div>

  <div class="container my-5">
    <div class="row" id="eventsGrid">
      <?php if ($result && $result->num_rows > 0): while ($e = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4 event-card" data-category="<?php echo htmlspecialchars($e['category']); ?>" data-aos="fade-up">
            <div class="card h-100 card-hover shadow-sm">
              <img src="assets/img/events/<?php echo htmlspecialchars($e['image']); ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-2"><?php echo htmlspecialchars($e['title']); ?></h5>
                <p class="text-muted small mb-1">
                  <i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($e['event_date']); ?>
                  &nbsp; <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($e['location']); ?>
                </p>
                <p class="flex-grow-1 small mt-2"><?php echo htmlspecialchars(mb_substr($e['description'], 0, 100)); ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="event.php?id=<?php echo (int)$e['id']; ?>" class="btn btn-sm btn-secondary">
                    <?php echo ($lang_code == 'ar') ? 'تفاصيل' : 'Details'; ?>
                  </a>
                  <span class="badge bg-secondary"><?php echo htmlspecialchars($e['category']); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile;
      else: ?>
        <p class="text-muted text-center"><?php echo htmlspecialchars($lang['no_events']); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <?php include "includes/footer.php"; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>