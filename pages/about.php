<?php
$pageTitle = 'About Us ';
require_once __DIR__ . '/../includes/header.php';
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/style.css">';
?>
    <!-- Hero Section -->
    <section class="hero">
        <h1>Yarnify 🧸</h1>
        <p>Handmade with love. Inspired by creativity. Created for you! ✨</p>
    </section>

    <!-- Our Story -->
    <section class="story-section">
        <div class="story-grid">
            <div class="story-image">
                
                <img src="<?php echo ASSETS_URL; ?>/images/us.jpeg" alt="Our Story">
            
            </div>
            <div class="story-content">
                <h2>Our Story 💕</h2>
                <p>Hello Cuties (｡•̀ᴗ-)✧</p>
                <p>Starting our bachelor's journey, we discovered the joy of crocheting. What began as making cute keychains and charms for friends quickly became our creative sanctuary in the midst of busy college life.</p>
                <p>Each stitch brought us peace, each creation sparked joy, and every gift we made strengthened our friendships. We realized that this wasn't just a hobby—it was our way of spreading happiness.</p>
                <div class="story-highlight">
                    <p><strong>So we created Yarnify</strong> - a place where we can share our passion with you. Every piece is crafted with love, inspired by creativity, and made just for you! 💖</p>
                </div>
            </div>
        </div>

        <div class="story-grid" style="margin-top: 80px;">
            <div class="story-content">
                <h2>Why Crochet?</h2>
                <p>Crocheting is more than just yarn and hooks—it's therapy, art, and meditation all woven together. In a world that moves so fast, taking time to create something with your own hands is magical.</p>
                <p>We've poured countless hours into perfecting our craft, learning new techniques, and experimenting with colors and patterns. Each mistake taught us something new, and every completed project filled us with pride.</p>
                <p>Now, we want to share that feeling with you. Whether you're looking for a unique gift, a cozy companion, or starting your own crochet journey, we're here to help! 🌟</p>
            </div>
            <div class="story-image">
                <img src="<?php echo ASSETS_URL; ?>/images/products/crochet.png" alt="Why Crochet">
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <h2>Our Team</h2>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-icon">🌸</div>
                <h3>Amigurumi Maker</h3>
                <p>Specializes in amigurumi and character designs. Loves bringing cute creatures to life with yarn and a sprinkle of imagination!</p>
            </div>
            <div class="team-card">
                <div class="team-icon">💃🏻</div>
                <h3>Dress Maker</h3>
                <p>Master of color combinations and wearables. Creates stunning cardigans, bags, and accessories that turn heads wherever you go!</p>
            </div>
            <div class="team-card">
                <div class="team-icon">💡</div>
                <h3>Brainiacs</h3>
                <p>Always experimenting with new patterns and techniques. Brings fresh ideas and unique designs to every collection!</p>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <h2>What We Believe In 💫</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">❤︎</div>
                <h3>Handmade with Love</h3>
                <p>Every stitch is made with care and attention. No mass production, just genuine craftsmanship and heart.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">✔</div>
                <h3>Quality Materials</h3>
                <p>We use only the best yarn and materials to ensure your crochet pieces last for years to come.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌎🌿</div>
                <h3>Sustainability</h3>
                <p>We care about our planet. Our packaging is eco-friendly and we minimize waste in every step.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">📜𓂃🪶</div>
                <h3>Custom Orders</h3>
                <p>Got a special request? We love creating personalized pieces that are uniquely yours!</p>
            </div>
            <div class="value-card">
                <div class="value-icon">^•ﻌ•^ฅ♡</div>
                <h3>Teaching & Sharing</h3>
                <p>We believe in spreading the joy of crochet through free patterns, tutorials, and tips!</p>
            </div>
            <div class="value-card">
                <div class="value-icon">💌</div>
                <h3>Community First</h3>
                <p>You're not just a customer—you're part of our crochet family. We're here to support your creative journey!</p>
            </div>
        </div>
    </section>
    
<?php require_once __DIR__ . '/../includes/footer.php'; ?>