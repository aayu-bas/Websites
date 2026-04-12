<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Yarnify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Pacifico&display=swap" rel="stylesheet">
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}
body {
font-family: "Lilita One", sans-serif;
color: #614c37;
}

#header {
height: 120px;
padding: 10px 0;
background-color: #ffffb7;
position: sticky;
top: 0;
z-index: 1000;
box-shadow: 0 0 10px rgb(80, 80, 80), 0 0 5px rgb(179, 179, 179);
}
.container {
padding: 8px 40px;
}
.navbar {
display: flex;
align-items: center;
justify-content: space-between;
}
.navbar-left {
display: flex;
align-items: center;
gap: 4px;
}

.logo {
height: 100px;
}
.brand-name {
font-size: 30px;
color: #f4b9e8;
font-family: "Pacifico", cursive;
}
#tabs ul {
display: flex;
align-items: center;
list-style: none;
padding: 0;
}
#tabs ul li {
margin-left: 20px;
margin-right: 12px;
}

#tabs ul li a {
color: #7d9b45;
text-decoration: none;
font-size: 22px;
position: relative;
padding-bottom: 4px;
}

#tabs ul li a.active {
color: #8d6cf7;
}

#tabs ul li a::after {
content: '';
width: 0;
height: 3px;
background: #8d6cf7;
border-radius: 6px;
position: absolute;
left: 0;
bottom: 0;
transition: width 0.3s ease;
}

#tabs ul li a:hover::after,
#tabs ul li a.active::after {
width: 100%;
}

.search-container{
  position:relative;
}
.search-bar{
  display: flex;
  align-items: center;
  background: #ffffff;
  border: 2px solid #e0f8c5;
  border-radius: 50px;
  padding:8px 14px;
  gap:8px;
  min-width: 200px;
  transition: border-color 0.3s, box-shadow 0.3s;
}
.search-bar input{
  border:none;
  outline: none;
  background: transparent;
  font-size: 14px;
  font-family: "Lilita One", sans-serif;
  color: #614c37;
  min-width: 140px;
}
::placeholder{
  color: rgb(192, 192, 192);
}
.fa-magnifying-glass{
  font-size: 18px;
  color: #0d5bcf;
  cursor: pointer;
}
.fa-user{
  font-size: 20px;
  color: rgb(254, 127, 148);
  cursor: pointer;
}

.fa-bag-shopping{
  font-size: 20px;
  color: rgb(254, 127, 148);
  cursor: pointer;
}
/* Hero Section */
.hero {
text-align: center;
padding: 80px 20px;
background: linear-gradient(135deg, #fbd3e4 0%, #fff9e6 100%);
position: relative;
overflow: hidden;
box-shadow: 5px 5px 10px 0px rgba(255, 192, 203, 0.5);
}

/* .hero::before {
content: '🧶';
position: absolute;
font-size: 120px;
opacity: 0.1;
top: 20px;
left: 10%;
animation: float 6s ease-in-out infinite;
} */

/* .hero::after {
content: '✨';
position: absolute;
font-size: 80px;
opacity: 0.15;
bottom: 40px;
right: 15%;
animation: float 5s ease-in-out infinite reverse;
}

@keyframes float {
0%, 100% { transform: translateY(0px); }
50% { transform: translateY(-20px); }
} */

.hero h1 {
font-size: 3.5rem;
color: #e0adbd;
margin-bottom: 20px;
font-family: "Pacifico", cursive;
}

.hero p {
font-size: 1.3rem;
color: #8a70cb;
max-width: 700px;
margin: 0 auto 30px;
line-height: 1.6;
}

/* Our Story Section */
.story-section {
max-width: 1200px;
margin: 80px auto;
padding: 0 40px;
}

.story-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 60px;
align-items: center;
margin-bottom: 80px;
}

.story-image {
position: relative;
}

.story-image img {
width: 100%;
height: 400px;
object-fit: cover;
border-radius: 30px;
box-shadow: 0 20px 60px rgba(138, 112, 203, 0.3);
transition: transform 0.4s ease;
}

.story-image img:hover {
transform: scale(1.05) rotate(2deg);
}

.story-content h2 {
font-size: 2.5rem;
color: #d6608a;
margin-bottom: 20px;
font-family: "Pacifico", cursive;
}

.story-content p {
font-size: 1.1rem;
line-height: 1.8;
color: #614c37;
margin-bottom: 15px;
}

