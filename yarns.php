<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yarns - Yarnify</title>
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
position: relative;
overflow: hidden;
}

/* .hero::before {
content: '🧶';
position: absolute;
font-size: 150px;
opacity: 0.08;
top: 10px;
left: 5%;
animation: rotate 20s linear infinite;
}

.hero::after {
content: '🧶';
position: absolute;
font-size: 120px;
opacity: 0.08;
bottom: 20px;
right: 10%;
animation: rotate 15s linear infinite reverse;
} */

@keyframes rotate {
from { transform: rotate(0deg); }
to { transform: rotate(360deg); }
}

.hero h1 {
font-size: 3.5rem;
color: #e0adbd;
margin-bottom: 20px;
font-family: "Pacifico", cursive;
position: relative;
z-index: 1;
}

.hero p {
font-size: 1.2rem;
color: #8a70cb;
max-width: 700px;
margin: 0 auto;
position: relative;
z-index: 1;
}

/* Filter Section */
.filter-section {
max-width: 1400px;
margin: 40px auto;
padding: 0 40px;
}

.filter-row {
display: flex;
gap: 20px;
justify-content: space-between;
align-items: center;
flex-wrap: wrap;
margin-bottom: 40px;
}

.filter-group {
display: flex;
gap: 10px;
flex-wrap: wrap;
}

.filter-btn {
padding: 10px 25px;
background:  #ffe8f0;
border: 3px solid #ffe8f0;
border-radius: 50px;
color:  #b57c8a;
font-family: "Lilita One", sans-serif;
font-size: 0.95rem;
cursor: pointer;
transition: all 0.3s ease;
}

.filter-btn:hover,
.filter-btn.active {
background: #d6a5b3;
color: white;
transform: translateY(-2px);
}

.yarns-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
gap: 30px;
max-width: 1400px;
margin: 0 auto 80px;
padding: 0 40px;
}

.yarn-card {
background: white;
border-radius: 20px;
overflow: hidden;
box-shadow: 0 10px 30px rgba(138, 112, 203, 0.15);
transition: transform 0.3s ease, box-shadow 0.3s ease;
cursor: pointer;
position: relative;
}

.yarn-card:hover {
transform: translateY(-8px);
box-shadow: 0 15px 40px rgba(138, 112, 203, 0.25);
}

.yarn-image {
width: 100%;
height: 250px;
object-fit: cover;
position: relative;
}

