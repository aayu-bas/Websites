
// --------ImageSlider---------------
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

// --------------DropDown Menu------------
const userIcon = document.getElementById("userIcon");
const dropdownMenu= document.getElementById("dropdownMenu");

userIcon.addEventListener("click",()=>{
  dropdownMenu.classList.toggle("show-menu");
});


// --------------ProductSlider------------
function closeModal(){
  document.getElementById("cartModal").style.display="none";
}
function slide(dir,btn){
  const carousel=btn.closest('.wrapper').querySelector('.carousel');
  carousel.scrollBy({left:250*dir, behavior: 'smooth'});
}
function buy(product){
  document.getElementById("productname").innerText=product;
  document.getElementById("cartModal").style.display="block";
}

function checkout(){
  window.open("checkout.html",'_blank');
}

function result(){
  window.location.href="moreresult.html";
}
//-----------------chatbot-----------------
const toggle = document.getElementById('chatbotToggle');
    const chatWindow = document.getElementById('chatbotWindow');
    const messagesContainer = document.getElementById('chatbotMessages');
    const input = document.getElementById('chatbotInput');
    const typingIndicator = document.getElementById('typingIndicator');

    // Toggle chatbot
    toggle.addEventListener('click', () => {
       chatWindow.classList.toggle('active');
      toggle.classList.toggle('active');
      if (chatWindow.classList.contains('active')) {
        input.focus();
      }
    });

    // Send message
    function sendMessage() {
      const message = input.value.trim();
      if (!message) return;

      // Add user message
      addMessage(message, 'user');
      input.value = '';

      // Show typing indicator
      typingIndicator.classList.add('active');
      scrollToBottom();

      // Simulate bot response
      setTimeout(() => {
        typingIndicator.classList.remove('active');
        const response = getBotResponse(message);
        addMessage(response, 'bot');
      }, 1000 + Math.random() * 1000);
    }

    // Handle Enter key
    function handleKeyPress(event) {
      if (event.key === 'Enter') {
        sendMessage();
      }
    }

    // Quick reply
    function sendQuickReply(message) {
      addMessage(message, 'user');
      
      typingIndicator.classList.add('active');
      scrollToBottom();

      setTimeout(() => {
        typingIndicator.classList.remove('active');
        const response = getBotResponse(message);
        addMessage(response, 'bot');
      }, 1000);
    }

    // Add message to chat
    function addMessage(text, sender) {
      const messageDiv = document.createElement('div');
      messageDiv.className = `message ${sender}`;
      
      const avatar = document.createElement('div');
      avatar.className = 'message-avatar';
      avatar.textContent = sender === 'bot' ? '🧸' : '🍯';
      
      const content = document.createElement('div');
      content.className = 'message-content';
      content.textContent = text;
      
      messageDiv.appendChild(avatar);
      messageDiv.appendChild(content);
      
      messagesContainer.insertBefore(messageDiv, typingIndicator);
      scrollToBottom();
    }

    // Scroll to bottom
    function scrollToBottom() {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Simple bot responses
    function getBotResponse(message) {
      const lowerMessage = message.toLowerCase();
      
      if (lowerMessage.includes('free') || lowerMessage.includes('pattern')) {
        return "Check out our Free Patterns section! We have tons of beginner-friendly patterns including amigurumi, blankets, and accessories.";
      }
      
      if (lowerMessage.includes('beginner') || lowerMessage.includes('start') || lowerMessage.includes('learn')) {
        return "Starting crochet is easy! I recommend beginning with a basic chain stitch and single crochet. Check our Beginner Tutorials for step-by-step videos.";
      }
      
      if (lowerMessage.includes('yarn') || lowerMessage.includes('wool')) {
        return "For beginners, I recommend medium-weight (worsted) acrylic yarn. It's affordable, easy to work with, and comes in lots of colors!";
      }
      
      if (lowerMessage.includes('hook')) {
        return "A 5mm (H/8) crochet hook is perfect for beginners! It works well with worsted weight yarn.";
      }
      
      if (lowerMessage.includes('help') || lowerMessage.includes('support')) {
        return "I'm here to help! You can also contact our support team via email or check out our FAQ section.";
      }
      
      if (lowerMessage.includes('hi') || lowerMessage.includes('hello') || lowerMessage.includes('hey')) {
        return "Hello! How can I help you with your crochet journey today?";
      }
      
      if (lowerMessage.includes('thank')) {
        return "You're welcome! Happy crocheting!";
      }
      
      return "That's a great question! You can browse our tutorials, patterns, or contact our team for personalized help. Is there anything specific you'd like to know?";
    }


// ----------------Products Carousel-------------
const productSections = {
    "AMIGURUMI": [
        { name: "Teddy Bear Pattern", price: 350, img: "photos/teddy_bear.png" },
        { name: "Mini Bunny",price: 300, img: "https://i.pinimg.com/736x/4b/e1/33/4be133db9cce150caad41855cba8f5e0.jpg" },
        { name: "Octopus Plushie",price: 280, img: "https://i.pinimg.com/1200x/a9/53/e8/a953e80ad0baa8209996bd3322cb4bd5.jpg" },
        { name: "Chibi Dinosaur",price: 400, img: "https://i.pinimg.com/736x/ac/3e/cb/ac3ecbf8c65ee15544322522c2cfdd9b.jpg" },
        { name: "Baby Penguin", price: 350, img: "https://i.pinimg.com/736x/15/9c/8f/159c8f130bb7829bbf7a58bedaa5ad8a.jpg" },
        { name: "Strawberry Rabbit", price: 420, img: "https://i.pinimg.com/1200x/a0/03/bf/a003bff7bf7d6f46377a50ec9c0494a0.jpg" },
    ],
    "WEARABLES": [
        { name: "Cardigan",price: 850,  img: "https://i.pinimg.com/1200x/0f/82/57/0f8257472925b4d0a400302c589ac6f5.jpg" },
        { name: "Beanie",price: 630,  img: "https://i.pinimg.com/736x/81/40/2d/81402dc55a453669c37ac32b7f8e15e2.jpg" },
        { name: "Aesthetic Bag",price: 1120, img: "https://i.pinimg.com/736x/69/36/9d/69369dc3fd6b48ded4184fae610aa1d1.jpg" },
        { name: "Miffy Handwarmers", price: 400,  img: "https://i.pinimg.com/736x/9e/67/49/9e67497e1734a42ef8df1034b7e24994.jpg" },
        { name: "Misasa Scarf", price: 950,  img: "https://i.pinimg.com/736x/1d/db/cf/1ddbcf33212f947608f4fb414cbd8600.jpg" },
        { name: "Crop Top",price: 600,  img: "https://i.pinimg.com/736x/21/25/0d/21250dd68a735fc886e0959a9a4c44db.jpg" },
    ],
    "DECORS": [
        { name: "Coaster",price: 250, img: "https://i.pinimg.com/736x/15/b3/e2/15b3e2567add28930f7c1279325e7eaf.jpg" },
        { name: "Flower pot hanger",price: 580, img: "https://i.pinimg.com/736x/c1/13/9d/c1139d377ed65ac61edec6dee6b679ec.jpg" },
        { name: "Curtain Tieback",price: 320, img: "https://i.pinimg.com/736x/de/b5/e0/deb5e02d6363b4f13f0a230cdbbf35dd.jpg" },
        { name: "Table wear",price: 450, img: "https://i.pinimg.com/736x/62/2a/58/622a58c216a5e68d4e7c3be213fa858a.jpg" },
        { name: "Tulip Pot",price: 290, img: "https://i.pinimg.com/736x/f1/89/8f/f1898f3e772aa92d44df9655f5bc997c.jpg" },
        { name: "Star Pillow",price: 410, img: "https://i.pinimg.com/736x/df/2a/83/df2a839660237977f6af10b98395a528.jpg" },
    ],
    "CHARACTERS": [
        { name: "Totoro",price: 500, img: "https://i.pinimg.com/1200x/c9/b1/0f/c9b10f6efa49b810d46a21aef5cf4f0f.jpg" },
        { name: "Pikachu",price: 480, img: "https://i.pinimg.com/1200x/b5/23/92/b52392c4dda4e267f769b2db6ce69d0d.jpg" },
        { name: "Kirby",price: 460, img: "https://i.pinimg.com/736x/b9/58/cd/b958cdb70afad08878fb849b4a220494.jpg" },
        { name: "Hello Kitty",price: 520, img: "https://i.pinimg.com/1200x/25/60/96/25609668617851a9f986ab734e6a01bd.jpg" },
        { name: "Stitch",price: 490, img: "https://i.pinimg.com/1200x/6a/25/2b/6a252ba9a039fc27f489f81e3cf00251.jpg" },
        { name: "Baby Yoda",price: 550, img: "https://i.pinimg.com/1200x/a8/11/6b/a8116b087d797998fbd90819596402ac.jpg" },
    ],
    "KEYCHAINS": [
        { name: "Strawberry", price: 150, img: "https://i.pinimg.com/736x/d5/19/b2/d519b211f9a7e0be3f9a7a44c8d98227.jpg" },
        { name: "Mini Bear", price: 180, img: "https://i.pinimg.com/1200x/32/ae/fe/32aefef3faa1a4a7a7684e579eded03d.jpg" },
        { name: "Heart Charm", price: 220, img: "https://i.pinimg.com/736x/3c/02/8b/3c028bb88f3e3e6a776b4839e7357e1d.jpg" },
        { name: "Ghost",price: 160, img: "https://i.pinimg.com/1200x/59/41/da/5941da8f801d342a7a51f05e49541b4e.jpg" },
        { name: "Flower",price: 140, img: "https://i.pinimg.com/736x/ac/a8/52/aca852a44f821e14c98e39d043a4ff52.jpg" },
        { name: "Star Cluster",price: 170, img: "https://i.pinimg.com/736x/2e/f0/58/2ef058a189d61a26d1390b15e0702737.jpg"},
    ],
};
 
// -------- Carousel Builder --------
function buildProductSections() {
    const container = document.getElementById("product-sections");
 
    for (const [title, products] of Object.entries(productSections)) {
        const heading = document.createElement("h2");
        heading.innerHTML = `${title} <span class="pointer" onclick="result()">&#8594;</span>`;
        container.appendChild(heading);
 
        const wrapper = document.createElement("div");
        wrapper.className = "wrapper";
 
        const prevBtn = document.createElement("button");
        prevBtn.className = "nav prev";
        prevBtn.textContent = "❮";
        prevBtn.setAttribute("onclick", "slide(-1, this)");
 
        const carousel = document.createElement("div");
        carousel.className = "carousel";
 
        products.forEach(product => {
            const card = document.createElement("div");
            card.className = "product-card";
            card.innerHTML = `
                <img src="${product.img}" alt="${product.name}">
                <div class="info">
                    <h4>${product.name}</h4>
                    <p class="price">NPR ${product.price}</p>
                    <button class="buy-btn" onclick="buy('${product.name}')">Add to Cart</button>
                </div>`;
            carousel.appendChild(card);
        });
 
        const nextBtn = document.createElement("button");
        nextBtn.className = "nav next";
        nextBtn.textContent = "❯";
        nextBtn.setAttribute("onclick", "slide(1, this)");
 
        wrapper.appendChild(prevBtn);
        wrapper.appendChild(carousel);
        wrapper.appendChild(nextBtn);
        container.appendChild(wrapper);
    }
}
 
buildProductSections();
// -------- Product Carousel Scroll --------
function slide(dir, btn) {
    const carousel = btn.closest(".wrapper").querySelector(".carousel");
    carousel.scrollBy({ left: 250 * dir, behavior: "smooth" });
}

// -------- Cart Modal --------
function buy(product) {
    document.getElementById("productname").innerText = product;
    document.getElementById("cartModal").style.display = "block";
}
 
function closeModal() {
    document.getElementById("cartModal").style.display = "none";
}
 
function checkout() {
    window.open("checkout.html", "_blank");
}
 
function result() {
    window.location.href = "moreresult.html";
}
// ========== Search products ==========
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

// Combine all products from productSections for search
let allProducts = [];
Object.keys(productSections).forEach(category => {
  productSections[category].forEach(product => {
    allProducts.push({
      ...product,
      category: category
    });
  });
});

// Search as user types
searchInput.addEventListener('input', function() {
  const query = this.value.toLowerCase().trim();
  
  // Hide results if search is empty
  if (query === '') {
    searchResults.classList.remove('active');
    return;
  }
  
  // Filter products based on search query
  const filtered = allProducts.filter(product => 
    product.name.toLowerCase().includes(query) || 
    product.category.toLowerCase().includes(query)
  );
  
  // Display results
  if (filtered.length > 0) {
    searchResults.innerHTML = filtered.map(product => `
      <div class="search-result-item" onclick="selectSearchProduct('${product.name}', '${product.category}')">
        <h4>${product.name}</h4>
        <p>${product.category}</p>
      </div>
    `).join('');
    searchResults.classList.add('active');
  } else {
    searchResults.innerHTML = '<div class="no-results">No products found!</div>';
    searchResults.classList.add('active');
  }
});

// Function to handle product selection from search
function selectSearchProduct(productName, category) {
  // Redirect to results page with query
  window.location.href = `moreresult.html?search=${encodeURIComponent(productName)}&category=${encodeURIComponent(category)}`;

  // Clear search
  searchInput.value = '';
  searchResults.classList.remove('active');
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.search-container')) {
    searchResults.classList.remove('active');
  }
});