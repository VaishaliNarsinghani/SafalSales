
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart - Safal Sales</title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <style>
    
    /* Cart count badge */
    .cart-count {
      background: #d6681aff;
      color: #fff;
      font-size: 12px;
      font-weight: bold;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
    }

    /* Empty cart message */
    .empty-cart {
      text-align: center;
      padding: 50px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .empty-cart h2 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #333;
    }

    .empty-cart p {
      color: #777;
      margin-bottom: 20px;
    }

    .empty-cart .browse-btn {
      display: inline-block;
      padding: 10px 20px;
      background: #d6681aff;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.3s;
    }

    .empty-cart .browse-btn:hover {
      background: #e03e00;
    }
    .checkout-btn {
  width: 100%;
  background: #d6681aff;
  color: #fff;
  border: none;
  padding: 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.checkout-btn:hover { background: #d6681aff; }

.checkout-btn.success {
  background: #28a745 !important; /* Green */
}

  </style>
<style>
 .navbar {
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 5px;
      background: #fff; /* you can change */
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .navbar .logo span {
      font-size: 40px;
      font-weight: 900;
      color: #d6681aff; /* matches your logo */
      letter-spacing: -1px;
    }

    .navbar .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .navbar .nav-links li a {
      text-decoration: none;
      font-weight: 700;
      font-size: 20px;
      color: #636161ff;
      transition: color 0.3s ease, transform 0.2s ease;
    }

    .navbar .nav-links li a:hover,
    .navbar .nav-links li a.active {
      color: #d6681aff;
      transform: scale(1.05);
    }

    .menu-toggle {
      font-size: 26px;
      background: none;
      border: none;
      cursor: pointer;
    }
    /* Navbar Layout */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 18px;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  position: relative;
  z-index: 10;
}

/* Right Controls (Translate + Admin) */
.right-controls {
  display: flex;
  align-items: center;
  gap: 18px;
}

.translate-btn {
  background: linear-gradient(45deg, #ff6a00, #ff4500);
  border-radius: 20px;
  padding: 4px 10px;
  color: #fff;
  font-size: 13px;
  box-shadow: 0 4px 10px rgba(255, 102, 0, 0.4);
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}
.translate-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 15px rgba(255, 102, 0, 0.6);
}

/* Hide default Google Translate UI */
.goog-te-banner-frame.skiptranslate,
.goog-te-gadget-icon,
.goog-te-gadget-simple {
  display: none !important;
}
/* Stealthy Admin Link */
.admin-link {
  text-decoration: none;
  color: transparent;          /* make invisible */
  font-weight: 600;
  font-size: 15px;
  background-image: linear-gradient(90deg, #d6681a 0%, #fff 100%);
  background-size: 0% 2px;
  background-repeat: no-repeat;
  background-position: bottom left;
  transition: all 0.3s ease;
  user-select: none;           /* prevents accidental selection */
  opacity: 0.05;               /* barely visible */
}

/* When hovered, show it */
.admin-link:hover {
  opacity: 1;
  color: #d6681a;
  background-size: 100% 2px;
}

/* Hide Admin on Mobile */

/* Orange Splash */
/* Orange Splash */
.triangle-splash {
  position: absolute;
  top: 0;
  left: -100%;
  width: 55%;
  height: 60%;
  background: #d6681a;  /* solid orange */
  clip-path: polygon(0 0, 100% 0, 0 100%);
  z-index: 5;
  box-shadow: 5px 0 25px rgba(0,0,0,0.25);
  animation: slideInTriangle 1.5s cubic-bezier(0.77, 0, 0.175, 1) forwards;
  /* decorative crackers image */
  background-image: url("https://png.pngtree.com/png-clipart/20231004/original/pngtree-diwali-firecracker-clipart-cartoon-colorful-png-image_13230975.png");
  background-repeat: no-repeat;
  background-size: 220px;
  background-position: bottom right;
}

/* Fireworks Overlay */
.fireworks-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 90%;
  height: 70%;
  object-fit: cover;
  opacity: 0.85;
  animation: fireworksFade 3s ease-in-out infinite alternate;
  pointer-events: none; /* prevents blocking clicks */
}

/* Logo & text inside splash */
.triangle-content {
  position: absolute;
  top: 30%;
  transform: translateY(-50%);
  text-align: left;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  opacity: 0;
  animation: fadeInText 2s ease forwards;
  animation-delay: 1.2s;
  z-index: 2;
}

.triangle-content img {
  width: 120px;
  border-radius: 50%;
  margin-bottom: 1px;
  padding: 0px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.4);
}

.triangle-content h1 {
  font-size: 40px;
  font-weight: 400;
  color: #ffffff;
  text-shadow: 
    -2px -2px 0 #66310bff,  
     2px -2px 0 #d6681a,  
    -2px  2px 0 #572908ff,  
     2px  2px 0 #d6681a,  
     0px  0px 15px rgba(0,0,0,0.4);
  letter-spacing: -2px;
}


/* Animations */
@keyframes slideInTriangle {
  from { left: -100%; opacity: 0; }
  to { left: 0; opacity: 1; }
}

@keyframes fadeInText {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fireworksFade {
  from { opacity: 0.7; transform: scale(1); }
  to { opacity: 1; transform: scale(1.05); }
}

/* Hide on small screens */
@media (max-width: 768px) {
  .triangle-splash {
    display: none;
  }
}
.translate-switch {
  position: relative;
  display: inline-block;
  width: 70px;
  height: 30px;
}

/* Hide checkbox */
.translate-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* Slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background: #ff6a00;
  border-radius: 30px;
  transition: 0.4s;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 5px;
  font-weight: bold;
  color: white;
  font-family: 'Poppins', sans-serif;
}

/* EN/HI inside slider */
.lang {
  z-index: 2;
  font-size: 12px;
}

/* Slider circle */
.slider:before {
  content: "";
  position: absolute;
  height: 24px;
  width: 24px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.4s;
  z-index: 1;
}

/* Checked state */
input:checked + .slider {
  background: #4caf50;
}

input:checked + .slider:before {
  transform: translateX(40px);
}

.site-footer {
  width:100%;
  background-color: #222;
  color: #fff;
  text-align: center;
  padding: 20px 15px;
  font-family: 'Poppins', sans-serif;
  margin-top: 50px;
}

.site-footer a {
  color: #ff6a00;
  text-decoration: none;
}

.site-footer a:hover {
  text-decoration: underline;
}

.footer-content p {
  margin: 5px 0;
  font-size: 14px;
}
@media (max-width: 768px) {
  body {
    font-size: 14px;
  }

  .empty-cart h2 {
    font-size: 20px;
  }
  .checkout-btn {
    font-size: 14px;
    padding: 10px;
  }
}

/* ✅ For small phones (≤ 480px) */
@media (max-width: 768px) {
  .navbar {
    flex-wrap: wrap;
    padding: 2px 5px;
  }
  .logo span {
    font-size: 40px;
  }
  .menu-toggle {
    font-size: 18px;
    order: 3;
  }
  .right-controls {
    margin-left: 0;
    gap: 10px;
  }
}
.ticker{
  position: relative;
  width: 100%;
  background: #c1121f;              /* deep red */
  color: #ffeb3b;                    /* bright yellow */
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  letter-spacing: .2px;
  overflow: hidden;
  display: flex;
  align-items: stretch;
  min-height: 44px;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
  z-index: 20;                       /* below sticky navbar’s z-index but above content */
}

/* left ribbon notch */
.ticker-ribbon-left{
  width: 48px;
  background: #9b0f19;              /* darker red side */
  position: relative;
  flex: 0 0 auto;
}
.ticker-ribbon-left::after{
  content:"";
  position: absolute;
  right: -16px;
  top: 0;
  width: 0;
  height: 0;
  border-top: 22px solid transparent;
  border-bottom: 22px solid transparent;
  border-left: 16px solid #9b0f19;   /* little triangular notch */
}

/* moving area */
.ticker-track{
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  flex: 1 1 auto;
}

/* content that slides left */
.ticker-content{
  display: inline-flex;
  gap: 16px;
  white-space: nowrap;
  will-change: transform;
  animation: tickerScroll var(--ticker-speed, 30s) linear infinite;
  padding: 0 10px;
  align-items: center;
}

/* pause on hover (desktop) */
.ticker:hover .ticker-content{ animation-play-state: paused; }

/* text styling */
.ticker-content span{ 
  font-size: 15px; 
  line-height: 44px;
}
.ticker-content .sep { opacity: .65 }

/* control button (pause/play) */
.ticker-ctrl{
  flex: 0 0 auto;
  width: 44px;
  height: 44px;
  border: none;
  background: #a50f1a;
  color: #ffeb3b;
  font-size: 16px;
  cursor: pointer;
  display: grid;
  place-items: center;
  transition: background .2s ease;
}
.ticker-ctrl:hover{ background:#900c16 }

/* animation */
@keyframes tickerScroll{
  from{ transform: translateX(0) }
  to{   transform: translateX(-50%) } /* because we duplicated content once */
}

/* accessibility: users who prefer reduced motion */
@media (prefers-reduced-motion: reduce){
  .ticker-content{ animation: none }
}

/* responsive tweaks */
@media (max-width: 768px){
  .ticker-content span{ font-size: 14px; line-height: 40px }
  .ticker{ min-height: 40px }
  .ticker-ctrl{ width: 40px; height: 40px; font-size: 14px }
  .ticker-ribbon-left{ width: 40px }
  .ticker-ribbon-left::after{
    border-top-width: 20px; border-bottom-width: 20px; right:-14px; border-left-width:14px;
  }
}
@media (max-width: 480px){
  .ticker-content span{ font-size: 15px; line-height: 36px }
  .ticker{ min-height: 36px }
  .ticker-ctrl{ width: 36px; height: 36px; font-size: 13px }
  .ticker-ribbon-left{ width: 36px }
  .ticker-ribbon-left::after{
    border-top-width: 18px; border-bottom-width: 18px; right:-12px; border-left-width:12px;
  }
}
/* --- Z-index fix: keep navbar & dropdown above ticker --- */
:root{
  --z-navbar: 1000;
  --z-ticker: 900;
}

/* Navbar above everything else */
.navbar{
  position: sticky;      /* or relative if you prefer */
  top: 0;                /* sticky header */
  z-index: var(--z-navbar) !important;
}

/* Ticker sits below the navbar/dropdown */
.ticker{
  position: relative;
  z-index: var(--z-ticker) !important;
}

/* Mobile dropdown panel sits above ticker (and content) */
@media (max-width: 768px){
  .navbar .nav-links{
    position: absolute;
    left: 0; right: 0; top: 100%;
    z-index: calc(var(--z-navbar) + 1) !important;
    /* rest of your styles remain the same */
  }
}

/* Just in case any parent creates a stacking context, avoid clipping */
.navbar, .nav-links { overflow: visible; }

</style>
 <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
 <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  </head>
<body>

  <!-- ===== NAVBAR ===== -->
<nav class="navbar">
  <div class="logo" style="display: flex; align-items: center; gap: 10px;">
    <img src="safal sales logo.png" alt="Safal Sales Logo" style="height:55px; border-radius:70%;">
    <span>Safal Sales</span>
  </div>

  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li>
      <a href="cart.php" class="active">
        MyCart🛒<span id="cartCount" class="cart-count">0</span>
      </a>
    </li>
  </ul>

  <!-- Right Side Controls -->
<div class="right-controls">
  <a href="admin_dashboard.php" class="admin-link">Admin</a>
</div>

  <button class="menu-toggle" id="menuToggle" aria-label="Menu" aria-expanded="false">☰</button>
</nav>
   <?php
// Edit these messages as you like, or fetch from DB
$news = [
  "       ⭐ BOOK YOUR ORDER HERE ⭐",
  "⭐ AFTER MAKING ADVANCE PAYMENT ⭐",
  "⭐ STORE OPEN TILL 11 PM ALL WEEK ⭐",
  "⭐ COLLECT YOUR ORDER PACKET FROM SHOP NO. 04, TEMP. CRACKER MARKET CHHOTA BANGARDA ⭐",
  "⭐ FOR DETAILS CONTACT -> 94250 46826 ⭐",
  "⭐ COMBO PACKS AVAILABLE — LIMITED STOCK! ⭐"
];

// Build a single line separated by bullets
$tickerText = implode(' • ', array_map('htmlspecialchars', $news));
?>
<div class="ticker" role="region" aria-label="Latest announcements">
  <div class="ticker-ribbon-left" aria-hidden="true"></div>
  <div class="ticker-track">
    <!-- Duplicate text once for seamless loop -->
    <div class="ticker-content">
      <span><?= $tickerText ?></span>
      <span class="sep"> • </span>
      <span><?= $tickerText ?></span>
    </div>
  </div>
  <button class="ticker-ctrl" id="tickerCtrl" aria-label="Pause ticker" aria-pressed="false">❚❚</button>
</div>

  <!-- Cart Section -->
  <section class="cart-page">
    <h2>Your Cart</h2>
    <div class="cart-container">
      
      <!-- Cart Items -->
      <div class="cart-items" id="cartItems"></div>
      
<!-- Order Summary -->
<div class="order-summary">
<h3>Order Summary</h3>
<p>Subtotal: ₹<span id="subtotal">0.00</span></p>
<h4>Total: ₹<span id="total">0.00</span></h4> 
<h3>Enter Your Information</h3>
<form id="checkoutForm" action="save_invoice.php" method="post">
  <input type="hidden" name="cart" id="cartInput">
  <input type="hidden" name="total" id="totalInput">
  <input type="hidden" name="invoice_number" id="invoiceInput">

  <input type="text" name="name" id="name" placeholder="Your Name" required>
  <input type="tel" name="phone" id="phone" placeholder="Your Mobile Number" required>
  <textarea name="referred_person" id="referred_person" 
          placeholder="Referred Person Name or Mobile Number (Whatsapp)" required></textarea>

  <textarea name="address" id="address" placeholder="Your Delivery Address" required></textarea>

  <button type="submit" class="checkout-btn">📥 Download Order Sheet</button>
</form>

  
      </div>
    </div>
  </section>
  <div id="confirmModal" class="modal">
  <div class="modal-content">
    <h2>📥 Confirm Download</h2>
    <p>Do you want to generate and download your order sheet?</p>
    <div class="modal-buttons">
      <button id="confirmYes">✅ Yes, Download</button>
      <button id="confirmNo">❌ Cancel</button>
    </div>
  </div>
</div>
 <footer class="site-footer">
  <div class="footer-content">
     <p>Contact Number: <a href="tel:+919425046286">+91 94250 46286</a></p>
    <p>&copy; <?= date('Y') ?> Safal Sales. All rights reserved.</p>
  </div>
</footer>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: 'en,hi',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  // Put this at the very end of <body> OR load with `defer`
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menuToggle');
  const links  = document.querySelector('.nav-links');
  if (!toggle || !links) return; // nothing to do if markup missing

  toggle.addEventListener('click', () => {
  const open = links.classList.toggle('active');
  toggle.classList.toggle('open', open);
  toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  toggle.textContent = open ? '✖' : '☰';
});
  // Close menu after clicking a link (mobile UX)
  links.addEventListener('click', (e) => {
    if (e.target.closest('a')) {
      links.classList.remove('active');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.textContent = '☰';
    }
  });
});

