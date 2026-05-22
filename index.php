<?php
session_start();
if (isset($_SESSION['login_time'])) {
    if ((time() - $_SESSION['login_time']) > $_SESSION['expire_time']) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Yarnify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="yarnify.png">
</head>
<body class="font-style">
    <div id="header">
        <div class="container">
            <nav class="navbar">
                <div class="navbar-left">
                <img src="yarnify.png" alt="logo" class="logo">
                <a href="index.php" style="text-decoration: none;"><p class="brand-name">Yarnify</p></a>
                </div>
                <div id="tabs">
                <ul>
                    <li><a href="index.php"class="active" style="background: #ffffb7;">Home</a></li>
                    <li><a href="about.php">About</a></li>
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
                    
                    <li class="user-menu">
                        <?php if (isset($_SESSION['logged_in'])): ?>
                            <i class="fa-solid fa-user-check" id="userIcon" title="<?php echo $_SESSION['user_email']; ?>"></i>
                        <?php else: ?>
                            <a href="login.php">
                                <i class="fa-regular fa-user" title="user"></i>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li><i class="fa-solid fa-bag-shopping" title="cart"></i></li>
                </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="slideshow-container">
        <div class="mySlides fade">
            <div class="numbertext">#Spotlight 1</div>
            <img src="slider/cardigans.png" alt="winter sale promo">
            <div class="text"> WINTER SALES <i class='fas fa-snowflake' style='font-size:28px;color:rgb(190, 247, 247)'></i><br><br>
            <a href="">SHOP ALL</a></div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">#Spotlight 2</div>
            <img src="slider/plush.png" alt="">
            <div class="text">DISCOVER MORE AMIGURUMIS LIKE THIS</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">#Spotlight 3</div>
            <img src="slider/discover_pattern.jpg" alt="">
            <div class="text">Maybe some text</div>
        </div>
        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
    </div>
    <br>
    <div style="text-align: center;">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span> 
    </div>

    <h1>Our Sales(10% OFF)🎉</h1>
    <div class="sale-container">
        <div class="card">
            <div class="sale">Sale</div>
                <img src="photos/couplecat.png">
                <div class="content">
                    <h3>Couple Cat</h3>
                    <p class="old-price">Rs.3,200.00 NPR</p>
                    <p class="new-price">Rs.2,800.00 NPR</p>
                </div>
        </div>

        <div class="card">
            <div class="sale">Sale</div>
                <img src="photos/brownbag.png">
                <div class="content">
                    <h3>Cute Brown Tote Bag</h3>
                    <p class="old-price">Rs.3,200.00 NPR</p>
                    <p class="new-price">Rs.2,800.00 NPR</p>
                </div>
        </div>

        <div class="card">
            <div class="sale">Sale</div>
                <img src="photos/football pillow.png">
                <div class="content">
                    <h3>Football Pillow</h3>
                    <p class="old-price">Rs.3,200.00 NPR</p>
                    <p class="new-price">Rs.2,800.00 NPR</p>
                </div>
        </div>
    </div>

    <div class="about-me">
        <div id="inner-container">
            <div id="photo-container">
                <img src="us.jpeg" alt="" id="photo">
                <div id="story">
                    <h2>Our Story</h2>
                    <center><p>Hello Cuties(⁠｡⁠•̀⁠ᴗ⁠-⁠)⁠✧</p></center>
                    <p>Starting the bachelors, we started crocheting making cute keychains, charms and other stuff. We took joy in making gifts for our friends. Crocheting helped us find peace in everyday bustling life.
                        So, we started a crochet store where we can share our joy and comfort with you. <br><br>
                        Handmade with love. <br>
                        Inspired by creativity. <br>
                        Created for you<i class="fa-solid fa-heart fa-beat" style="color: #f7a6c9;"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>

   <div id="product-sections"></div>

    <!-- pop up cart -->
    <div class="modal" id="cartModal">
        <div class="modal-content">
            <button onclick="closeModal()" id="closed">✕</button>
            <div id="bear-icon">🧸</div>
            <h3>⋆｡‧˚ʚAdded to the Cartɞ˚‧｡⋆</h3>
            <p id="productname"></p>
            <button class="checkout" onclick="checkout()">
                Go to Checkout
            </button>
        </div>
    </div>

    <div class="browse">
        <p>Browse to other categories<span class="pointer" onclick="result()">→</span></p>
    </div>
<!-- ==================collection========================= -->
    <div class="collections">
        <div class="col-grid">
            <!-- box 1 -->
            <div class="collection-box">
                <h2>Free Patterns</h2>
                <div class="image-grid">
                    <div class="image-item">
                        <div class="pdf">PDF</div>
                        <img src="https://i.pinimg.com/736x/0c/3f/58/0c3f58ec17a1749bb65e6f7c2b368cbb.jpg" alt="">
                        <p>Teddy Bear</p>
                       
                    </div>

                    <div class="image-item">
                        <div class="pdf">PDF</div>
                        <img src="https://i.pinimg.com/736x/a1/12/95/a112952b21fed735c7e3c17f80aaffe1.jpg" alt="">
                        <p>Bunny Plush</p>
                    </div>

                    <div class="image-item">
                        <div class="pdf">PDF</div>
                        <img src="https://i.pinimg.com/736x/1d/6e/7d/1d6e7dd2d17865dd75ca78ca9c3ced4a.jpg" alt="">
                        <p>Unicorn</p>
                    </div>

                    <div class="image-item">
                        <div class="pdf">PDF</div>
                        <img src="https://i.pinimg.com/736x/d7/1b/30/d71b30e97fc0ed177536a44179f29e54.jpg" alt="">
                        <p>Dinosaur</p>
                    </div>
                </div>
                <a href="#" class="see-more">See More →</a>
            </div>
            <!-- box 2-->
             <div class="collection-box">
                <h2>Yarn Collections</h2>
                <div class="image-grid">
                    <div class="image-item">
                        <img src="https://i.pinimg.com/736x/77/1a/c7/771ac748f0e6a346093e89632348e693.jpg" alt="">
                        <p>Pastel Yarn</p>
                       
                    </div>

                    <div class="image-item">   
                        <img src="https://i.pinimg.com/736x/0a/42/bf/0a42bf42af4d60a4c957f4f1e39286f3.jpg" alt="">
                        <p>Bulky Yarn</p>
                    </div>

                    <div class="image-item">
                        <img src="https://i.pinimg.com/1200x/21/ca/c2/21cac292ba4dd3358d6b9b341cc55285.jpg" alt="">
                        <p>Alpaca Yarn</p>
                    </div>

                    <div class="image-item">
                        <img src="https://d2q9kw5vp0we94.cloudfront.net/i/w=1000,h=1000,try=_crochet,v=1/yarnlistthumb/5420369.jpg~w=300" alt="">
                        <p>Lace Yarn</p>
                    </div>
                </div>
                <a href="#" class="see-more">See More →</a>
            </div>

            <!-- box 3 -->
             <div class="collection-box">
                <h2>Hook Sizes</h2>
                <div class="image-grid">
                    <div class="image-item">
                        
                        <img src="https://m.media-amazon.com/images/I/41ePSlOsWFL._AC_UF894,1000_QL80_.jpg" alt="">
                        <p>2.0mm & 2.5mm</p>
                       
                    </div>

                    <div class="image-item">
                       
                        <img src="https://www.hobbycraft.co.uk/dw/image/v2/BHCG_PRD/on/demandware.static/-/Sites-hobbycraft-uk-master/default/dw781bd971/images/large/665365_1000_2_-knitcraft-aluminium-crochet-hook-4-mm-pink.jpg?sw=554&q=85" alt="">
                        <p>4.0mm</p>
                    </div>

                    <div class="image-item">
                       
                        <img src="https://m.media-amazon.com/images/I/41sgSAiN47L.jpg" alt="">
                        <p>3.5mm</p>
                    </div>

                    <div class="image-item">
                        
                        <img src="https://m.media-amazon.com/images/I/51Cc2RhvspL.jpg" alt="">
                        <p>5mm</p>
                    </div>
                </div>
                <a href="#" class="see-more">See More →</a>
            </div>
            <!-- box 4 -->
             <div class="collection-box">
                <h2>KeyChains</h2>
                <div class="image-grid">
                    <div class="image-item">
                        
                        <img src="https://i.pinimg.com/1200x/a8/8d/e3/a88de37d3e251002dd8f049f01fcd093.jpg" alt="">
                        <p>Star Keyring</p>
                       
                    </div>

                    <div class="image-item">
                       
                        <img src="https://i.pinimg.com/1200x/14/ce/55/14ce553d8c2ee357993fd923f8c65e1a.jpg" alt="">
                        <p>Silver KeyRing</p>
                    </div>

                    <div class="image-item">

                        <img src="https://i.pinimg.com/1200x/0a/38/3a/0a383a62398327e3f982cf26acbaf29f.jpg" alt="">
                        <p>Metal Keyring</p>
                    </div>

                    <div class="image-item">
                        
                        <img src="https://i.pinimg.com/736x/5c/d7/42/5cd7420c542a29d47e0aebc8a203bafd.jpg" alt="">
                        <p>Gold Keyring</p>
                    </div>
                </div>
                <a href="#" class="see-more">See More →</a>
            </div>
            
        </div>
    </div>

    <!-- =============chatbot=============== -->
    <button class="chatbot-toggle" id="chatbotToggle">
        <i class="fa-solid fa-message fa-2xl" style="color: rgb(251, 251, 251);"></i>
    </button>

  <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
        <h3>🧶 Crochet Helper</h3>
        <p>Ask me anything about crochet!</p>
        </div>

        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-avatar">🧸</div>
                <div class="message-content">
                Hi there! I'm your crochet assistant. How can I help you today?
                </div>
            </div>

            <div class="typing-indicator" id="typingIndicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>

        <div class="quick-replies" id="quickReplies">
            <button class="quick-reply-btn" onclick="sendQuickReply('Free patterns')">Free Patterns</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Beginner tips')">Beginner Tips</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Yarn guide')">Yarn Guide</button>
        </div>

        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)"/>
            <button onclick="sendMessage()">➤</button>
        </div>
    </div>

    <!-- ==============footer==================== -->
    <footer>
        <div class="footer-container">
            <div class="newsletter">
                <h3>Stay  Updated! <i class="fa-solid fa-envelope"></i></h3>
                <p>Get Free patterns and crochet tips delivered to your inbox! ^^</p>
                <form action="post" class="mail">
                    <input type="email" name="Email" id="" placeholder="Your email address" required/>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ABOUT</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li> <a href="#">Support</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>  
                </div>

                <div class="footer-section">
                    <h3>LEARN</h3>
                    <ul>
                        <li><a href="#">Tools Required</a></li>
                        <li><a href="#">Beginner Tutorials</a></li>
                        <li><a href="#">Video Guides</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Return Policy</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>LEGAL</h3>
                    <ul>
                        <li><a href="#">Terms and Condition</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">Cookie Setting</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">General Product Safety Return</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    
                    <h3>CONNECT WITH US</h3>
                    <div class="social-links"></div>
                        <a href="#"><i class="fa-brands fa-instagram fa-xl"></i></i></a>
                        <a href="#"><i class="fa-brands fa-facebook fa-xl"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube fa-xl"></i></a>
                        <a href="#"><i class="fa-brands fa-pinterest fa-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <p>Made with Love and Care by Yarnify &copy; 2025</p>
            </div>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>