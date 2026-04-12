<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patterns - Yarnify</title>
    <link rel="icon" href="yarnify.png">
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
/* background: linear-gradient(135deg, #fff5f7 0%, #fff9e6 100%); */
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
  color: #614c37;
  font-family: "Lilita One", sans-serif;
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

/* Hero */
.hero {
text-align: center;
padding: 80px 20px 60px;
background: linear-gradient(135deg, #ffeef5 0%, #fff9e6 100%);
box-shadow: 5px 5px 10px 0px rgba(255, 192, 203, 0.5);
}

.hero h1 {
font-size: 3.5rem;
color: #e0adbd;
margin-bottom: 20px;
font-family: "Pacifico", cursive;
}

.hero p {
font-size: 1.2rem;
color: #8a70cb;
max-width: 600px;
margin: 0 auto;
}

/* Filter Section */
.filter-section {
max-width: 1200px;
margin: 40px auto;
padding: 0 40px;
}

.filter-buttons {
display: flex;
gap: 15px;
justify-content: center;
flex-wrap: wrap;
margin-bottom: 50px;
}

.filter-btn {
padding: 12px 30px;
 background: #ffe8f0;
color: #b57c8a;
border: 3px solid #ffe8f0;
border-radius: 50px;
font-family: "Lilita One", sans-serif;
font-size: 1rem;
cursor: pointer;
transition: all 0.3s ease;
}

.filter-btn:hover,
.filter-btn.active {
background: #d6a5b3;
color: white;
transform: translateY(-3px);
}

/* Patterns Grid */
.patterns-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
gap: 35px;
max-width: 1200px;
margin: 0 auto 80px;
padding: 0 40px;
}

.pattern-card {
background: white;
border-radius: 20px;
overflow: hidden;
box-shadow: 0 10px 30px rgba(138, 112, 203, 0.15);
transition: transform 0.3s ease, box-shadow 0.3s ease;
cursor: pointer;
position: relative;
}

.pattern-card:hover {
transform: translateY(-8px);
box-shadow: 0 15px 40px rgba(138, 112, 203, 0.25);
}


.pattern-badge.free {
background: #5cdb95;
}

.pattern-badge.premium {
background: #8d6cf7;
}

.pattern-image {
width: 100%;
height: 280px;
object-fit: cover;
}

.pattern-content {
padding: 25px;
}

.pattern-category {
color: #b57c8a;
font-size: 0.9rem;
margin-bottom: 8px;
text-transform: uppercase;
letter-spacing: 1px;
}

.pattern-content h3 {
color: #8a70cb;
font-size: 1.4rem;
margin-bottom: 12px;
font-weight: normal;
}

.pattern-content p {
color: #614c37;
line-height: 1.6;
margin-bottom: 15px;
font-size: 0.95rem;
}

.pattern-meta {
display: flex;
gap: 20px;
margin-bottom: 15px;
font-size: 0.9rem;
color: #9c5db0;
}

.pattern-meta span {
display: flex;
align-items: center;
gap: 5px;
}

.pattern-price {
font-size: 1.3rem;
color: #614c37;
margin-bottom: 15px;
}

.pattern-price.free {
color: #90c081;
}

.download-btn {
width: 100%;
padding: 12px;
background:#90c081;
color: white;
border: none;
border-radius: 50px;
font-family: "Lilita One", sans-serif;
font-size: 1rem;
cursor: pointer;
transition: transform 0.2s ease;
}

.download-btn:hover {
transform: scale(1.05);
}

.download-btn.free-btn {
background: #90c081;
}

