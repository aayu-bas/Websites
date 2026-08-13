document.addEventListener('DOMContentLoaded', function() {
    if (typeof initFlashMessages === 'function') initFlashMessages();
    if (typeof initSearch === 'function') initSearch();
    initCartActions();
    initProductCards();
    initWishlistActions();
    initTabs();
});
// --------ImageSlider---------------
const slides = document.querySelectorAll('.slide');
const nextBtn = document.querySelector('.next');
const prevBtn = document.querySelector('.prev');

let currentSlide = 0;

function showSlide(index){

    slides.forEach(slide=>{
      slide.classList.remove('active');
    });

    slides[index].classList.add('active');
}

nextBtn.addEventListener('click',()=>{
    currentSlide++;
    if(currentSlide >= slides.length){
      currentSlide = 0;
    }
    showSlide(currentSlide);
});

prevBtn.addEventListener('click',()=>{
    currentSlide--;
    if(currentSlide < 0){
      currentSlide = slides.length - 1;
    }
    showSlide(currentSlide);
});

// contact form-------------------------
function toggleFaq(element) {
  const item = element.parentElement;
  const wasActive = item.classList.contains('active');

  // Close all
  document.querySelectorAll('.faq-item').forEach(faq => {
    faq.classList.remove('active');
  });

  // Open clicked if it wasn't active
  if (!wasActive) {
    item.classList.add('active');
  }
}

// Form validation enhancement
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
  const email = this.querySelector('input[name="email"]').value;
  const message = this.querySelector('textarea[name="message"]').value;

  if (!email.includes('@')) {
    e.preventDefault();
    alert('Please enter a valid email address');
    return;
  }
  if (message.length < 10) {
    e.preventDefault();
    alert('Please write a bit more — we love reading your messages! <3');
    return;
  }
});

// Flash Messages
function initFlashMessages() {
    const flashMessages = document.querySelectorAll('.flash-message');

    flashMessages.forEach(message => {
        // Auto-hide after 5 seconds
        setTimeout(() => {
            message.classList.add('hide');
            setTimeout(() => message.remove(), 400);
        }, 5000);

        // Click to dismiss
        message.addEventListener('click', function() {
            this.classList.add('hide');
            setTimeout(() => this.remove(), 400);
        });
    });
}
// Notification System
function showNotification(type, message) {
    const container = document.querySelector('.flash-messages') || createFlashContainer();

    const notification = document.createElement('div');
    notification.className = `flash-message ${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
    `;

    container.appendChild(notification);

    // Auto remove
    setTimeout(() => {
        notification.classList.add('hide');
        setTimeout(() => notification.remove(), 400);
    }, 5000);

    // Click to remove
    notification.addEventListener('click', function() {
        this.classList.add('hide');
        setTimeout(() => this.remove(), 400);
    });
}

function createFlashContainer() {
    const container = document.createElement('div');
    container.className = 'flash-messages';
    document.body.appendChild(container);
    return container;
}


// Product Cards - Quick Add to Cart
function initProductCards() {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return; // ignore clicks while a request is in flight
            const productId = this.dataset.productId;
            const qtyInput = document.querySelector('.quantity-input');
            const quantity = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
            addToCart(productId, quantity, this);
        });
    });
}

function getCartActionsPath() {
    return window.location.pathname.includes('/pages/') ? '../includes/' : 'includes/';
}

function addToCart(productId, quantity, btn) {
    if (btn) btn.disabled = true;

    fetch(getCartActionsPath() + 'cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            updateCartCount(data.cart_count);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Something went wrong!');
    })
    .finally(() => {
        if (btn) btn.disabled = false;
    });
}

// Update Cart Count
function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

// Wishlist Actions
function initWishlistActions() {
    document.querySelectorAll('.add-to-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            toggleWishlist(productId, this);
        });
    });
}

function toggleWishlist(productId, btn) {
    const basePath = window.location.pathname.includes('/pages/') ? 
    '../includes/' : 'includes/';
    fetch(basePath +'wishlist_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=toggle&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            btn.classList.toggle('active');
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-heart');
                icon.classList.toggle('fa-heart-o');
            }
            updateWishlistCount(data.wishlist_count);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Something went wrong!');
    });
}


// Cart Actions (quantity update, remove)
function initCartActions() {
    // Quantity controls
    document.querySelectorAll('.quantity-control').forEach(control => {
        const minusBtn = control.querySelector('.qty-minus');
        const plusBtn = control.querySelector('.qty-plus');
        const input = control.querySelector('input');

        if (minusBtn) {
            minusBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                if (val > 1) {
                    input.value = val - 1;
                    updateCartItem(input.dataset.itemId, val - 1);
                }
            });
        }

        if (plusBtn) {
            plusBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                input.value = val + 1;
                updateCartItem(input.dataset.itemId, val + 1);
            });
        }

        if (input) {
            input.addEventListener('change', () => {
                let val = parseInt(input.value) || 1;
                if (val < 1) val = 1;
                input.value = val;
                updateCartItem(input.dataset.itemId, val);
            });
        }
    });

    // Remove items
    document.querySelectorAll('.remove-cart-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.dataset.itemId;
            if (confirm('Remove this item from cart?')) {
                removeCartItem(itemId);
            }
        });
    });
}


function updateCartItem(itemId, quantity) {
    fetch(getCartActionsPath() + 'cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            updateCartTotal(data.total);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeCartItem(itemId) {
    fetch(getCartActionsPath() + 'cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=remove&item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-cart-item="${itemId}"]`);
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'translateX(100px)';
                setTimeout(() => item.remove(), 300);
            }
            updateCartCount(data.cart_count);
            updateCartTotal(data.total);
            showNotification('success', data.message);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartTotal(total) {
    const totalEl = document.querySelector('.cart-total-amount');
    if (totalEl) {
        totalEl.textContent = 'रु' + parseFloat(total).toFixed(2);
    }
}

//product.php
// product.php quantity selector (local only — no AJAX, just UI state before Add to Cart)
document.querySelectorAll(".detail-quantity-control").forEach(control => {
    const minusBtn = control.querySelector(".detail-qty-minus");
    const plusBtn = control.querySelector(".detail-qty-plus");
    const input = control.querySelector(".quantity-input");

    const updateButtons = () => {
        const value = parseInt(input.value);
        const min = parseInt(input.min);
        const max = parseInt(input.max);
        minusBtn.disabled = value <= min;
        plusBtn.disabled = value >= max;
    };

    plusBtn.addEventListener("click", () => {
        let value = parseInt(input.value);
        let max = parseInt(input.max);
        if (value < max) input.value = value + 1;
        updateButtons();
    });

    minusBtn.addEventListener("click", () => {
        let value = parseInt(input.value);
        let min = parseInt(input.min);
        if (value > min) input.value = value - 1;
        updateButtons();
    });

    input.addEventListener("input", () => {
        let value = parseInt(input.value) || 1;
        let min = parseInt(input.min);
        let max = parseInt(input.max);
        if (value < min) input.value = min;
        if (value > max) input.value = max;
        updateButtons();
    });

    updateButtons();
});

// Product Tabs
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;

            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
}