</script>
<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

function updateCartCount() {
  let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  const cartCount = document.getElementById("cartCount");
  if (cartCount) cartCount.textContent = totalItems;
}

function renderCart() {
  const cartItemsDiv = document.getElementById("cartItems");
  cartItemsDiv.innerHTML = "";

  if (cart.length === 0) {
    cartItemsDiv.innerHTML = `
      <div class="empty-cart">
        <h2>🛒No Items</h2>
        <p>Looks like you haven’t added anything yet.</p>
        <a href="products.php" class="browse-btn">Browse Products</a>
      </div>
    `;
    document.getElementById("subtotal").innerText = "0.00";
    document.getElementById("total").innerText = "0.00";
    updateCartCount();
    return;
  }

  let subtotal = 0;
  cart.forEach((item, index) => {
    subtotal += item.price * item.qty;
    const div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML = `
      <img src="${item.img}" alt="${item.name}">
      <div>
        <h4>${item.name}</h4>
        <p class="price">₹${item.price}</p>
      </div>
      <div class="qty-controls">
        <button onclick="decreaseQty(${index})">-</button>
        <input type="number" id="qty-${index}" value="${item.qty}" readonly>
        <button onclick="increaseQty(${index})">+</button>
        <button onclick="removeItem(${index})" style="margin-left:10px;background:red;color:#fff;">x</button>
      </div>
    `;
    cartItemsDiv.appendChild(div);
  });

  document.getElementById("subtotal").innerText = subtotal.toFixed(2);
  document.getElementById("total").innerText = subtotal.toFixed(2);
  updateCartCount();
}

