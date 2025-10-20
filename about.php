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

    <!-- Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <!-- Unified Styles -->
    <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body class="light-mode">

    <?php include "includes/navbar.php"; ?>

    <!-- ====== Hero Section ====== -->
    <section class="hero about-hero py-5">
        <div class="container text-center">
            <h1 class="fw-bold mb-3" data-aos="fade-up">
                <?php echo htmlspecialchars($lang['about_title']); ?>
            </h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">
                <?php echo htmlspecialchars($lang['about_subtitle']); ?>
            </p>
        </div>
    </section>

    <!-- ====== About Vision Section ====== -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1542744173-05336fcc7ad4" alt="About Us" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h3 class="fw-bold mb-3 text-primary">
                        <?php echo htmlspecialchars($lang['about_vision']); ?>
                    </h3>
                    <p class="text-muted">
                        <?php echo htmlspecialchars($lang['about_vision_text1']); ?>
                    </p>
                    <p class="text-muted mb-0">
                        <?php echo htmlspecialchars($lang['about_vision_text2']); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== Team Section ====== -->
    <section class="py-5 achievements">
        <div class="container">
            <h3 class="text-center fw-bold mb-5" data-aos="fade-up">
                <?php echo htmlspecialchars($lang['about_team']); ?>
            </h3>

            <div class="row justify-content-center gy-4">
                <?php
                $team = [
                    [
                        "name_ar" => "بشير السمان",
                        "name_en" => "Basheer Alsamman",
                        "role_en" => "Full Stack Developer",
                        "img" => "https://cdn-icons-png.flaticon.com/512/921/921071.png"
                    ],
                    [
                        "name_ar" => "ملاك جيرون",
                        "name_en" => "Malak Jiroun",
                        "role_en" => "Front-End Developer",
                        "img" => "https://cdn-icons-png.flaticon.com/512/4140/4140047.png"
                    ],
                    [
                        "name_ar" => "آية عبد المولى",
                        "name_en" => "Aya Abdulmawla",
                        "role_en" => "Front-End Developer",
                        "img" => "https://cdn-icons-png.flaticon.com/512/4140/4140051.png"
                    ],
                    [
                        "name_ar" => "حلا خالد",
                        "name_en" => "Hala Khaled",
                        "role_en" => "Front-End Developer",
                        "img" => "https://cdn-icons-png.flaticon.com/512/4140/4140047.png"
                    ],
                    [
                        "name_ar" => "راما الحلاق",
                        "name_en" => "Rama Alhallak",
                        "role_en" => "Front-End Developer",
                        "img" => "https://cdn-icons-png.flaticon.com/512/4140/4140051.png"
                    ],
                ];

                foreach ($team as $index => $member): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo 100 * $index; ?>">
                        <div class="card team-card border-0 shadow-sm rounded-4 h-100 card-hover">
                            <div class="p-3 text-center">
                                <img src="<?php echo htmlspecialchars($member['img']); ?>"
                                    class="rounded-circle shadow-sm"
                                    alt="<?php echo htmlspecialchars($member['name_ar']); ?>"
                                    style="width:120px; height:120px; object-fit:cover;">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-1">
                                    <?php echo htmlspecialchars($member['name_en']); ?>
                                </h5>
                                <small class="text-muted d-block mb-2">
                                    <?php echo htmlspecialchars($member['name_ar']); ?>
                                </small>
                                <p class="text-secondary small mb-0">
                                    <?php echo htmlspecialchars($member['role_en']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        AOS.init({
            duration: 700,
            once: true
        });
    </script>
</body>

</html>