.pattern-card.hidden {
display: none;
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
@media (max-width: 768px) {
.patterns-grid {
    grid-template-columns: 1fr;
}

.hero h1 {
    font-size: 2.5rem;
}

.filter-buttons {
    gap: 10px;
}

.filter-btn {
    padding: 10px 20px;
    font-size: 0.9rem;
}
}
</style>
</head>
<body>
<div id="header">
    <div class="container">
        <nav class="navbar">
            <div class="navbar-left">
                <img src="yarnify.png" alt="logo" class="logo">
                <p class="brand-name">Yarnify</p>
            </div>
            <div id="tabs">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="patterns.php" class="active" style="background:#ffffb7;">Patterns</a></li>
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

    <!-- Hero -->
    <section class="hero">
        <h1>Crochet Patterns</h1>
        <p>Download beautiful patterns for your next project! From beginner-friendly to advanced designs. ✨</p>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Patterns</button>
            <button class="filter-btn" data-filter="free">Free Patterns</button>
            <button class="filter-btn" data-filter="premium">Premium</button>
            <button class="filter-btn" data-filter="amigurumi">Amigurumi</button>
            <button class="filter-btn" data-filter="wearables">Wearables</button>
            <button class="filter-btn" data-filter="home-decor">Home Decor</button>
        </div>
    </section>

    <section class="patterns-grid" id="patternsGrid"></section>

    <footer>
        <p>Made with Love and Care by Yarnify © 2025</p>
    </footer>

    <script>
        const patterns = [
            {
                id: 1,
                title: "Teddy Bear Amigurumi",
                category: "Amigurumi",
                description: "Adorable teddy bear perfect for beginners. Includes step-by-step photos and video tutorial link.",
                difficulty: "Beginner",
                time: "4-6 hours",
                price: "FREE",
                type: "free",
                image: "photos/teddy_bear.png"
            },
            {
                id: 2,
                title: "Cozy Cardigan",
                category: "Wearables",
                description: "Stylish oversized cardigan with pockets. Available in sizes XS-3XL with detailed sizing guide.",
                difficulty: "Intermediate",
                time: "15-20 hours",
                price: "NPR 450",
                type: "premium",
                image: "https://i.pinimg.com/1200x/0f/82/57/0f8257472925b4d0a400302c589ac6f5.jpg"
            },
            {
                id: 3,
                title: "Coaster Set",
                category: "Home Decor",
                description: "Bright and cheerful coaster set. Pattern includes 4 coasters with different flower designs.",
                difficulty: "Beginner",
                time: "2-3 hours",
                price: "FREE",
                type: "free",
                image: "https://i.pinimg.com/1200x/25/47/de/2547decf2b6ac9219f98c14d201318d5.jpg"
            },
            {
                id: 4,
                title: "Bunny",
                category: "Amigurumi",
                description: "Complete family of bunnies - mama, papa, and baby. Perfect gift for Easter or baby showers!",
                difficulty: "Intermediate",
                time: "8-12 hours",
                price: "NPR 380",
                type: "premium",
                image: "https://i.pinimg.com/1200x/43/57/67/435767b6e55eeb610e92c4a1e6b08397.jpg"
            },
            {
                id: 5,
                title: "Wall Hanger",
                category: "Home Decor",
                description: "Greeny creepers perfect for nurseries. Includes macramé tassels tutorial.",
                difficulty: "Beginner",
                time: "3-4 hours",
                price: "FREE",
                type: "free",
                image: "https://i.pinimg.com/736x/5e/18/f5/5e18f5e576aa2edeaf6b5c29b4202979.jpg"
            },
            {
                id: 6,
                title: "Bucket Hat",
                category: "Wearables",
                description: "Trendy bucket hat in multiple sizes. Perfect for summer festivals and beach days!",
                difficulty: "Intermediate",
                time: "5-7 hours",
                price: "NPR 320",
                type: "premium",
                image: "https://i.pinimg.com/736x/09/02/1f/09021fed52bc49253671ce0a157b9f8c.jpg"
            },
            {
                id: 7,
                title: "Mini Octopus",
                category: "Amigurumi",
                description: "Cute reversible octopus with happy and grumpy faces. Great stress relief toy!",
                difficulty: "Beginner",
                time: "2-3 hours",
                price: "FREE",
                type: "free",
                image: "https://i.pinimg.com/736x/f6/a8/54/f6a854f14134044c381a20f702266c1d.jpg"
            },
            {
                id: 8,
                title: "Tote Bag",
                category: "Wearables",
                description: "Sturdy tote bag perfect for groceries and everyday use. Eco-friendly alternative to plastic!",
                difficulty: "Beginner",
                time: "6-8 hours",
                price: "FREE",
                type: "free",
                image: "https://i.pinimg.com/736x/68/b5/4b/68b54b6f48e5f757be80d986b49100e5.jpg"
            },
            {
                id: 9,
                title: "Granny Square Blanket",
                category: "Home Decor",
                description: "Classic granny square blanket in modern colors. Includes 5 different square patterns.",
                difficulty: "Intermediate",
                time: "25-30 hours",
                price: "NPR 520",
                type: "premium",
                image: "https://i.pinimg.com/1200x/9f/5f/bd/9f5fbd365cb54f7257d2c2fbece12fd1.jpg"
            },
            {
                id: 10,
                title: "Dinosaur Plushie",
                category: "Amigurumi",
                description: "Adorable T-Rex plushie that stands on its own. Kids absolutely love this one!",
                difficulty: "Intermediate",
                time: "7-9 hours",
                price: "NPR 400",
                type: "premium",
                image: "https://i.pinimg.com/1200x/98/0d/d5/980dd5f4dc83bfde3cab246805fc6b52.jpg"
            },
            {
                id: 11,
                title: "Fingerless Gloves",
                category: "Wearables",
                description: "Cozy fingerless gloves with ribbed cuffs. Perfect for typing in cold weather!",
                difficulty: "Beginner",
                time: "3-4 hours",
                price: "FREE",
                type: "free",
                image: "https://i.pinimg.com/736x/6f/19/fa/6f19faf3447aadbae10c5bb7703dd6bd.jpg"
            },
            {
                id: 12,
                title: "Plant Hanger Trio",
                category: "Home Decor",
                description: "Set of 3 plant hangers in different lengths. Bring nature indoors with style!",
                difficulty: "Intermediate",
                time: "4-6 hours",
                price: "NPR 490",
                type: "premium",
                image: "https://i.pinimg.com/736x/9a/35/f9/9a35f9b02809ae9eb0a05742e2fbb340.jpg"
            }
        ];

        // Generate pattern cards
        function generatePatterns(filteredPatterns) {
            const grid = document.getElementById('patternsGrid');
            grid.innerHTML = '';

            filteredPatterns.forEach(pattern => {
                const card = document.createElement('div');
                card.className = 'pattern-card';
                card.setAttribute('data-category', pattern.category.toLowerCase().replace(' ', '-'));
                card.setAttribute('data-type', pattern.type);
                const badgeText = pattern.type === 'free' ? 'FREE' : 'PREMIUM';
                const priceClass = pattern.type === 'free' ? 'free' : '';
                const btnClass = pattern.type === 'free' ? 'free-btn' : '';

                card.innerHTML = `
                    
                    <img src="${pattern.image}" alt="${pattern.title}" class="pattern-image">
                    <div class="pattern-content">
                        <div class="pattern-category">${pattern.category}</div>
                        <h3>${pattern.title}</h3>
                        <p>${pattern.description}</p>
                        <div class="pattern-meta">
                            <span><i class="fa-regular fa-clock"></i> ${pattern.time}</span>
                        </div>
                        <div class="pattern-price ${priceClass}">${pattern.price}</div>
                        <button class="download-btn ${btnClass}" onclick="downloadPattern(${pattern.id})">
                            Download Pattern
                        </button>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        // Initial load - show all patterns
        generatePatterns(patterns);

        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');

                // Get filter value
                const filter = this.getAttribute('data-filter');

                // Filter patterns
                let filtered = patterns;
                
                if (filter === 'all') {
                    filtered = patterns;
                } else if (filter === 'free' || filter === 'premium') {
                    filtered = patterns.filter(p => p.type === filter);
                } else {
                    filtered = patterns.filter(p => 
                        p.category.toLowerCase().replace(' ', '-') === filter
                    );
                }

                // Regenerate grid
                generatePatterns(filtered);
            });
        });

        // Download pattern function
        function downloadPattern(patternId) {
            const pattern = patterns.find(p => p.id === patternId);
            if (pattern.type === 'free') {
                alert(`Downloading "${pattern.title}" pattern for FREE!\n\nCheck your downloads folder.`);
            } else {
                alert(`💳 Proceeding to checkout for "${pattern.title}"!\n\nPrice: ${pattern.price}`);
            }
        }
    </script>

</body>
</html>