function increaseQty(i) {
  cart[i].qty++;
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}
function decreaseQty(i) {
  if (cart[i].qty > 1) {
    cart[i].qty--;
  } else {
    cart.splice(i, 1);
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}
function removeItem(i) {
  cart.splice(i, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}

// ========== CONFIRMATION MODAL ==========
document.addEventListener("DOMContentLoaded", () => {
  const form   = document.getElementById("checkoutForm");

  // Create modal dynamically
  const modal = document.createElement("div");
  modal.innerHTML = `
    <div id="confirmModal" class="modal">
      <div class="modal-content">
        <h2>📥 Confirm Download</h2>
        <p>Do you want to generate and download your order sheet?</p>
        <div class="modal-buttons">
          <button id="confirmYes">✅ Yes, download</button>
          <button id="confirmNo">❌ Cancel</button>
        </div>
      </div>
    </div>`;
  document.body.appendChild(modal);

  // Modal CSS
  const style = document.createElement("style");
  style.innerHTML = `
    .modal {position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.6);z-index:2000;}
    .modal.show{display:flex;}
    .modal-content{width:min(420px,92vw);background:#fff;padding:22px;border-radius:14px;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,.25);animation:popIn .25s ease;}
    .modal-content h2{margin:0 0 8px;color:#d6681aff;}
    .modal-content p{margin:0 0 18px;color:#333;}
    .modal-buttons{display:flex;gap:12px;justify-content:center;}
    .modal-buttons button{border:0;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer;}
    #confirmYes{background:#28a745;color:#fff;}
    #confirmNo{background:#dc3545;color:#fff;}
    @keyframes popIn{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
  `;
  document.head.appendChild(style);

  const confirmModal = document.getElementById("confirmModal");
  const yesBtn = document.getElementById("confirmYes");
  const noBtn  = document.getElementById("confirmNo");

  form.addEventListener("submit", (e) => {
    if (form.dataset.skipConfirm === "1") { 
      form.dataset.skipConfirm = "";
      return;
    }
    e.preventDefault();
    confirmModal.classList.add("show");
  });

  noBtn.addEventListener("click", () => {
    confirmModal.classList.remove("show");
  });

  yesBtn.addEventListener("click", () => {
    confirmModal.classList.remove("show");
    generateInvoiceAndSubmit();
  });

  function generateInvoiceAndSubmit() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const cols = [
      { x: 20,  w: 80 }, { x: 100, w: 30 },
      { x: 130, w: 20 }, { x: 150, w: 40 }
    ];
    const headerHeight = 10, cellPadding = 3, fontSize = 11, lineHeightFactor = 1.15, bottomLimit = 280;

    const invoiceNo = "INV-" + Math.floor(Math.random() * 900000 + 100000);
    const today = new Date().toLocaleDateString();

    doc.setFont("helvetica","bold"); doc.setFontSize(18);
    doc.text("Safal Sales Order Sheet", 105, 15, { align: "center" });
    doc.setFontSize(11); doc.setFont("helvetica","normal");
    doc.text("Safal Sales Pvt. Ltd.", 20, 30);
    doc.text("Phone: (123) 456-7890", 20, 48);
    doc.text(`Invoice #: ${invoiceNo}`, 150, 30);
    doc.text(`Date: ${today}`, 150, 36);

const name = document.getElementById("name").value;
const referred_person = document.getElementById("referred_person").value; // ✅ correct
const phone = document.getElementById("phone").value;
const address = document.getElementById("address").value;


    doc.setFont("helvetica","bold"); doc.text("Bill To:", 20, 65);
    doc.setFont("helvetica","normal");
    const addrLines = doc.splitTextToSize(address, 80);
    doc.text(name, 20, 72);
    doc.text(referred_person, 20, 78);
    doc.text(phone, 20, 84);
    doc.text(addrLines, 20, 90);

    // Table Header
    let startY = 110;
    doc.setFillColor(255,69,0); doc.setTextColor(255,255,255);
    doc.setFont("helvetica","bold"); doc.setFontSize(fontSize);
    cols.forEach(c => doc.rect(c.x, startY, c.w, headerHeight, "F"));
    doc.text("Item", cols[0].x + cellPadding, startY + headerHeight - 3);
    doc.text("Price", cols[1].x + cols[1].w/2, startY + headerHeight - 3, { align: "center" });
    doc.text("Qty", cols[2].x + cols[2].w/2, startY + headerHeight - 3, { align: "center" });
    doc.text("Total", cols[3].x + cols[3].w - cellPadding, startY + headerHeight - 3, { align: "right" });

    doc.setTextColor(0,0,0); doc.setFont("helvetica","normal");

    let y = startY + headerHeight + 4;
    let grandTotal = 0;

    function checkPageBreak(rowHeight) {
      if (y + rowHeight > bottomLimit) { doc.addPage(); y = 20; }
    }

    cart.forEach(item => {
      const nameLines = doc.splitTextToSize(String(item.name), cols[0].w - 2*cellPadding);
      const rowHeight = Math.max(headerHeight, (nameLines.length * fontSize * lineHeightFactor) + 2*cellPadding);
      checkPageBreak(rowHeight);
      cols.forEach(c => doc.rect(c.x, y, c.w, rowHeight));
      const textX = cols[0].x + cellPadding;
      const textY = y + cellPadding + fontSize - 1;
      nameLines.forEach((ln, idx) => doc.text(ln, textX, textY + idx * fontSize * lineHeightFactor));
      const yNum = y + (rowHeight/2) + (fontSize/2) - 2;
      doc.text(`${Number(item.price).toFixed(2)}`, cols[1].x + cols[1].w - cellPadding, yNum, { align: "right" });
      doc.text(String(item.qty), cols[2].x + (cols[2].w/2), yNum, { align: "center" });
      doc.text(`${(item.price * item.qty).toFixed(2)}`, cols[3].x + cols[3].w - cellPadding, yNum, { align: "right" });
      grandTotal += item.price * item.qty;
      y += rowHeight + 4;
    });

    // Grand Total
    doc.rect(cols[0].x, y, cols[0].w + cols[1].w + cols[2].w, headerHeight);
    doc.rect(cols[3].x, y, cols[3].w, headerHeight);
    doc.setFont("helvetica","bold");
    doc.text("Total Amount", cols[0].x + cellPadding, y + headerHeight - 3);
    doc.text(`${grandTotal.toFixed(2)}`, cols[3].x + cols[3].w - cellPadding, y + headerHeight - 3, { align: "right" });
    doc.setFontSize(10); doc.setFont("helvetica","italic");
    doc.text("(Thank you for your business!)", 105, 290, { align: "center" });

    doc.save(`invoice_${invoiceNo}.pdf`);

    // send data to server
    document.getElementById("invoiceInput").value = invoiceNo;
    document.getElementById("cartInput").value    = JSON.stringify(cart);
    document.getElementById("totalInput").value   = grandTotal.toFixed(2);

    form.dataset.skipConfirm = "1";
    form.submit();
  }
});

renderCart();
</script>

<script type="text/javascript">
let currentLang = 'en';

function setGoogleTranslate(lang) {
  const expire = new Date();
  expire.setTime(expire.getTime() + 365*24*60*60*1000);
  document.cookie = "googtrans=/en/" + lang + ";expires=" + expire.toUTCString() + ";path=/";
  document.cookie = "googtrans=/en/" + lang + ";expires=" + expire.toUTCString() + ";path=/";
  location.reload();
}

// Initialize toggle based on cookie
window.addEventListener('load', function() {
  const langCookie = document.cookie.match(/googtrans=\/en\/(hi|en)/);
  if(langCookie && langCookie[1] === 'hi') {
    document.getElementById('translate-toggle').checked = true;
    currentLang = 'hi';
  }
});

// Toggle change
document.getElementById('translate-toggle').addEventListener('change', function() {
  currentLang = this.checked ? 'hi' : 'en';
  setGoogleTranslate(currentLang);
});
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>
<script>
// optional: change speed by setting a CSS variable
// document.documentElement.style.setProperty('--ticker-speed', '25s');

(function(){
  const ctrl = document.getElementById('tickerCtrl');
  const content = document.querySelector('.ticker-content');
  if (!ctrl || !content) return;

  let paused = false;

  // Click to pause/resume
  ctrl.addEventListener('click', () => {
    paused = !paused;
    content.style.animationPlayState = paused ? 'paused' : 'running';
    ctrl.setAttribute('aria-pressed', paused ? 'true' : 'false');
    ctrl.textContent = paused ? '▶' : '❚❚';
  });

  // Touch: tap the ribbon area to pause (good on mobile)
  document.querySelector('.ticker-track')?.addEventListener('click', () => {
    // ignore if clicked pause button itself
    if (document.activeElement === ctrl) return;
    paused = !paused;
    content.style.animationPlayState = paused ? 'paused' : 'running';
    ctrl.setAttribute('aria-pressed', paused ? 'true' : 'false');
    ctrl.textContent = paused ? '▶' : '❚❚';
  });
})();
</script>
</body>
</html>