.yarn-colors {
display: flex;
gap: 8px;
padding: 15px 20px;
background: linear-gradient(135deg, #c6c6c6, #c2c2c2);
}

.color-swatch {
width: 35px;
height: 35px;
border-radius: 50%;
border: 3px solid white;
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
cursor: pointer;
transition: transform 0.2s ease;
}

.color-swatch:hover {
transform: scale(1.15);
}

.yarn-content {
padding: 20px;
}

.yarn-brand {
color: #b57c8a;
font-size: 0.85rem;
margin-bottom: 8px;
text-transform: uppercase;
letter-spacing: 1px;
}

.yarn-content h3 {
color: #8a70cb;
font-size: 1.3rem;
margin-bottom: 12px;
}

.yarn-specs {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 10px;
margin-bottom: 15px;
font-size: 0.9rem;
}

.yarn-spec {
display: flex;
align-items: center;
gap: 6px;
color: #614c37;
}

.yarn-spec i {
color: #d6608a;
font-size: 0.85rem;
}

.yarn-price {
font-size: 1.4rem;
color: #614c37;
font-weight: bold;
margin-bottom: 15px;
}

.yarn-stock {
display: inline-block;
padding: 4px 12px;
background: #5cdb95;
color: white;
border-radius: 20px;
font-size: 0.8rem;
margin-bottom: 15px;
}

.yarn-stock.low {
background: #ffaa5c;
}

.yarn-stock.out {
background: #ff6b6b;
}

.add-cart-btn {
width: 100%;
padding: 12px;
/* background: linear-gradient(135deg, #ff8fb1, #d36fd0); */
background: #d6a5b3;
color: white;
border: none;
border-radius: 50px;
font-family: "Lilita One", sans-serif;
font-size: 1rem;
cursor: pointer;
transition: transform 0.2s ease;
}

.add-cart-btn:hover {
transform: scale(1.05);
}

.add-cart-btn:disabled {
background: #cccccc;
cursor: not-allowed;
}

/* Info Banner */
.info-banner {
background: #dcfac5;
padding: 40px;
text-align: center;
margin: 60px 40px;
border-radius: 25px;
border: 3px solid #ccfda6;
}

.info-banner h2 {
color: #8a70cb;
font-size: 2rem;
margin-bottom: 15px;
font-family: "Pacifico", cursive;
}

.info-banner p {
color: #614c37;
font-size: 1.1rem;
line-height: 1.6;
}

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
.yarns-grid {
    grid-template-columns: 1fr;
}

.hero h1 {
    font-size: 2.5rem;
}

.filter-row {
    flex-direction: column;
    align-items: stretch;
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
                        <li><a href="patterns.php">Patterns</a></li>
                        <li><a href="yarns.php" class="active">Yarns</a></li>
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

    <section class="hero">
        <h1>Premium Yarns</h1>
        <p>Soft, colorful, and perfect for any project! Discover our curated collection of high-quality yarns.</p>
    </section>


    <section class="filter-section">
        <div class="filter-row">
            <div class="filter-group" id="typeFilters">
                <button class="filter-btn active" data-type="all">All Yarns</button>
                <button class="filter-btn" data-type="acrylic">Acrylic</button>
                <button class="filter-btn" data-type="cotton">Cotton</button>
                <button class="filter-btn" data-type="wool">Wool</button>
                <button class="filter-btn" data-type="blend">Blend</button>
            </div>
        </div>
        <div class="filter-group" id="weightFilters">
            <button class="filter-btn active" data-weight="all">All Weights</button>
            <button class="filter-btn" data-weight="light">Light/Fingering</button>
            <button class="filter-btn" data-weight="medium">Medium/Worsted</button>
            <button class="filter-btn" data-weight="bulky">Bulky/Chunky</button>
        </div>
    </section>

    <section class="yarns-grid" id="yarnsGrid">
    </section>

    <section class="info-banner">
        <h2>Yarn Care Guide 💕</h2>
        <p>All our yarns come with detailed care instructions. We recommend hand washing in cold water and laying flat to dry for best results. Different fiber contents may have specific care needs - check the label on each product!</p>
    </section>

    <footer>
        <p>Made with Love and Care by Yarnify © 2025</p>
    </footer>

    <script>
        // Yarn data
        const yarns = [
            {
                id: 1,
                name: "Soft Cotton DK",
                brand: "lilthings",
                type: "cotton",
                weight: "medium",
                price: 280,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#FFB6C1", "#87CEEB", "#FFE4B5", "#E6E6FA", "#98FB98"],
                specs: {
                    weight: "DK / Light Worsted",
                    fiber: "100% Cotton",
                    length: "250m / 100g",
                    care: "Machine Washable"
                },
                image: "https://i.pinimg.com/736x/fd/d6/48/fdd648250a9bcf9d66a605958f844366.jpg"
            },
            {
                id: 2,
                name: "Chunky Wool Roving",
                brand: "Woolly Wonders",
                type: "wool",
                weight: "bulky",
                price: 520,
                stock: "Low Stock",
                stockLevel: "low",
                colors: ["#D2691E", "#F5DEB3", "#FFE4E1", "#E0E0E0"],
                specs: {
                    weight: "Bulky / Chunky",
                    fiber: "100% Merino Wool",
                    length: "80m / 100g",
                    care: "Hand Wash Only"
                },
                image: "https://i.pinimg.com/1200x/78/a3/95/78a395b4098ab5c27b2fee082a806c17.jpg"
            },
            {
                id: 3,
                name: "Rainbow Acrylic",
                brand: "ColorPop",
                type: "acrylic",
                weight: "medium",
                price: 180,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#FF6B9D", "#FFA500", "#FFD700", "#32CD32", "#4169E1", "#9370DB"],
                specs: {
                    weight: "Worsted / Medium",
                    fiber: "100% Acrylic",
                    length: "300m / 100g",
                    care: "Machine Washable"
                },
                image: "https://i.pinimg.com/1200x/0c/0a/7f/0c0a7fcc1da775c3c8a76c56c1a6cf79.jpg"
            },
            {
                id: 4,
                name: "Baby Soft Blend",
                brand: "Little Loops",
                type: "blend",
                weight: "light",
                price: 350,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#FFC0CB", "#B0E0E6", "#FFFACD", "#E6E6FA"],
                specs: {
                    weight: "Fingering / Baby",
                    fiber: "50% Cotton 50% Acrylic",
                    length: "400m / 100g",
                    care: "Gentle Machine Wash"
                },
                image: "https://i.pinimg.com/1200x/45/ce/8f/45ce8ff33a19def6daa18cf258d17546.jpg"
            },
            {
                id: 5,
                name: "Organic Cotton",
                brand: "EcoYarn",
                type: "cotton",
                weight: "medium",
                price: 420,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#F5F5DC", "#D2B48C", "#BC8F8F", "#696969"],
                specs: {
                    weight: "DK / Light Worsted",
                    fiber: "100% Organic Cotton",
                    length: "220m / 100g",
                    care: "Machine Washable"
                },
                image: "https://www.woolyn.com/cdn/shop/files/Bloom1_1600x.jpg"
            },
            {
                id: 6,
                name: "Super Bulky Acrylic",
                brand: "Yarnspirations",
                type: "acrylic",
                weight: "bulky",
                price: 320,
                stock: "Out of Stock",
                stockLevel: "out",
                colors: ["#2F4F4F", "#708090", "#778899"],
                specs: {
                    weight: "Super Bulky",
                    fiber: "100% Acrylic",
                    length: "100m / 200g",
                    care: "Machine Washable"
                },
                image: "https://m.media-amazon.com/images/I/81Z4P7XqiOL.jpg"
            },
            {
                id: 7,
                name: "Alpaca Blend Luxe",
                brand: "ANDEANSUN",
                type: "blend",
                weight: "medium",
                price: 680,
                stock: "Low Stock",
                stockLevel: "low",
                colors: ["#8B7355", "#D2B48C", "#F5DEB3"],
                specs: {
                    weight: "Worsted / Medium",
                    fiber: "60% Alpaca 40% Wool",
                    length: "180m / 100g",
                    care: "Hand Wash"
                },
                image: "https://andeansunyarns.com/wp-content/uploads/2019/10/IMG_6575-1.jpg"
            },
            {
                id: 8,
                name: "Mercerized Cotton",
                brand: "Shine Yarns",
                type: "cotton",
                weight: "light",
                price: 390,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#FF1493", "#00CED1", "#FFD700", "#FF6347", "#9370DB"],
                specs: {
                    weight: "Sport / Fine",
                    fiber: "100% Mercerized Cotton",
                    length: "350m / 100g",
                    care: "Machine Washable"
                },
                image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRh8ZMfyjbgZNFw_EorCDYEeep1a2EWX2paLQ&s"
            },
            {
                id: 9,
                name: "Washable Wool",
                brand: "Brava",
                type: "wool",
                weight: "medium",
                price: 450,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#8B0000", "#006400", "#00008B", "#8B008B"],
                specs: {
                    weight: "Aran / Worsted",
                    fiber: "100% Superwash Wool",
                    length: "200m / 100g",
                    care: "Machine Washable"
                },
                image: "https://d2q9kw5vp0we94.cloudfront.net/i/w=1000,h=1000,try=_crochet,v=1/yarnlistthumb/5420219.jpg~w=300"
            },
            {
                id: 10,
                name: "Bamboo Silk Blend",
                brand: "Luxury Line",
                type: "blend",
                weight: "light",
                price: 580,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#E6E6FA", "#FFB6C1", "#B0E0E6", "#FAFAD2"],
                specs: {
                    weight: "Fingering / Lace",
                    fiber: "70% Bamboo 30% Silk",
                    length: "420m / 100g",
                    care: "Hand Wash"
                },
                image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVLOYUXRivYA2tJzOUMn5CTg1L_pKlf8lLBQ&s"
            },
            {
                id: 11,
                name: "Variegated Acrylic",
                brand: "Patons",
                type: "acrylic",
                weight: "medium",
                price: 220,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#FF69B4", "#87CEEB", "#98FB98", "#DDA0DD"],
                specs: {
                    weight: "Worsted / Medium",
                    fiber: "100% Acrylic",
                    length: "280m / 100g",
                    care: "Machine Washable"
                },
                image: "https://www.thewoolqueen.ca/cdn/shop/products/yarn-patons-canadiana-pretty-baby-variegated-11420-1-057355335356-patons-the-wool-queen-5770380116079_1000x1000.jpg"
            },
            {
                id: 12,
                name: "Jumbo Chenille",
                brand: "Velvet Touch",
                type: "acrylic",
                weight: "bulky",
                price: 480,
                stock: "In Stock",
                stockLevel: "high",
                colors: ["#F0E68C", "#FFB6C1", "#E0E0E0", "#ADD8E6"],
                specs: {
                    weight: "Jumbo / Super Bulky",
                    fiber: "100% Polyester Chenille",
                    length: "120m / 250g",
                    care: "Hand Wash"
                },
                image: "https://becozi.net/cdn/shop/products/1A2DF714-0118-4453-B9AB-57A265491817_2048x2048.jpg"
            }
        ];

        // Generate yarn cards
        function generateYarns(filteredYarns) {
            const grid = document.getElementById('yarnsGrid');
            grid.innerHTML = '';

            filteredYarns.forEach(yarn => {
                const card = document.createElement('div');
                card.className = 'yarn-card';

                const stockClass = yarn.stockLevel;
                const isOutOfStock = yarn.stockLevel === 'out';

                card.innerHTML = `
                    <img src="${yarn.image}" alt="${yarn.name}" class="yarn-image">
                    <div class="yarn-colors">
                        ${yarn.colors.map(color => `<div class="color-swatch" style="background-color: ${color}" title="${color}"></div>`).join('')}
                    </div>
                    <div class="yarn-content">
                        <div class="yarn-brand">${yarn.brand}</div>
                        <h3>${yarn.name}</h3>
                        <div class="yarn-specs">
                            <div class="yarn-spec">
                                <i class="fa-solid">•</i>
                                <span>${yarn.specs.weight}</span>
                            </div>
                            <div class="yarn-spec">
                                <i class="fa-solid">•</i>
                                <span>${yarn.specs.length}</span>
                            </div>
                            <div class="yarn-spec">
                                <i class="fa-solid">•</i>
                                <span>${yarn.specs.fiber}</span>
                            </div>
                            <div class="yarn-spec">
                                <i class="fa-solid">•</i>
                                <span>${yarn.specs.care}</span>
                            </div>
                        </div>
                        <div class="yarn-price">NPR ${yarn.price}</div>
                        <div class="yarn-stock ${stockClass}">${yarn.stock}</div>
                        <button class="add-cart-btn" ${isOutOfStock ? 'disabled' : ''} onclick="addToCart(${yarn.id})">
                            <i class="fa-solid fa-cart-shopping"></i> ${isOutOfStock ? 'Out of Stock' : 'Add to Cart'}
                        </button>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        // Initial load
        generateYarns(yarns);

        // Filter by type
        const typeFilters = document.querySelectorAll('#typeFilters .filter-btn');
        let activeType = 'all';
        let activeWeight = 'all';
        let searchQuery = '';

        typeFilters.forEach(button => {
            button.addEventListener('click', function() {
                typeFilters.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                activeType = this.getAttribute('data-type');
                applyFilters();
            });
        });

        // Filter by weight
        const weightFilters = document.querySelectorAll('#weightFilters .filter-btn');

        weightFilters.forEach(button => {
            button.addEventListener('click', function() {
                weightFilters.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                activeWeight = this.getAttribute('data-weight');
                applyFilters();
            });
        });

        // Apply all filters
        function applyFilters() {
            let filtered = yarns;

            // Filter by type
            if (activeType !== 'all') {
                filtered = filtered.filter(y => y.type === activeType);
            }

            // Filter by weight
            if (activeWeight !== 'all') {
                filtered = filtered.filter(y => y.weight === activeWeight);
            }

            // Filter by search
            if (searchQuery) {
                filtered = filtered.filter(y => 
                    y.name.toLowerCase().includes(searchQuery) ||
                    y.brand.toLowerCase().includes(searchQuery) ||
                    y.specs.fiber.toLowerCase().includes(searchQuery)
                );
            }

            generateYarns(filtered);
        }

        // Add to cart function
        function addToCart(yarnId) {
            const yarn = yarns.find(y => y.id === yarnId);
            alert(`Added "${yarn.name}" to your cart!\n\nPrice: NPR ${yarn.price}\nFiber: ${yarn.specs.fiber}`);
        }
    </script>

</body>
</html>