.story-highlight {
background: linear-gradient(135deg, #ffeef5, #fff9e6);
padding: 30px;
border-radius: 20px;
border: 3px solid #ffe8f0;
margin-top: 20px;
}

/* Team Section */
.team-section {
background:linear-gradient(135deg,#a7d7f7 0%, white 100%);
padding: 80px 40px;
text-align: center;
}

.team-section h2 {
font-size: 2.8rem;
color: #8a70cb;
margin-bottom: 50px;
font-family: "Pacifico", cursive;
}

.team-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
gap: 40px;
max-width: 1200px;
margin: 0 auto;
}

.team-card {
background: white;
border-radius: 25px;
padding: 30px;
box-shadow: 0 15px 40px rgba(138, 112, 203, 0.2);
transition: transform 0.3s ease;
}

.team-card:hover {
transform: translateY(-10px);
}

.team-icon {
width: 120px;
height: 120px;
background: linear-gradient(135deg, #ffd6e8, #e8d5ff);
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 3rem;
margin: 0 auto 20px;
}

.team-card h3 {
color: #4eb7f8;
font-size: 1.5rem;
margin-bottom: 10px;
}

.team-card p {
color: #614c37;
font-size: 1rem;
line-height: 1.6;
}

/* Values Section */
.values-section {
max-width: 1200px;
margin: 80px auto;
padding: 0 40px;
background: linear-gradient(135deg, #fff5f7 0%, #fff9e6 100%);
}

.values-section h2 {
font-size: 2.8rem;
color: #e0adbd;
text-align: center;
margin-bottom: 50px;
font-family: "Pacifico", cursive;
}

.values-grid {
display: grid;
grid-template-columns: repeat(3, 1fr);
gap: 30px;
}

.value-card {
background: white;
padding: 40px 30px;
border-radius: 20px;
text-align: center;
border: 3px solid #976432;
transition: all 0.3s ease;
}

.value-card:hover {
border-color:#654321;
transform: translateY(-5px);
box-shadow: 0 10px 30px rgba(255, 234, 130, 0.2);
}

.value-icon {
font-size: 3.5rem;
margin-bottom: 20px;
}

.value-card h3 {
color: #8a70cb;
font-size: 1.4rem;
margin-bottom: 15px;
}

.value-card p {
color: #614c37;
line-height: 1.6;
}

/* Footer */
footer {
background: #ffffb7;
padding: 40px 20px 24px;
box-shadow: 0 0 10px #ffffb7, 0 0 5px #505050;
margin-top: 80px;
text-align: center;
}

footer p {
color: #d6608a;
font-size: 1rem;
}

/* Responsive */
/* @media (max-width: 768px) {
.story-grid {
    grid-template-columns: 1fr;
    gap: 40px;
}

.values-grid {
    grid-template-columns: 1fr;
}

.hero h1 {
    font-size: 2.5rem;
}

.hero p {
    font-size: 1.1rem;
}
} */
</style>
</head>
<body>

    <!-- Header -->
    <div id="header">
        <div class="container">
            <nav class="navbar">
                <div class="navbar-left">
                    <img src="yarnify.png" alt="logo" class="logo">
                    <a href="index.html" style="text-decoration: none;"><p class="brand-name">Yarnify</p></a>
                </div>
                <div id="tabs">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php" class="active">About</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="patterns.php">Patterns</a></li>
                        <li><a href="yarns.php">Yarns</a></li>
                        <li class="search-container">
                            <div class="search-bar">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchInput" placeholder="Search" autocomplete="off"/>
                            </div>
                            <div class="search-results" id="searchResults"></div>
                        </li>
                        <li><i class="fa-regular fa-user" title="user" id="userIcon"></i></li>
                        <li><i class="fa-solid fa-bag-shopping" title="cart"></i></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Yarnify 🧸</h1>
        <p>Handmade with love. Inspired by creativity. Created for you! ✨</p>
    </section>

    <!-- Our Story -->
    <section class="story-section">
        <div class="story-grid">
            <div class="story-image">
                
                <img src="us.jpeg" alt="Our Story">
            
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
                <img src="photos/why_crochet.jpg" alt="Why Crochet">
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

    <!-- Footer -->
    <footer>
        <p>Made with Love and Care by Yarnify © 2025</p>
    </footer>

</body>
</html>