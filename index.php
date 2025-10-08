<?php
// index.php
include "config/lang_config.php";
include "database/db.php";

// مسار الصفحة الحالية بدون query string
$current_path = strtok($_SERVER["REQUEST_URI"], '?');

// Toggle language link (يحافظ على باقي الـ GET params)
$toggle_lang = $lang_code === 'ar' ? 'en' : 'ar';
$query = $_GET;
$query['lang'] = $toggle_lang;
$lang_link = $current_path . '?' . http_build_query($query);

// تاريخ اليوم لجلب الفعاليات القادمة
$today = date('Y-m-d');

// جلب فعاليات بارزة (3 قادمة)
$featured_sql = "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC LIMIT 3";
$stmt = $conn->prepare($featured_sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$featured_result = $stmt->get_result();

// جلب أحدث 8 فعاليات قادمة
$latest_sql = "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC LIMIT 8";
$stmt2 = $conn->prepare($latest_sql);
$stmt2->bind_param("s", $today);
$stmt2->execute();
$latest_result = $stmt2->get_result();

// جلب قائمة التصنيفات المتاحة
$cat_sql = "SELECT DISTINCT category FROM events";
$cat_result = $conn->query($cat_sql);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang_code); ?>" dir="<?php echo $lang_code == 'ar' ? 'rtl' : 'ltr'; ?>">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($lang['title']); ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS (Animate on Scroll) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <link href="assets/css/styles.css" rel="stylesheet">

  <style>
    /* small in-file adjustments */
    .hero {
      background: linear-gradient(120deg, rgba(0, 123, 255, 0.12), rgba(255, 193, 7, 0.08));
      padding: 80px 0;
    }

    .card-hover:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }
  </style>
</head>

<body class="light-mode">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="assets/img/logo.png" alt="logo" style="height:45px; margin-inline-end:8px;">
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
  <section class="hero">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6" data-aos="fade-right">
          <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($lang['title']); ?></h1>
          <p class="lead"><?php echo htmlspecialchars($lang['subtitle']); ?></p>
          <a href="events.php" class="btn btn-secondary btn-lg"><?php echo htmlspecialchars($lang['cta']); ?></a>
        </div>
        <div class="col-lg-6 text-center" data-aos="fade-left">
          <img src="assets/img/hero.jpg" alt="hero" class="img-fluid rounded shadow" style="max-height:360px;object-fit:cover;">
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURED CAROUSEL -->
  <div class="container my-5">
    <h3 class="mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['featured']); ?></h3>

    <?php if ($featured_result && $featured_result->num_rows > 0): ?>
      <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up">
        <div class="carousel-inner">
          <?php $first = true;
          while ($f = $featured_result->fetch_assoc()): ?>
            <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
              <div class="row g-0 align-items-center">
                <div class="col-md-5">
                  <img src="assets/img/<?php echo htmlspecialchars($f['image']); ?>" class="d-block w-100" alt="" style="height:300px;object-fit:cover;">
                </div>
                <div class="col-md-7 p-4">
                  <h4><?php echo htmlspecialchars($f['title']); ?></h4>
                  <p><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($f['event_date']); ?> &nbsp; <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($f['location']); ?></p>
                  <p><?php echo htmlspecialchars(mb_substr($f['description'], 0, 200)); ?>...</p>
                  <a href="event.php?id=<?php echo (int)$f['id']; ?>" class="btn btn-outline-secondary">تفاصيل</a>
                </div>
              </div>
            </div>
          <?php $first = false;
          endwhile; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    <?php else: ?>
      <p class="text-muted"><?php echo htmlspecialchars($lang['no_events']); ?></p>
    <?php endif; ?>
  </div>

  <!-- CATEGORIES -->
  <div class="container my-4" data-aos="fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h4><?php echo htmlspecialchars($lang['categories']); ?></h4>
      <div>
        <button class="btn btn-sm btn-outline-secondary filter-btn" data-cat="all">All</button>
      </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <?php if ($cat_result && $cat_result->num_rows > 0): while ($c = $cat_result->fetch_assoc()): ?>
          <button class="btn btn-sm btn-outline-secondary filter-btn" data-cat="<?php echo htmlspecialchars($c['category']); ?>"><?php echo htmlspecialchars($c['category']); ?></button>
        <?php endwhile;
      else: ?>
        <span class="text-muted">No categories</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- LATEST EVENTS GRID -->
  <div class="container my-5">
    <h4 class="mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['latest']); ?></h4>

    <div class="row" id="eventsGrid">
      <?php if ($latest_result && $latest_result->num_rows > 0): while ($e = $latest_result->fetch_assoc()): ?>
          <div class="col-md-3 mb-4 event-card" data-category="<?php echo htmlspecialchars($e['category']); ?>" data-aos="fade-up">
            <div class="card h-100 card-hover">
              <img src="assets/img/<?php echo htmlspecialchars($e['image']); ?>" class="card-img-top" style="height:160px; object-fit:cover;" alt="">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($e['title']); ?></h5>
                <p class="card-text text-muted small"><?php echo htmlspecialchars($e['event_date']); ?> • <?php echo htmlspecialchars($e['location']); ?></p>
                <p class="flex-grow-1 small"><?php echo htmlspecialchars(mb_substr($e['description'], 0, 80)); ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="event.php?id=<?php echo (int)$e['id']; ?>" class="btn btn-sm btn-secondary">تفاصيل</a>
                  <span class="badge bg-secondary"><?php echo htmlspecialchars($e['category']); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile;
      else: ?>
        <p><?php echo htmlspecialchars($lang['no_events']); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- SCROLL TO TOP -->
  <button id="scrollTopBtn" class="btn btn-secondary" title="Scroll to top" style="position:fixed;right:20px;bottom:20px;display:none;"><i class="fa-solid fa-arrow-up"></i></button>

  <!-- FOOTER -->
  <footer class="footer mt-5 pt-5 pb-3 bg-dark text-light">
    <div class="container">
      <div class="row gy-4">

        <!-- Logo & Description -->
        <div class="col-md-4 text-center text-md-start">
          <a href="index.php" class="d-flex align-items-center mb-3 text-decoration-none text-light">
            <img src="assets/img/logo.png" alt="logo" style="height:50px; margin-inline-end:10px;">
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



  <!-- ADMIN LOGIN MODAL -->
  <div class="modal fade" id="adminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" action="admin/login.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo htmlspecialchars($lang['admin_login']); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label"><?php echo htmlspecialchars($lang['login_username']); ?></label>
            <input name="username" type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><?php echo htmlspecialchars($lang['login_password']); ?></label>
            <input name="password" type="password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">إغلاق</button>
          <button class="btn btn-secondary" type="submit"><?php echo htmlspecialchars($lang['login_btn']); ?></button>
        </div>
      </form>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    // init AOS
    AOS.init({
      duration: 700,
      once: true
    });

    // small: show/hide scroll button
    const scrollBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', () => {
      scrollBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
    scrollBtn.addEventListener('click', () => window.scrollTo({
      top: 0,
      behavior: 'smooth'
    }));
  </script>

</body>

</html>