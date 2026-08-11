    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3><i class="fas fa-heart"></i> Yarnify</h3>
                    <p>Handmade with love, one stitch at a time. Every piece tells a story of patience, creativity, and warmth.</p>
                </div>

                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php">Shop All</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/custom-designer.php">Custom Orders</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About Us</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php?category=amigurumi">Amigurumi</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php?category=wearables">Wearables</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php?category=decors">Decors</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php?category=keychains">Keychains</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Support and Legal</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/pages/shop.php">FAQs</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Shipping Info</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/custom-designer.php">Return Policy</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/about.php">Terms and Condition</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-envelope"></i> hello@yarnify.com</p>
                    <p><i class="fas fa-phone"></i> +977 9876543210</p>
                    <p><i class="fas fa-map-marker-alt"></i> Langankhel, Patan</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p> Yarnify. All rights reserved. Handmade with <i class="fas fa-heart" style="color: var(--color-pink);"></i></p>
            </div>
        </div>
    </footer>

    <script src="<?php echo ASSETS_URL; ?>/js/script.js"></script>
    <?php if (isset($extraJS)) echo $extraJS; ?>
</body>
</html>