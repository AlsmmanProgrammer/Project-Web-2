<?php
// events.php
include "config/lang_config.php";
include "database/db.php";

// تحديد اللغة الحالية
$toggle_lang = $lang_code === 'ar' ? 'en' : 'ar';
$current_path = strtok($_SERVER["REQUEST_URI"], '?');
$query = $_GET;
$query['lang'] = $toggle_lang;
$lang_link = $current_path . '?' . http_build_query($query);

// اليوم الحالي
$today = date('Y-m-d');

// جلب جميع الفعاليات القادمة
$sql = "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

// جلب التصنيفات (للفلترة)
$cat_sql = "SELECT DISTINCT category FROM events";
$cat_result = $conn->query($cat_sql);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang_code); ?>" dir="<?php echo $lang_code == 'ar' ? 'rtl' : 'ltr'; ?>">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($lang['nav_events']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

  <!-- Bootstrap / Font Awesome / AOS -->
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

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="assets/img/logo.svg" alt="logo" style="height:45px; margin-inline-end:8px;">
      </a>

      <!-- Toggler for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu items -->
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
          <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php"><?php echo htmlspecialchars($lang['nav_home']); ?></a></li>
          <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>" href="events.php"><?php echo htmlspecialchars($lang['nav_events']); ?></a></li>
          <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php"><?php echo htmlspecialchars($lang['nav_about']); ?></a></li>
          <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php"><?php echo htmlspecialchars($lang['nav_contact']); ?></a></li>
        </ul>

        <!-- Right side buttons -->
        <div class="d-flex align-items-center gap-2 ms-lg-3">
          <!-- Language toggle -->
          <a id="langToggle" class="btn btn-outline-secondary btn-sm rounded-circle" href="<?php echo htmlspecialchars($lang_link); ?>" title="<?php echo htmlspecialchars($lang['lang']); ?>">
            <i id="langIcon" class="fa-solid fa-globe"></i>
          </a>

          <!-- Theme toggle -->
          <button id="themeToggle" class="btn btn-outline-secondary btn-sm rounded-circle" title="<?php echo htmlspecialchars($lang['theme']); ?>">
            <i id="themeIcon" class="fa-regular fa-moon"></i>
          </button>

          <!-- Admin modal button -->
          <button class="btn btn-secondary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#adminModal">
            <?php echo htmlspecialchars($lang['nav_login']); ?>
          </button>
        </div>
      </div>
    </div>
  </nav>
  <!-- HERO -->
  <section class="event-hero">
    <div class="container">
      <h1 class="fw-bold mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['nav_events']); ?></h1>
      <p class="lead" data-aos="fade-up" data-aos-delay="100">
        <?php echo ($lang_code == 'ar') ? 'استكشف جميع الفعاليات القادمة في مدينتك' : 'Explore all upcoming events in your city'; ?>
      </p>
    </div>
  </section>

  <!-- FILTER BUTTONS -->
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

  <!-- EVENTS GRID -->
  <div class="container my-5">
    <div class="row" id="eventsGrid">
      <?php if ($result && $result->num_rows > 0): while ($e = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4 event-card" data-category="<?php echo htmlspecialchars($e['category']); ?>" data-aos="fade-up">
            <div class="card h-100 card-hover shadow-sm">
              <img src="assets/img/<?php echo htmlspecialchars($e['image']); ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="">
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

  <!-- FOOTER -->
  <footer class="footer mt-5 pt-5 pb-3 bg-dark text-light">
    <div class="container">
      <div class="row gy-4">

        <!-- Logo & Description -->
        <div class="col-md-4 text-center text-md-start">
          <a href="index.php" class="d-flex align-items-center mb-3 text-decoration-none text-light">
            <img src="assets/img/logo.svg" alt="logo" style="height:50px; margin-inline-end:10px;">
          </a>
          <p class="small mb-0"><?php echo htmlspecialchars($lang['footer_desc']); ?></p>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4 text-center">
          <h6 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['footer_quick_links']); ?></h6>
          <ul class="list-unstyled">
            <li><a href="index.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_home']); ?></a></li>
            <li><a href="events.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_events']); ?></a></li>
            <li><a href="about.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_about']); ?></a></li>
            <li><a href="contact.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_contact']); ?></a></li>
          </ul>
        </div>

        <!-- Contact & Social -->
        <div class="col-md-4 text-center text-md-end">
          <h6 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['footer_contact_title']); ?></h6>
          <p class="small mb-1">
            <i class="fa-regular fa-envelope me-2"></i> info@example.com
          </p>
          <div class="d-flex justify-content-center justify-content-md-end gap-3">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>

      </div>

      <hr class="border-secondary mt-4">
      <div class="text-center footer-bottom">
        © <?php echo date('Y'); ?> <?php echo htmlspecialchars($lang['title']); ?> — <?php echo htmlspecialchars($lang['footer_rights']); ?>.
      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script>
    AOS.init({
      duration: 700,
      once: true
    });

    // تصفية الفعاليات حسب التصنيف
    $('.filter-btn').on('click', function() {
      const category = $(this).data('cat');
      $('.filter-btn').removeClass('active');
      $(this).addClass('active');
      if (category === 'all') {
        $('.event-card').show();
      } else {
        $('.event-card').hide();
        $(`.event-card[data-category="${category}"]`).show();
      }
    });
  </script>
</body>

</html>