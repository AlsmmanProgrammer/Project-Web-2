<?php
include "config/lang_config.php";
include "database/db.php";

$toggle_lang = $lang_code === 'ar' ? 'en' : 'ar';
$current_path = strtok($_SERVER["REQUEST_URI"], '?');
$query = $_GET;
$query['lang'] = $toggle_lang;
$lang_link = $current_path . '?' . http_build_query($query);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang_code); ?>" dir="<?php echo $lang_code == 'ar' ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($lang['about_title']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/about.css" rel="stylesheet">
</head>

<body class="light-mode">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/img/logo.svg" alt="logo" style="height:45px; margin-inline-end:8px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php"><?php echo htmlspecialchars($lang['nav_home']); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>" href="events.php"><?php echo htmlspecialchars($lang['nav_events']); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php"><?php echo htmlspecialchars($lang['nav_about']); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php"><?php echo htmlspecialchars($lang['nav_contact']); ?></a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 ms-lg-3">
                    <!-- language -->
                    <a id="langToggle" class="btn btn-outline-secondary btn-sm rounded-circle" href="<?php echo htmlspecialchars($lang_link); ?>" title="<?php echo htmlspecialchars($lang['lang']); ?>">
                        <i id="langIcon" class="fa-solid fa-globe"></i>
                    </a>

                    <!-- theme -->
                    <button id="themeToggle" class="btn btn-outline-secondary btn-sm rounded-circle" title="<?php echo htmlspecialchars($lang['theme']); ?>">
                        <i id="themeIcon" class="fa-regular fa-moon"></i>
                    </button>

                    <!-- admin -->
                    <button class="btn btn-secondary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#adminModal">
                        <?php echo htmlspecialchars($lang['nav_login']); ?>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section class="about-hero">
        <div class="container">
            <h1 class="fw-bold mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['about_title']); ?></h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($lang['about_subtitle']); ?></p>
        </div>
    </section>

    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="assets/img/about.png" alt="about" class="img-fluid">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h3 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['about_vision']); ?></h3>
                    <p class="text-muted"><?php echo htmlspecialchars($lang['about_vision_text1']); ?></p>
                    <p class="text-muted"><?php echo htmlspecialchars($lang['about_vision_text2']); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center fw-bold mb-5" data-aos="fade-up"><?php echo htmlspecialchars($lang['about_team']); ?></h3>
            <div class="row gy-4">
                <?php
                $team = [
                    ["name" => "أحمد السمان", "role" => "المدير التنفيذي", "img" => "team-1.jpg"],
                    ["name" => "ليلى مراد", "role" => "مديرة الفعاليات", "img" => "team-2.jpg"],
                    ["name" => "محمد الخطيب", "role" => "مصمم واجهات", "img" => "team-3.jpg"],
                ];
                foreach ($team as $member): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card team-card text-center border-0 shadow-sm">
                            <img src="assets/img/team/<?php echo htmlspecialchars($member['img']); ?>" class="card-img-top" alt="Team" style="height:250px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="text-muted small mb-2"><?php echo htmlspecialchars($member['role']); ?></p>
                                <div>
                                    <a href="#" class="text-secondary mx-1"><i class="fab fa-facebook"></i></a>
                                    <a href="#" class="text-secondary mx-1"><i class="fab fa-linkedin"></i></a>
                                    <a href="#" class="text-secondary mx-1"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="footer mt-5 pt-5 pb-3 bg-dark text-light">
        <div class="container">
            <div class="row gy-4">

                <div class="col-md-4 text-center text-md-start">
                    <a href="index.php" class="d-flex align-items-center mb-3 text-decoration-none text-light">
                        <img src="assets/img/logo.svg" alt="logo" style="height:50px; margin-inline-end:10px;">
                    </a>
                    <p class="small mb-0"><?php echo htmlspecialchars($lang['footer_desc']); ?></p>
                </div>

                <div class="col-md-4 text-center">
                    <h6 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['footer_quick_links']); ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_home']); ?></a></li>
                        <li><a href="events.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_events']); ?></a></li>
                        <li><a href="about.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_about']); ?></a></li>
                        <li><a href="contact.php" class="footer-link"><?php echo htmlspecialchars($lang['footer_contact']); ?></a></li>
                    </ul>
                </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/about.js"></script>
</body>

</html>