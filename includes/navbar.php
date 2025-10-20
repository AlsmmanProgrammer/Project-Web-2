<nav class="navbar navbar-expand-lg shadow-sm sticky-top custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/img/logo.svg" alt="logo" style="height:45px; margin-inline-end:8px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                        href="index.php"><?php echo htmlspecialchars($lang['nav_home']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>"
                        href="events.php"><?php echo htmlspecialchars($lang['nav_events']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>"
                        href="about.php"><?php echo htmlspecialchars($lang['nav_about']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>"
                        href="contact.php"><?php echo htmlspecialchars($lang['nav_contact']); ?></a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 ms-lg-3">
                <a id="langToggle" class="btn btn-outline-secondary btn-sm rounded-circle"
                    href="<?php echo htmlspecialchars($lang_link); ?>"
                    title="<?php echo htmlspecialchars($lang['lang']); ?>">
                    <i id="langIcon" class="fa-solid fa-globe"></i>
                </a>

                <button id="themeToggle" class="btn btn-outline-secondary btn-sm rounded-circle"
                    title="<?php echo htmlspecialchars($lang['theme']); ?>">
                    <i id="themeIcon" class="fa-regular fa-moon"></i>
                </button>

                <a href="admin/login.php" class="btn btn-secondary btn-sm px-3">
                    <?php echo htmlspecialchars($lang['nav_login']); ?>
                </a>
            </div>
        </div>
    </div>
</nav>
