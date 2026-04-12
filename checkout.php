<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
 <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Pacifico&display=swap" rel="stylesheet">
<title>Checkout – Yarnify</title>

<link href="checkout.css" rel="stylesheet"/>

</head>
<body>
  <div id="header">
    <div class="container">
      <nav class="navbar">
        <div class="navbar-left">
          <img src="yarnify.png" alt="logo" class="logo">
          <a href="index.html" style="text-decoration: none;"><p class="brand-name">Yarnify</p></a>
        </div>
        <div id="tabs">
          <ul id="abc">
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="shop.html">Shop</a></li>
            <li><a href="#">Patterns</a></li>
            <li><a href="#">Yarns</a></li>
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

<div class="product-wrap">

  <div class="gallery">
    <div class="main-img-wrap">
      <img id="mainImg" src="yarn.jpg" alt="Colourful yarn balls arranged in a circle"/>
      <div class="img-overlay"></div>
    </div>
    <div class="thumbs">
      <div class="thumb active" onclick="setImg(this,'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80')">
        <img src="crochet.jpg" alt=""/>
      </div>
      <div class="thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c?w=600&q=80')">
        <img src="16 different crochet keychains for inspiration.jpg" alt=""/>
      </div>
      <div class="thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1612476282523-50e0b7cbc8d4?w=600&q=80')">
        <img src="download (1).jpg" alt=""/>
      </div>
      <div class="thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=600&q=80')">
        <img src="download (3).jpg" alt=""/>
      </div>
      <div class="thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=600&q=80')">
        <img src="download (2).jpg" alt=""/>
      </div>
    </div>
  </div>

  <!-- Info -->
  <div class="product-info">
    <div class="category-tag">Your Favourite Yarn</div>
    <h1 class="product-title">Soft Yarn, Handmade Stitched</h1>
    <div class="stars-row">
      <span class="stars">★★★★★</span>
      <span class="review-count">(64 reviews)</span>
    </div>
    <div class="price-row">
      <span class="price-current">₹ 700.00</span>
      <span class="price-original">₹ 900.00</span>
      <span class="price-badge">22% OFF</span>
    </div>
    <p class="delivery-note">🚚 Delivery charges are calculated during checkout</p>

    <div style="animation:slideIn .5s .5s both">
      <p class="label">Quantity</p>
      <div class="qty-row">
        <button class="qty-btn" onclick="changeQty(-1)">−</button>
        <input class="qty-display" id="qty" type="text" value="1" readonly/>
        <button class="qty-btn" onclick="changeQty(1)" style="border-radius:0 10px 10px 0;border-left:none;">+</button>
      </div>
    </div>

    <div class="color-row">
      <p class="label">Color Family</p>
      <select class="color-select">
        <option>🔴 Red</option>
        <option>🔵 Blue</option>
        <option>🟢 Green</option>
        <option>🟡 Yellow</option>
        <option>🟣 Purple</option>
        <option>🩷 Pink</option>
        <option>🟠 Orange</option>
        <option>⚪ White</option>
      </select>
    </div>

    <div class="cta-wrap">
      <button class="btn-cart" onclick="addToCart()">🛒 Add to Cart</button>
      <a href="payment.html" class="btn-shop">🛍️ Buy with Shop</a>
    </div>

    <div class="desc-section">
      <h3>Please read product description</h3>
      <p class="desc-text">Our Light Plush Yarn offers a luxuriously soft and airy texture, designed to bring comfort and elegance to every stitch. Wonderfully fluffy yet easy to work with, it's ideal for creating plush toys, cozy blankets, scarves, and other heartfelt handmade pieces. This yarn works great for beginners and professionals alike.</p>

      <h3>YARN INFORMATION</h3>
      <table class="yarn-table">
        <tr><td>Fibre Content</td><td>100% polyester</td></tr>
        <tr><td>Weight</td><td>50g | 1.76oz</td></tr>
        <tr><td>Yarn length</td><td>55m | 60.1yd</td></tr>
        <tr><td>Recommended hook size</td><td>hook: 4–5mm &nbsp;|&nbsp; needle: 5–6mm</td></tr>
        <tr><td>Care instructions</td><td>Machine wash · Do not bleach · 70°C ironing · Dry clean · Dry flat</td></tr>
      </table>

      <div class="notes-box">
        <h4>⚠️ Notes (MUST READ!)</h4>
        <ul>
          <li>Colour may vary slightly from photos due to lighting and screen settings.</li>
          <li>Avoid using magic ring. Use a chain circle for a more secure start.</li>
          <li>It is normal that plush yarn may shed slightly.</li>
          <li>Colour may vary slightly from photos due to lighting and screen settings.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Recommendations -->
