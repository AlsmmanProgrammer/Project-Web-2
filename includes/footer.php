

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