<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Contact Us';

$success= false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name)) {
        $errors[] = "Please enter your name";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email";
    }

    if (empty($subject)) {
        $errors[] = "Please enter a subject";
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long.";
    }

    if (empty($errors)) {

        //sending mail here
        // mail('hello@yarnify.com', $subject, $message, "From: $email");

        $success = true;
    }
}
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/style.css">';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="contact-hero">
    <h1>Yarnify-Get in Touch</h1>
    <p>We'd love to hear from you! Whether you have a question about our crochet pieces, need help with a custom order, or just want to say hi — our inbox is always cozy and open.</p>
</section>

<div class="contact-cards">
    <div class="contact-card">
        <div class="icon-wrapper">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3>Visit Our Studio</h3>
        <p>Lagankhel, Patan</p>
        <a href="https://maps.google.com" target="blank">Get Directions ↗</a>
    </div>
    <div class="contact-card">
        <div class="icon-wrapper">
            <i class="fa-solid fa-phone"></i>
        </div>
        <h3>Call Us</h3>
         <p>
            <a href="tel:+919876543210">+91 98765 43210</a><br>
            <small style="color: #8d6cf7;">Mon-Sat, 10am - 7pm</small>
        </p>
    </div>
    <div class="contact-card">
        <div class="icon-wrapper">
            <i class="fas fa-envelope"></i>
        </div>
        <h3>Email Us</h3>
        <p>
            <a href="mailto:hello@yarnify.com">hello@yarnify.com</a><br>
        </p>
    </div>
</div>

<section class="contact-section">
    <div class="contact-form-wrapper">
        <h2>Send us a Message 📩</h2>
        <p class="subtitle">Fill out the form below and we'll get back to you within 24 hours!</p>

        <?php if($success):?>
            <div class="success-message">
                <i class="fas fa-heart"></i>
                <h3>Message Sent Successfully!</h3>
                <p>Thank you for reaching out, <?php echo htmlspecialchars($name); ?>! We've received your message and will reply to <?php echo htmlspecialchars($email); ?> soon.</p>
        
            </div>
        <?php endif; ?>
        <?php if(!empty($errors)):?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                 <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if(!$success): ?>
        <form action="" method="POST" id="contactForm">
            <div class="form-row">
                <div class="form-group">
                    <i class="fas fa-user"></i> Your Name
                    <input type="text" name="name" placeholder="Aayusha Basnet" required 
                        value="<?php echo $_POST['name'] ?? ''; ?>">
                </div>
                <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" placeholder="abc@example.com" required
                        value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Subject</label>
                <input type="text" name="subject" placeholder="What's this about?" required
                    value="<?php echo $_POST['subject'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-comment-dots"></i> Your Message</label>
                <textarea name="message" placeholder="Tell us everything! Ask about custom orders, shipping, or collaborations." required minlength="10"><?php echo $_POST['message'] ?? ''; ?></textarea>
            </div>
        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Send Message
        </button>
        </form>
        <?php endif; ?>
    </div>

    <div class="contact-info-panel">
        <div class="info-block">
            <h3><i class="fas fa-clock"></i> Studio Hours</h3>
            <ul class="hours-list">
                <li><span class="day">Monday - Friday</span> <span class="time">10:00 AM - 7:00 PM</span></li>
                <li><span class="day">Saturday</span> <span class="time">11:00 AM - 6:00 PM</span></li>
                <li><span class="day">Sunday</span> <span class="time">Closed</span></li>
            </ul>
        </div>

        <div class="info-block">
            <h3><i class="fas fa-shipping-fast"></i> Quick Info</h3>
            <p style="color: #6b5b3e; line-height: 1.8; margin-bottom: 15px;">
                <i class="fas fa-check-circle" style="color: #7d9b45;"></i> Free shipping over ₹999<br>
                <i class="fas fa-check-circle" style="color: #7d9b45;"></i> Cash on Delivery available<br>
                <i class="fas fa-check-circle" style="color: #7d9b45;"></i> Custom orders take 5-7 days<br>
                <i class="fas fa-check-circle" style="color: #7d9b45;"></i> All items handmade with love
            </p>
        </div>
        
        <div class="info-block">
            <h3><i class="fas fa-hashtag"></i> Connect With Us</h3>
            <p style="color: #6b5b3e; margin-bottom: 15px;">Follow our crochet journey for behind-the-scenes, new drops, and cozy vibes!</p>
            <div class="social-connect">
                <a href="#" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="pinterest" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                <a href="#" class="facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="whatsapp" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="map-container">
        <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9780.910169594215!2d85.31644811822059!3d27.665402429026187!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19da0758c081%3A0x2a3ed12f74626419!2sLagankhel%2C%20Lalitpur%2C%20Nepal!5e0!3m2!1sen!2sin!4v1785935280485!5m2!1sen!2sin" 
         allowfullscreen="" 
         loading="lazy" 
         referrerpolicy="strict-origin-when-cross-origin"></iframe>
        <div class="map-overlay">
            <h4><i class="fas fa-store"></i> Yarnify Studio</h4>
            <p>Lagankhel, Patan</p>
        </div>
    </div>
</section>

<section class="faq-section">
    <h2>Frequently Asked Questions</h2>
    <p class="subtitle">Quick answers to common queries</p>

    <div class="faq-item">
        <div class="faq-question" onclick ="toggleFaq(this)">
            <span>How long do custom crochet take?</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Custom Orders typically takes 5-7 business days depending on the complexity of the request.Amigirimi may take longer (7-10) days. We'll always keep you updated on the progress. </p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick ="toggleFaq(this)">
            <span>Do you offer International Shipping?</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Currently we ship to Nepal only. We're working on expanding internationally soon — follow us on Instagram for updates!</p>    
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Can I request a specific color not shown?</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Absolutely! Use our Custom Order Designer or mention your preferred color in the message form. We have 30+ yarn shades available.</p>
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>What is your return policy?</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>We accept returns within 7 days of delivery for unused items in original packaging. Custom orders are non-returnable but we offer free repairs if there are any defects.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>How do I care for my crochet items?</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Hand wash gently in cold water with mild detergent. Lay flat to dry — never wring or tumble dry! Each item comes with a care card.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
