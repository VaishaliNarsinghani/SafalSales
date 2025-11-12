<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include("db.php");

// Stats
$total = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$featured = $conn->query("SELECT COUNT(*) AS c FROM products WHERE featured=1")->fetch_assoc()['c'];
$lowstock = $conn->query("SELECT COUNT(*) AS c FROM products WHERE online_stock < 5")->fetch_assoc()['c'];

// Dummy recent activities (you can fetch from a log table later)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders - Safal Sales</title>
  <style>
    .recent-activities {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  margin-top: 20px;
}
.recent-activities h3 {
  margin-bottom: 15px;
  color: #d6681aff;
}
.recent-activities ul {
  list-style: none;
  padding: 0;
}
.recent-activities li {
  padding: 8px 0;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}
.recent-activities li:last-child { border-bottom: none; }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
    }

    /* ===== NAVBAR (top) ===== */
    .navbar {
      display: flex; justify-content: space-between; align-items: center;
      background: #fff; padding: 15px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      position: sticky; top:0; z-index:100;
    }
    .navbar .logo { font-size: 20px; font-weight: bold; color: #d6681aff; }
    .nav-links { list-style:none; display:flex; margin:0; padding:0; }
    .nav-links li { margin-left:20px; }
    .nav-links a {
      text-decoration:none; color:#333; font-weight:500; transition:0.3s;
    }
    .nav-links a:hover { color:#d6681aff; }

    /* ===== Layout ===== */
    .wrapper { display:flex; min-height:100vh; }

    /* Sidebar */
    .sidebar {
      width:250px; background:#fff; border-right:1px solid #eee;
      padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.05);
    }
    .sidebar h2 {
      color:#d6681aff; margin-bottom:20px; font-size:22px;
    }
    .sidebar a {
      display:block; padding:12px 15px; margin-bottom:10px;
      color:#333; text-decoration:none; border-radius:8px;
      font-weight:500; transition:0.2s;
    }
    .sidebar a:hover, .sidebar a.active {
      background:#d6681aff; color:#fff;
    }

    /* Main Content */
    .main {
      flex:1; padding:30px;
    }
    h2 { margin-top:0; margin-bottom:20px; color:#333; }

    /* Table */
    table {
      width:100%; border-collapse:collapse;
      background:#fff; border-radius:12px;
      overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.08);
    }
    table th, table td {
      padding:14px; border-bottom:1px solid #eee;
      text-align:left; font-size:14px;
    }
    table th {
      background:#f9f9f9; font-weight:bold;
    }
    table tr:hover { background:#fafafa; }
    a.download {
      color:#d6681aff; text-decoration:none; font-weight:bold;
    }
    a.download:hover { text-decoration:underline; }
    /* MAIN CONTENT */
.main {
  padding: 20px 30px;
  background: #f8f9fa;
  min-height: 100vh;
}

.main h1 {
  margin-bottom: 25px;
  color: #333;
  font-size: 28px;
}

/* Stats Section */
.main .stats {
  display: flex;
  gap: 20px;
  margin-bottom: 40px;
}

.main .card {
  flex: 1;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.2s, box-shadow 0.2s;
}

.main .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.main .card h2 {
  font-size: 18px;
  margin-bottom: 10px;
  color: #555;
}

.main .card span {
  font-size: 32px;
  font-weight: bold;
  color: #d6681aff;
}

/* Activities Section */
.main .activities {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.main .activities h3 {
  margin-bottom: 15px;
  font-size: 20px;
  color: #333;
}

.main .activity {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
  font-size: 15px;
  color: #444;
}

.main .activity:last-child {
  border-bottom: none;
}

.main .activity small {
  display: block;
  color: #888;
  font-size: 13px;
  margin-top: 5px;
}
.card-link {
  text-decoration: none;
  color: inherit;
  flex: 1; /* same as card */
}

.card-link .card {
  cursor: pointer;
}
.details-box {
  position: fixed;
  top: 50px;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  max-height: 70%;
  overflow-y: auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  z-index: 1000;
}

.details-box .close-btn {
  float: right;
  background: #d6681aff;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 5px 10px;
  cursor: pointer;
}

.details-box h3 {
  margin-top: 0;
  color: #d6681aff;
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
      font-size: 30px;
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
@media (max-width: 768px) {
  .admin-link {
    display: none;
  }
}

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

</style>
 <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
 <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
     <div class="logo" style="display: flex; align-items: center; gap: 10px;">
    <img src="safal sales logo.png" alt="Safal Sales Logo" style="height:55px; border-radius:70%;">
    <span>Safal Sales</span>
  </div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="cart.php">Cart 🛒 <span id="cartCount" class="cart-count">0</span></a></li>
      <li><a href="admin_dashboard.php" class="active">Admin</a></li>
      <li><a href="logout.php">🚪Logout</a></li>
    </ul>
    <div id="google_translate_element"></div>
  </nav>

  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Admin Panel</h2>
      <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
      <a href="admin_products.php">🛍 Products</a>
      <a href="admin_orders.php">📦 Orders</a>
      <a href="admin_check.php">✅ Order Check</a>
      <a href="logout.php">🚪 Logout</a>
    </div>

     <!-- Main Content -->
    <div class="main">
      <h1>Dashboard</h1>
    <div class="stats">
  <div class="card" onclick="showDetails('all')">
    <h2>Total Products</h2>
    <span><?= $total ?></span>
  </div>

  <div class="card" onclick="showDetails('featured')">
    <h2>Featured Products</h2>
    <span><?= $featured ?></span>
  </div>

  <div class="card" onclick="showDetails('lowstock')">
    <h2>Low Stock</h2>
    <span><?= $lowstock ?></span>
  </div>
</div>
<div id="detailsBox" class="details-box" style="display:none;">
  <button onclick="closeDetails()" class="close-btn">✖ Close</button>
  <h3 id="detailsTitle"></h3>
  <div id="detailsContent"></div>
</div>



    <?php
$activities = $conn->query("SELECT * FROM activities ORDER BY created_at DESC LIMIT 10");
?>

<div class="recent-activities">
  <h3>Recent Activities</h3>
  <ul>
    <?php while($row = $activities->fetch_assoc()): ?>
      <li>
        <?= htmlspecialchars($row['action']) ?> 
        <small style="color:gray;">(<?= $row['created_at'] ?>)</small>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
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
<script>
  function addToCart(name, price, img) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // ✅ Ensure price is number
    price = parseFloat(price);

    // ✅ Get qty from input inside same product card
    const card = event.target.closest(".product-card");
    let qtyInput = card.querySelector("input[type='number']");
    let qty = parseInt(qtyInput.value) || 1;

    // ✅ Check if already in cart
    let existing = cart.find(item => item.name === name);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ name, price, qty, img });
    }

    // ✅ Save to localStorage
    localStorage.setItem("cart", JSON.stringify(cart));

    // ✅ Update cart badge live
    updateCartCount();

    alert(`${name} added to cart!`);
  }

  function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);

    const cartCount = document.getElementById("cartCount");
    if (cartCount) {
      cartCount.textContent = totalItems > 0 ? totalItems : 0;
    }
  }

  // ✅ Sync cart badge across multiple tabs/pages
  window.addEventListener("storage", function(e) {
    if (e.key === "cart") {
      updateCartCount();
    }
  });

  // ✅ Update badge when page loads
  document.addEventListener("DOMContentLoaded", updateCartCount);
</script>
<script src="cart.js"></script>
<script>
function showDetails(type) {
    const box = document.getElementById('detailsBox');
    const title = document.getElementById('detailsTitle');
    const content = document.getElementById('detailsContent');

    // Set title
    if(type === 'all') title.textContent = 'All Products';
    if(type === 'featured') title.textContent = 'Featured Products';
    if(type === 'lowstock') title.textContent = 'Low Stock Products';

    // Fetch filtered data from PHP via AJAX
    fetch(`admin_products_ajax.php?filter=${type}`)
      .then(res => res.text())
      .then(data => {
        content.innerHTML = data;
        box.style.display = 'block';
      });
}

function closeDetails() {
    document.getElementById('detailsBox').style.display = 'none';
}
</script>

</body>
</html>
