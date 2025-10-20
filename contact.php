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
    <title><?php echo htmlspecialchars($lang['contact_title']); ?> | <?php echo htmlspecialchars($lang['title']); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/contact.css" rel="stylesheet">
</head>

<body class="light-mode">
    <?php include "includes/navbar.php"; ?>


    <section class="hero about-hero py-5">
        <div class="container text-center">
            <h1 class="fw-bold mb-3" data-aos="fade-up">
                <?php echo htmlspecialchars($lang['contact_title']); ?>
            </h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">
                <?php echo htmlspecialchars($lang['contact_subtitle']); ?>
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <!-- Info -->
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="p-4 rounded-4 shadow-sm bg-white dark-card h-100">
                        <h4 class="fw-bold mb-3 text-secondary"><?php echo htmlspecialchars($lang['contact_info']); ?></h4>
                        <p class="text-muted mb-4"><?php echo htmlspecialchars($lang['contact_text']); ?></p>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-circle bg-primary-subtle text-primary me-3">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <span><?php echo htmlspecialchars($lang['contact_address']); ?></span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-circle bg-success-subtle text-success me-3">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <span>info@example.com</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="icon-circle bg-warning-subtle text-warning me-3">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <span>+963 999 999 999</span>
                            </li>
                        </ul>

                        <div class="d-flex gap-3">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card border-0 shadow-sm rounded-4 bg-white dark-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 text-primary"><?php echo htmlspecialchars($lang['contact_form_title']); ?></h5>
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

                                <button type="submit" class="btn btn-secondary w-100 py-2">
                                    <?php echo htmlspecialchars($lang['contact_send']); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/contact.js"></script>
</body>

</html>