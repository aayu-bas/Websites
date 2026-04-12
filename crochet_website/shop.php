<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Yarnify</title>
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
  min-width: 140px;
  font-family: "Lilita One", sans-serif;
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

.shop-banner{
  width:100%;
  height:370px;
  background: 
  linear-gradient(rgba(255,255,255,0.4), rgba(255,255,255,0.4)),
  url("photos/shop_banner.png");
  background-size: cover;  
  background-position: center; 
  background-repeat: no-repeat;
  display:flex;
  align-items:center;
  justify-content:flex-start;
  padding-left:60px;
  box-shadow: 5px 5px 10px #888888;
}

#essential{
  font-size:42px;
  color:#c08497;
  margin-top:10px;
}
.banner-text p{
  font-size:18px;
  color:#5a4a4a;
  margin-top:10px;
  line-height:1.5;
}

/* ===== Category Tabs ===== */
.categories {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 15px;
  margin: 40px 20px;
}

.category-btn {
  padding: 12px 22px;
  font-family:"Lilita One", sans-serif;
  border-radius: 25px;
  border: none;
  background: #ffe8f0;
  color: #b57c8a;
  cursor: pointer;
  transition: 0.3s;
  font-size: 16px;
}

.category-btn:hover,
.category-btn.active {
  background: #d6a5b3;
  color: white;
  transform: translateY(-3px);
}

.products {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  padding: 20px 40px;
}

.product {
  background: #fff7f9;
  padding: 15px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  transition: 0.3s;
}

.product:hover {
  transform: translateY(-8px);
}

.product img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
}

.product h4 {
  margin: 10px 0;
  color: #b57c8a;
}

.product p {
  color: #614c37;
}

.buy-btn {
  margin-top: 10px;
  font-family: "Lilita One", sans-serif;
  background: #d6a5b3;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  color: white;
  cursor: pointer;
}

