<?php
// contact.php
include "config/lang_config.php";
include "database/db.php";

// إعداد روابط اللغة
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
    <title><?php echo htmlspecialchars($lang['contact_title']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

    <!-- Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/contact.css" rel="stylesheet">
</head>

<body class="light-mode">

    <!-- NAVBAR -->
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

    <!-- HERO -->
    <section class="contact-hero text-center py-5">
        <div class="container">
            <h1 class="fw-bold mb-3" data-aos="fade-up"><?php echo htmlspecialchars($lang['contact_title']); ?></h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($lang['contact_subtitle']); ?></p>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section py-5">
        <div class="container">
            <div class="row gy-4">
                <!-- Contact Info -->
                <div class="col-lg-5" data-aos="fade-right">
                    <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['contact_info']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($lang['contact_text']); ?></p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-secondary"></i> <?php echo htmlspecialchars($lang['contact_address']); ?></li>
                        <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-secondary"></i> info@example.com</li>
                        <li class="mb-2"><i class="fa-solid fa-phone me-2 text-secondary"></i> +963 999 999 999</li>
                    </ul>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3"><?php echo htmlspecialchars($lang['contact_form_title']); ?></h5>
                            <form id="contactForm" method="POST" action="send_message.php">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?php echo htmlspecialchars($lang['contact_name']); ?></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?php echo htmlspecialchars($lang['contact_email']); ?></label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo htmlspecialchars($lang['contact_subject']); ?></label>
                                    <input type="text" name="subject" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo htmlspecialchars($lang['contact_message']); ?></label>
                                    <textarea name="message" rows="5" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-secondary w-100 py-2"><?php echo htmlspecialchars($lang['contact_send']); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <script src="assets/js/main.js"></script>
    <script src="assets/js/contact.js"></script>
</body>

</html>