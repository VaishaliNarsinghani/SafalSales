<?php
include("db.php");

// Fetch all products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Products - Safal Sales</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      box-sizing: border-box;
    }
    
    .products-page { 
      padding: 10px; 
      max-width: 1400px;
      margin: 0 auto;
    }
    
    .filters {
      display: flex; 
      justify-content: space-between; 
      align-items: center;
      margin-top: 70px;
      margin-bottom: 10px;
    }
    
    .filters input, .filters select {
      padding: 10px; 
      border: 1px solid #ccc; 
      border-radius: 6px; 
      font-size: 14px;
    }

    /* Product List - COMPACT LAYOUT */
    .product-grid { 
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding: 15px;
      width: 100%;
    }
    
    .product-card {
      background: linear-gradient(145deg, #fff3e0, #ffe0b2);
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      transition: transform .2s, box-shadow .2s;
      cursor: pointer;
      overflow: hidden;
      display: flex;
      align-items: center;
      height: 150px; /* ~1.5 inches */
      min-height: 120px;
      max-height: 150px;
      padding: 8px 12px;
      gap: 15px;
    }
    
    .product-card:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 5px 15px rgba(255,102,0,0.2); 
    }
    
    .product-image { 
      width: 100px;
      height: 100px;
      min-width: 100px;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8f8f8;
    }
    
    .product-image img { 
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .product-details {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      min-height: 100px;
      padding: 5px 0;
    }
    
    .product-info {
      flex: 1;
    }
    
    .product-details h3 { 
      font-size: 16px; 
      color: #ff6a00; 
      margin: 0 0 4px 0; 
      font-weight: 700;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    
    .product-details .category { 
      font-size: 12px; 
      color: #555; 
      margin-bottom: 4px;
      font-weight: 600;
    }
    
    .product-details .desc { 
      font-size: 11px; 
      color: #666; 
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 6px;
    }
    
    .product-details .price { 
      font-weight: bold; 
      color: #d32f2f; 
      font-size: 16px;
      margin: 0;
    }
    
    .actions { 
      display: flex; 
      align-items: center; 
      gap: 8px;
      margin-top: 8px;
    }
    
    .actions label { 
      font-size: 12px; 
      font-weight: 600;
      white-space: nowrap;
    }
    
    .qty-input { 
      width: 45px; 
      padding: 4px; 
      border-radius: 4px; 
      border: 1px solid #ccc; 
      font-size: 12px;
      text-align: center;
    }
    
    .add-to-cart {
      background: linear-gradient(45deg,#ff4500,#ff6a00); 
      border: none; 
      color: white; 
      padding: 6px 12px; 
      border-radius: 6px; 
      cursor: pointer; 
      font-weight: bold; 
      transition: all .2s; 
      font-size: 12px;
      white-space: nowrap;
    }
    
    .add-to-cart:hover { 
      background: linear-gradient(45deg,#ff6a00,#ff4500); 
      transform: scale(1.05); 
    }

    /* Compact Category Tabs */
    .category-tabs {
      display: flex; 
      justify-content: center; 
      flex-wrap: wrap; 
      gap: 6px; 
      margin-bottom: 15px; 
      margin-top: 50px;
    }
    
    .category-tabs .tab {
      padding: 6px 12px; 
      border: 1px solid #ccc; 
      border-radius: 15px; 
      background: #f9f9f9; 
      cursor: pointer; 
      transition: all .2s ease-in-out;
      font-size: 13px; 
      font-weight: 500; 
      white-space: nowrap;
    }
    
    .category-tabs .tab.active, .category-tabs .tab:hover { 
      background: #d6681aff; 
      color: #fff; 
      border-color: #d6681aff; 
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .product-grid { 
        gap: 10px;
        padding: 10px 5px;
      }
      
      .product-card {
        height: 120px;
        min-height: 110px;
        max-height: 150px;
        padding: 6px 10px;
        gap: 12px;
      }
      
      .product-image { 
        width: 90px;
        height: 90px;
        min-width: 90px;
      }
      
      .product-details h3 {
        font-size: 15px;
      }
      
      .product-details .desc {
        font-size: 10px;
        -webkit-line-clamp: 1;
      }
      
      .product-details .price {
        font-size: 15px;
      }
      
      .category-tabs { 
        gap: 4px; 
        margin-top: 30px;
      }
      
      .category-tabs .tab { 
        padding: 5px 10px; 
        font-size: 12px; 
      }
      
      .add-to-cart {
        padding: 5px 10px;
        font-size: 11px;
      }
      
      .qty-input {
        width: 40px;
        font-size: 11px;
      }
    }

    @media (max-width: 480px) {
      .product-card {
        height: 150px;
        min-height: 100px;
        max-height: 130px;
        padding: 2px 4px;
        gap: 10px;
      }
      
      .product-image { 
        width: 80px;
        height: 80px;
        min-width: 80px;
      }
      
      .product-details h3 {
        font-size: 14px;
        -webkit-line-clamp: 1;
      }
      
      .product-details .desc {
        display: none; /* Hide description on very small screens */
      }
      
      .actions {
        margin-top: 5px;
      }
    }

    /* No Results Message */
    .no-results {
      text-align: center;
      color: #555;
      margin-top: 20px;
      font-size: 16px;
      padding: 20px;
    }
  </style>

  <style>
    /* Navbar */
    .navbar {
      font-family: 'Poppins', sans-serif; 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      padding: 5px 5px;
      background: #fff; 
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .navbar .logo span { 
      font-size: 30px; 
      font-weight: 900; 
      color: #d6681aff; 
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
      transition: color .3s ease, transform .2s ease;
    }
    
    .navbar .nav-links li a:hover, .navbar .nav-links li a.active { 
      color: #d6681aff; 
      transform: scale(1.05); 
    }
    
    .menu-toggle { 
      font-size: 26px; 
      background: none; 
      border: none; 
      cursor: pointer; 
    }

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
      box-shadow: 0 4px 10px rgba(255,102,0,0.4); 
      transition: all .3s ease; 
      border: none; 
      cursor: pointer;
    }
    
    .translate-btn:hover { 
      transform: scale(1.05); 
      box-shadow: 0 6px 15px rgba(255,102,0,0.6); 
    }

    .goog-te-banner-frame.skiptranslate, .goog-te-gadget-icon, .goog-te-gadget-simple { 
      display: none !important; 
    }

    .admin-link {
      text-decoration: none; 
      color: transparent; 
      font-weight: 600; 
      font-size: 15px;
      background-image: linear-gradient(90deg, #d6681a 0%, #fff 100%); 
      background-size: 0% 2px; 
      background-repeat: no-repeat; 
      background-position: bottom left;
      transition: .3s; 
      user-select: none; 
      opacity: .05;
    }
    
    .admin-link:hover { 
      opacity: 1; 
      color: #d6681a; 
      background-size: 100% 2px; 
    }

    /* Mobile Navbar */
    @media (max-width: 768px) {
      .navbar {
        flex-wrap: wrap;
        padding: 5px 10px;
      }
      
      .nav-links {
        flex-direction: column;
        width: 100%;
        display: none;
        margin-top: 10px;
      }
      
      .nav-links.active {
        display: flex;
      }
      
      .menu-toggle {
        order: 3;
      }
      
      .right-controls {
        margin-left: 0;
        gap: 10px;
      }
    }
  </style>

  <style>
    /* Ticker */
    .ticker{
      position: relative; 
      width: 100%; 
      background: #c1121f; 
      color: #ffeb3b; 
      font-family: 'Poppins', sans-serif; 
      font-weight: 700;
      letter-spacing: .2px; 
      overflow: hidden; 
      display: flex; 
      align-items: stretch; 
      min-height: 44px; 
      box-shadow: 0 2px 8px rgba(0,0,0,.1); 
      z-index: 20;
    }
    
    .ticker-ribbon-left{ 
      width: 48px; 
      background: #9b0f19; 
      position: relative; 
      flex: 0 0 auto; 
    }
    
    .ticker-ribbon-left::after{
      content: ""; 
      position: absolute; 
      right: -16px; 
      top: 0; 
      width: 0; 
      height: 0; 
      border-top: 22px solid transparent; 
      border-bottom: 22px solid transparent; 
      border-left: 16px solid #9b0f19;
    }
    
    .ticker-track{ 
      position: relative; 
      overflow: hidden; 
      display: flex; 
      align-items: center; 
      flex: 1 1 auto; 
    }
    
    .ticker-content{ 
      display: inline-flex; 
      gap: 16px; 
      white-space: nowrap; 
      will-change: transform; 
      animation: tickerScroll var(--ticker-speed,30s) linear infinite; 
      padding: 0 10px; 
      align-items: center; 
    }
    
    .ticker:hover .ticker-content{ 
      animation-play-state: paused; 
    }
    
    .ticker-content span{ 
      font-size: 15px; 
      line-height: 44px; 
    }
    
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
    
    .ticker-ctrl:hover{ 
      background: #900c16; 
    }
    
    @keyframes tickerScroll{ 
      from{ transform: translateX(0) } 
      to{ transform: translateX(-50%) } 
    }
    
    @media (max-width: 768px){
      .ticker{ min-height: 40px } 
      .ticker-content span{ line-height: 40px } 
      .ticker-ctrl{ width: 40px; height: 40px; font-size: 14px }
      .ticker-ribbon-left{ width: 40px } 
      .ticker-ribbon-left::after{ border-top-width: 20px; border-bottom-width: 20px; right: -14px; border-left-width: 14px; }
    }
    
    @media (max-width: 480px){
      .ticker{ min-height: 36px } 
      .ticker-content span{ line-height: 36px } 
      .ticker-ctrl{ width: 36px; height: 36px; font-size: 13px }
      .ticker-ribbon-left{ width: 36px } 
      .ticker-ribbon-left::after{ border-top-width: 18px; border-bottom-width: 18px; right: -12px; border-left-width: 12px; }
    }

    /* Z-index guards */
    :root{ --z-navbar: 1000; --z-ticker: 900; }
    .navbar{ position: sticky; top: 0; z-index: var(--z-navbar) !important; }
    .ticker{ position: relative; z-index: var(--z-ticker) !important; }
    @media (max-width: 768px){
      .navbar .nav-links{ position: absolute; left: 0; right: 0; top: 100%; z-index: calc(var(--z-navbar) + 1) !important; }
    }
    .navbar, .nav-links { overflow: visible; }

    /* Search Section */
    .search-section {
      background: linear-gradient(135deg, #fff8f6 0%, #fff1eb 100%);
      border-radius: 16px; 
      padding: 25px; 
      margin: 25px 0; 
      border-left: 4px solid #d6681a;
      box-shadow: 0 6px 20px rgba(214, 104, 26, 0.15); 
      position: relative; 
      overflow: hidden;
    }
    
    .search-section::before {
      content: ''; 
      position: absolute; 
      top: 0; 
      right: 0; 
      width: 100px; 
      height: 100px;
      background: radial-gradient(circle, rgba(214,104,26,0.1) 0%, transparent 70%); 
      border-radius: 50%;
    }
    
    .search-header { 
      text-align: center; 
      margin-bottom: 20px; 
    }
    
    .search-title {
      font-family: 'Poppins', sans-serif; 
      font-size: 28px; 
      font-weight: 700; 
      color: #d6681a; 
      margin: 0 0 6px 0;
      display: flex; 
      align-items: center; 
      justify-content: center; 
      gap: 10px; 
    }
    
    .search-icon-title { 
      font-size: 32px; 
      animation: bounce 2s infinite; 
    }
    
    .search-subtitle { 
      font-family: 'Segoe UI', Arial, sans-serif; 
      font-size: 14px; 
      color: #636161; 
      font-weight: 500; 
      opacity: .8; 
    }

    .filters.search-field {
      display: flex; 
      max-width: 500px; 
      margin: 0 auto; 
      background: white; 
      border-radius: 50px; 
      padding: 3px;
      box-shadow: 0 3px 12px rgba(0,0,0,.1); 
      transition: all .3s ease; 
      border: 2px solid transparent;
    }
    
    .search-section .filters { 
      margin-top: 0; 
    }

    .filters.search-field:focus-within { 
      border-color: #d6681a; 
      box-shadow: 0 5px 18px rgba(214,104,26,.25); 
      transform: translateY(-2px); 
    }

    .search-input {
      flex: 1; 
      border: none; 
      outline: none; 
      padding: 12px 20px; 
      font-size: 15px; 
      font-family: 'Segoe UI', Arial, sans-serif;
      background: transparent; 
      border-radius: 50px; 
      color: #333;
    }
    
    .search-input::placeholder { 
      color: #999; 
      font-weight: 400; 
    }

    .search-btn {
      background: linear-gradient(135deg, #d6681a 0%, #e57c3a 100%);
      border: none; 
      border-radius: 50px; 
      padding: 10px 22px; 
      color: white; 
      font-family: 'Poppins', sans-serif; 
      font-weight: 600; 
      font-size: 14px;
      cursor: pointer; 
      display: flex; 
      align-items: center; 
      gap: 6px; 
      transition: all .3s ease; 
      box-shadow: 0 3px 10px rgba(214,104,26,.3);
    }
    
    .search-btn:hover { 
      background: linear-gradient(135deg, #c55a15 0%, #d6681a 100%); 
      transform: translateY(-2px); 
      box-shadow: 0 5px 15px rgba(214,104,26,.4); 
    }
    
    .search-btn:active { 
      transform: translateY(0); 
    }
    
    .search-text { 
      display: inline-block; 
    }

    @keyframes bounce { 
      0%,20%,50%,80%,100%{ transform: translateY(0) } 
      40%{ transform: translateY(-4px) } 
      60%{ transform: translateY(-2px) } 
    }

    @media (max-width: 768px){
      .search-section{ 
        padding: 20px 15px; 
        margin: 20px 0; 
        border-radius: 12px; 
      }
      
      .search-title{ 
        font-size: 22px; 
        flex-direction: column; 
        gap: 6px; 
      }
      
      .search-icon-title{ 
        font-size: 28px; 
      }
      
      .search-subtitle{ 
        font-size: 13px; 
      }
      
      .filters.search-field{ 
        flex-direction: column; 
        border-radius: 12px; 
        padding: 0; 
        gap: 0; 
      }
      
      .search-input{ 
        padding: 14px 16px; 
        border-radius: 12px 12px 0 0; 
        border-bottom: 1px solid #eee; 
      }
      
      .search-btn{ 
        border-radius: 0 0 12px 12px; 
        padding: 12px 16px; 
        justify-content: center; 
      }
    }
    
    .search-btn.loading { 
      position: relative; 
      color: transparent; 
    }
    
    .search-btn.loading::after{
      content: ''; 
      position: absolute; 
      width: 18px; 
      height: 18px; 
      border: 2px solid transparent; 
      border-top: 2px solid #fff; 
      border-radius: 50%; 
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin { 
      0%{ transform: rotate(0deg) } 
      100%{ transform: rotate(360deg) } 
    }
    
    .search-field.success{ 
      border-color: #4caf50; 
      box-shadow: 0 3px 12px rgba(76,175,80,.2); 
    }

    /* Footer */
    .site-footer { 
      background: #222; 
      color: #fff; 
      text-align: center; 
      padding: 15px 10px; 
      font-family: 'Poppins', sans-serif; 
      margin-top: 30px; 
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
      font-size: 13px; 
    }
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
      <li><a href="products.php" class="active">Products</a></li>
      <li>
        <a href="cart.php">
          MyCart🛒<span id="cartCount" class="cart-count">0</span>
        </a>
      </li>
    </ul>
    <div class="right-controls">
      <a href="admin_dashboard.php" class="admin-link">Admin</a>
    </div>

    <button class="menu-toggle" id="menuToggle" aria-label="Menu" aria-expanded="false">☰</button>
  </nav>

  <?php
  // Ticker messages
  $news = [
    "       ⭐ BOOK YOUR ORDER HERE ⭐",
    "⭐ AFTER MAKING ADVANCE PAYMENT ⭐",
    "⭐ STORE OPEN TILL 11 PM ALL WEEK ⭐",
    "⭐ COLLECT YOUR ORDER PACKET FROM SHOP NO. 04, TEMP. CRACKER MARKET CHHOTA BANGARDA ⭐",
    "⭐ FOR DETAILS CONTACT -> 94250 46826 ⭐",
    "⭐ COMBO PACKS AVAILABLE — LIMITED STOCK! ⭐"
  ];
  $tickerText = implode(' • ', array_map('htmlspecialchars', $news));
  ?>
  <div class="ticker" role="region" aria-label="Latest announcements">
    <div class="ticker-ribbon-left" aria-hidden="true"></div>
    <div class="ticker-track">
      <div class="ticker-content">
        <span><?= $tickerText ?></span>
        <span class="sep"> • </span>
        <span><?= $tickerText ?></span>
      </div>
    </div>
    <button class="ticker-ctrl" id="tickerCtrl" aria-label="Pause ticker" aria-pressed="false">❚❚</button>
  </div>

  <!-- Product Page -->
  <section class="products-page">

    <!-- ===== Search Section ===== -->
    <div class="search-section">
      <div class="search-header">
        <h3 class="search-title">
          <span class="search-icon-title">🔍</span>
          Search Products Here
        </h3>
        <div class="search-subtitle">Find exactly what you're looking for</div>
      </div>

      <div class="filters search-field">
        <input type="text" id="search" class="search-input" placeholder="Search products by name, category, or features...">
        <button type="button" class="search-btn" aria-label="Search">
          <span class="search-text">Search</span>
        </button>
      </div>
    </div>

    <!-- Category Tabs -->
    <div class="category-tabs" id="categoryTabs">
      <button class="tab active" data-category="all">All Products</button>
      <button class="tab" data-category="baby">Baby Items</button>
      <button class="tab" data-category="rocket">Rocket/SkyShot</button>
      <button class="tab" data-category="light">Lights</button>
      <button class="tab" data-category="bomb">BOMB</button>
      <button class="tab" data-category="fancy">Fancy/New Items</button>
    </div>

    <!-- Product List -->
    <div class="product-grid">
      <?php while($p = $products->fetch_assoc()) { ?>
        <div class="product-card"
             data-name="<?= strtolower($p['name']) ?>"
             data-category="<?= strtolower($p['category']) ?>"
             data-id="<?= $p['id'] ?>">

          <div class="product-image">
            <img src="<?= $p['thumbnail'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">
          </div>

          <div class="product-details">
            <div class="product-info">
              <h3><?= htmlspecialchars($p['name']) ?></h3>
              <p class="category"><?= htmlspecialchars($p['category']) ?></p>
              <p class="desc"><?= !empty($p['description']) ? htmlspecialchars($p['description']) : "High quality product" ?></p>
              <p class="price">₹<?= $p['price'] ?></p>
            </div>

            <div class="actions">
              <label>Qty:</label>
              <input type="number" value="1" min="1" class="qty-input">
              <button class="add-to-cart"
                onclick="event.stopPropagation(); addToCart('<?= $p['name'] ?>', <?= $p['price'] ?>, '<?= $p['thumbnail'] ?>')">
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

  </section>

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

  <script>
    // Make entire product card clickable
    document.querySelectorAll(".product-card").forEach(card => {
      card.addEventListener("click", () => {
        let id = card.getAttribute("data-id");
        window.location.href = "product.php?id=" + id;
      });
    });

    // Prevent card click when clicking "Add to Cart"
    document.querySelectorAll(".add-to-cart").forEach(btn => {
      btn.addEventListener("click", e => e.stopPropagation());
    });

    // Prevent card click when changing Qty
    document.querySelectorAll(".qty-input").forEach(input => {
      input.addEventListener("click", e => e.stopPropagation());
      input.addEventListener("input", e => e.stopPropagation());
    });

    // Search + Filter
    const searchInput = document.getElementById("search");
    const products = document.querySelectorAll(".product-card");
    const tabs = document.querySelectorAll(".category-tabs .tab");

    // "No products found" message
    const noResults = document.createElement("p");
    noResults.textContent = "No products found";
    noResults.className = "no-results";
    noResults.style.display = "none";
    document.querySelector(".product-grid").appendChild(noResults);

    function filterProducts() {
      let searchText = (searchInput.value || "").toLowerCase();
      let activeTab = document.querySelector(".category-tabs .tab.active");
      let category = activeTab ? activeTab.dataset.category : "all";

      let visibleCount = 0;
      products.forEach(p => {
        let name = p.getAttribute("data-name");
        let cat = p.getAttribute("data-category");

        if ((category === "all" || cat === category) && name.includes(searchText)) {
          p.style.display = "flex";
          visibleCount++;
        } else {
          p.style.display = "none";
        }
      });

      noResults.style.display = visibleCount === 0 ? "block" : "none";
    }

    // Search typing
    searchInput.addEventListener("input", filterProducts);

    // Tab clicks
    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        document.querySelector(".category-tabs .tab.active").classList.remove("active");
        tab.classList.add("active");
        filterProducts();
      });
    });

    // SEARCH BUTTON hooks into the same filter logic
    const searchBtn = document.querySelector('.search-btn');
    const searchField = document.querySelector('.filters.search-field');
    if (searchBtn) {
      searchBtn.addEventListener('click', function() {
        this.classList.add('loading');
        filterProducts();
        searchField.classList.add('success');
        setTimeout(() => {
          this.classList.remove('loading');
          setTimeout(() => searchField.classList.remove('success'), 1200);
        }, 800);
      });

      // Enter key in input
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchBtn.click();
        }
      });

      // Clear success on typing
      searchInput.addEventListener('input', function() {
        searchField.classList.remove('success');
      });
    }
  </script>

  <script src="cart.js"></script>
  <script>
    function addToCart(name, price, img) {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      price = parseFloat(price);
      const card = event.target.closest(".product-card");
      let qtyInput = card.querySelector("input[type='number']");
      let qty = parseInt(qtyInput.value) || 1;

      let existing = cart.find(item => item.name === name);
      if (existing) { existing.qty += qty; }
      else { cart.push({ name, price, qty, img }); }

      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartCount();
      alert(`${name} added to cart!`);
    }

    function updateCartCount() {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
      const cartCount = document.getElementById("cartCount");
      if (cartCount) { cartCount.textContent = totalItems > 0 ? totalItems : 0; }
    }

    window.addEventListener("storage", function(e) {
      if (e.key === "cart") { updateCartCount(); }
    });
    document.addEventListener("DOMContentLoaded", updateCartCount);
  </script>

  <script>
    // Navbar mobile toggle
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('menuToggle');
      const links  = document.querySelector('.nav-links');
      if (!toggle || !links) return;

      toggle.addEventListener('click', () => {
        const open = links.classList.toggle('active');
        toggle.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.textContent = open ? '✖' : '☰';
      });
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
    // Ticker pause
    (function(){
      const ctrl = document.getElementById('tickerCtrl');
      const content = document.querySelector('.ticker-content');
      if (!ctrl || !content) return;
      let paused = false;
      ctrl.addEventListener('click', () => {
        paused = !paused;
        content.style.animationPlayState = paused ? 'paused' : 'running';
        ctrl.setAttribute('aria-pressed', paused ? 'true' : 'false');
        ctrl.textContent = paused ? '▶' : '❚❚';
      });
    })();
  </script>
</body>
</html>