.buy-btn:hover {
  background: #b57c8a;
}
.category-section {
  margin: 20px;
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
        <li><a href="shop.php" class="active" style="background:#ffffb7;">Shop</a></li>
        <li><a href="patterns.php">Patterns</a></li>
        <li><a href="yarns.php">Yarns</a></li>
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

<!-- ===== Banner ===== -->
<div class="shop-banner">
  <div class="banner-text">
    <p id="essential">Crochet Essentials</p>
    <p>
      Everything you need for your crochet journey.<br>
      Create something magical.
    </p>
  </div>
</div>

<!-- ===== Category Tabs ===== -->
<div class="categories">
  <button class="category-btn active" onclick="filterProducts('all')">All</button>
  <button class="category-btn" onclick="filterProducts('hooks')">Hooks</button>
  <button class="category-btn" onclick="filterProducts('needles')">Needles</button>
  <button class="category-btn" onclick="filterProducts('markers')">Stitch Markers</button>
  <button class="category-btn" onclick="filterProducts('eyes')">Safety Eyes</button>
  <button class="category-btn" onclick="filterProducts('keychains')">Keychains</button>
</div>

<!-- ===== Multiple Sections for Each Category ===== -->
<div class="category-section" id="section-all">
  <div class="products" id="products-all"></div>
</div>

<div class="category-section" id="section-hooks" style="display:none;">
  <div class="products" id="products-hooks"></div>
</div>

<div class="category-section" id="section-needles" style="display:none;">
  <div class="products" id="products-needles"></div>
</div>

<div class="category-section" id="section-markers" style="display:none;">
  <div class="products" id="products-markers"></div>
</div>

<div class="category-section" id="section-eyes" style="display:none;">
  <div class="products" id="products-eyes"></div>
</div>

<div class="category-section" id="section-keychains" style="display:none;">
  <div class="products" id="products-keychains"></div>
</div>

<footer>
  <p>Made with Love and Care by Yarnify © 2025 🧶✨</p>
</footer>
<script>
// Sample products
const products = [
  {name:"Crochet Hook Set", category:"hooks", price:500, img:"https://i.pinimg.com/1200x/61/ce/c9/61cec99d6afa98b8082da7491c5c3081.jpg"},
  {name:"Yarn Needles", category:"needles", price:200, img:"https://i.pinimg.com/1200x/a5/88/f0/a588f07e865ff8ae074919dfe52c59f2.jpg"},
  {name:"Stitch Markers Pack", category:"markers", price:150, img:"https://i.pinimg.com/1200x/5b/b5/ce/5bb5ce92c010a56f4284f3e18aeb501b.jpg"},
  {name:"Safety Eyes Set", category:"eyes", price:250, img:"https://i.pinimg.com/736x/1f/07/9e/1f079ea85573b21151701f08d9f3c063.jpg"},
  {name:"Cute Keychains", category:"keychains", price:180, img:"https://i.pinimg.com/1200x/98/34/26/98342629b3e8d60c40a8453fb6981986.jpg"},
  
  {name:"Ergonomic Hook", category:"hooks", price:700, img:"https://i.pinimg.com/1200x/52/ae/7a/52ae7a88677676b5a031cc1955870ac3.jpg"},
  {name:"Plastic Needles", category:"needles", price:180, img:"https://images.squarespace-cdn.com/content/v1/5a721e6acf81e0929e9f64c8/1642711501648-0KUIL81BM61QQY5HDVVQ/H393711affe274212b4ea78bcf0f3b851D.jpg"},
  {name:"Stitch Markers Deluxe", category:"markers", price:220, img:"https://i.pinimg.com/1200x/92/10/61/921061d6e1241a837b103e68231da77c.jpg"},
  {name:"Large Safety Eyes", category:"eyes", price:300, img:"https://i.pinimg.com/1200x/f5/48/19/f54819964f3226e2cfca70f8e942b918.jpg"},
  {name:"Heart Keychains", category:"keychains", price:200, img:"https://i.pinimg.com/1200x/04/91/22/049122f968ac7956a5376f68709117ef.jpg"},
];

// Function to populate a specific section
function populateSectionProducts(sectionId, productsList) {
  const container = document.getElementById(sectionId);
  container.innerHTML = productsList.map(p => `
    <div class="product">
      <img src="${p.img}">
      <h4>${p.name}</h4>
      <p>NPR ${p.price}</p>
      <button class="buy-btn">Add to Cart</button>
    </div>
  `).join("");
}

// Initial load: show all products
populateSectionProducts('products-all', products);
document.getElementById('section-all').style.display = 'block';

// Filter function
function filterProducts(category) {
  // Hide all sections
  document.querySelectorAll('.category-section').forEach(sec => sec.style.display = 'none');

  // Remove active class from all buttons
  document.querySelectorAll(".category-btn").forEach(btn => btn.classList.remove("active"));

  // Add active class to clicked button
  event.target.classList.add("active");

  if (category === "all") {
    document.getElementById('section-all').style.display = 'block';
    category.style.fontFamily ="Lilita One, sans-serif";
    populateSectionProducts('products-all', products);
  } else if (category === "hooks") {
    document.getElementById('section-hooks').style.display = 'block';
    populateSectionProducts('products-hooks', products.filter(p => p.category === 'hooks'));
  } else if (category === "needles") {
    document.getElementById('section-needles').style.display = 'block';
    populateSectionProducts('products-needles', products.filter(p => p.category === 'needles'));
  } else if (category === "markers") {
    document.getElementById('section-markers').style.display = 'block';
    populateSectionProducts('products-markers', products.filter(p => p.category === 'markers'));
  } else if (category === "eyes") {
    document.getElementById('section-eyes').style.display = 'block';
    populateSectionProducts('products-eyes', products.filter(p => p.category === 'eyes'));
  } else if (category === "keychains") {
    document.getElementById('section-keychains').style.display = 'block';
    populateSectionProducts('products-keychains', products.filter(p => p.category === 'keychains'));
  }
}
</script>

</body>
</html>