<div class="recs-section">
  <h2>You might also like</h2>
  <div class="recs-grid">
    <div class="rec-card">
      <div class="rec-img-wrap">
        <img src="Flowers that last forever_ 🌷💗.jpg" alt="Crochet Purse"/>
        <span class="sale-badge">Sale</span>
      </div>
      <div class="rec-info">
        <p class="rec-name">Cotton Crochet Purse 50g</p>
        <p class="rec-price">Rs 600.00 NPR</p>
      </div>
    </div>
    <div class="rec-card">
      <div class="rec-img-wrap">
        <img src="Unique Crochet Hat Ideas for Stylish and Cozy Looks.jpg" alt="Tulip Bouquet"/>
        <span class="sale-badge">Sale</span>
      </div>
      <div class="rec-info">
        <p class="rec-name">Tulip Flower Bouquet (10 flowers)</p>
        <p class="rec-price"><s>Rs. 4,500.00 NPR</s> <strong>Rs. 3,800.00 NPR</strong></p>
      </div>
    </div>
    <div class="rec-card">
      <div class="rec-img-wrap">
        <img src="Trailing Pothos Vine in Crochet Spilling from a Hanging Crochet Pot.jpg" alt="Headkerchief"/>
        <span class="sale-badge">Sale</span>
      </div>
      <div class="rec-info">
        <p class="rec-name">Crochet Headkerchief</p>
        <p class="rec-price"><s>Rs. 2,200.00 NPR</s> <strong>Rs. 1,900.00 NPR</strong></p>
      </div>
    </div>
    <div class="rec-card">
      <div class="rec-img-wrap">
        <img src="DM her for order.jpg" alt="Slouch Hat"/>
        <span class="sale-badge">Sale</span>
      </div>
      <div class="rec-info">
        <p class="rec-name">Crochet Slouch Hat</p>
        <p class="rec-price"><s>Rs. 5,000.00 NPR</s> <strong>Rs. 4,800.00 NPR</strong></p>
      </div>
    </div>
  </div>
</div>

<!-- Reviews -->
<div class="reviews-section">
  <div class="reviews-inner">
    <div class="reviews-header">
      <div class="overall-score">5.0</div>
      <div class="overall-right">
        <div class="stars">★★★★★</div>
        <p>Based on 15 reviews</p>
      </div>
      <button class="btn-review">Write a review</button>
    </div>
    <div class="reviews-grid">
      <div class="review-card">
        <div style="display:flex;justify-content:space-between"><span class="reviewer-name">Millie Brown</span><span class="review-date">07/17/2025</span></div>
        <div class="review-stars">★★★★★</div>
        <p class="review-text">This is amazing. Its soft, cozy and just great and its also high quality and very AFFORDABLE which I love to keep going. Girly ur the best, ur the reason why this is I love crochet!!</p>
      </div>
      <div class="review-card">
        <div style="display:flex;justify-content:space-between"><span class="reviewer-name">Dove Cameron Boyce</span><span class="review-date">01/09/2026</span></div>
        <div class="review-stars">★★★★☆</div>
        <p class="review-text">I love your yarn SO much. Usually buying things from underrated channels is SO expensive but yours is just so cheap!! love it.</p>
      </div>
      <div class="review-card">
        <div style="display:flex;justify-content:space-between"><span class="reviewer-name">Mia M. Masterson</span><span class="review-date">01/26/2026</span></div>
        <div class="review-stars">★★★★★</div>
        <p class="review-text">Its my hobby to crochet. I love the quality of your yarns and designs you crocheted. 🧶💕</p>
      </div>
      <div class="review-card">
        <div style="display:flex;justify-content:space-between"><span class="reviewer-name">Emma Goodshow</span><span class="review-date">12/06/2025</span></div>
        <div class="review-stars">★★★★★</div>
        <p class="review-text">Hello, I'm Emma Goodshow. And I love your videos and you creativity skills. ❤️</p>
      </div>
    </div>
  </div>
</div>
<div class="bottom">
  <p>Made with Love and Care by Yarnify &copy; 2025</p>
   </div>

</body>
</html>
