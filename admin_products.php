<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
if(!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include("db.php");

// Add Product
// Add Product
if (isset($_POST['add'])) {
   $name     = $_POST['name'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $actual_stock = $_POST['actual_stock'];
    $online_stock = $_POST['online_stock'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $description = $_POST['description'];
    $video    = $_POST['video'];

    // Logging action
    $admin = $_SESSION['admin']; 
    $log = $conn->prepare("INSERT INTO activities (admin_user, action) VALUES (?, ?)");
    $action = "Added product: $name";
    $log->bind_param("ss", $admin, $action);
    $log->execute();

    // Ensure uploads folder exists
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // === IMAGE UPLOADS ===
// === IMAGE UPLOADS ===
$img1 = '';
$img2 = '';
$img3 = '';
$thum = '';

if (!empty($_FILES['image1']['name'])) {
    $img1 = "uploads/" . time() . "_1_" . basename($_FILES['image1']['name']);
    move_uploaded_file($_FILES['image1']['tmp_name'], __DIR__ . "/" . $img1);
}
if (!empty($_FILES['image2']['name'])) {
    $img2 = "uploads/" . time() . "_2_" . basename($_FILES['image2']['name']);
    move_uploaded_file($_FILES['image2']['tmp_name'], __DIR__ . "/" . $img2);
}
if (!empty($_FILES['image3']['name'])) {
    $img3 = "uploads/" . time() . "_3_" . basename($_FILES['image3']['name']);
    move_uploaded_file($_FILES['image3']['tmp_name'], __DIR__ . "/" . $img3);
}
if (!empty($_FILES['thumbnail']['name'])) {
    $thum = "uploads/" . time() . "_thumb_" . basename($_FILES['thumbnail']['name']);
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . "/" . $thum);
}
if (!is_writable(__DIR__ . "/uploads/")) {
    die("❌ Uploads folder is not writable by PHP!");
}

// === INSERT INTO DB ===
$stmt = $conn->prepare("INSERT INTO products 
    (name, category, price, actual_stock, online_stock, image1, image2, image3, thumbnail, video, description, featured) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->bind_param("ssdiissssssi", 
    $name, $category, $price, $actual_stock, $online_stock, 
    $img1, $img2, $img3, $thum, $video, $description, $featured
);

$stmt->execute();

}
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products - Safal Sales</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
    }

    /* ===== NAVBAR ===== */
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

    /* Layout */
    .wrapper { display:flex; min-height:100vh; }

    /* Sidebar */
    .sidebar {
      width:250px; background:#fff; border-right:1px solid #eee;
      padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.05);
    }
    .sidebar h2 { color:#d6681aff; margin-bottom:20px; font-size:22px; }
    .sidebar a {
      display:block; padding:12px 15px; margin-bottom:10px;
      color:#333; text-decoration:none; border-radius:8px;
      font-weight:500; transition:0.2s;
    }
    .sidebar a:hover, .sidebar a.active { background:#d6681aff; color:#fff; }

    /* Main Content */
    .main { flex:1; padding:30px; }
    h2 { margin-top:0; margin-bottom:20px; color:#333; }

    /* Form */
    form {
      background:#fff; padding:20px; border-radius:12px;
      box-shadow:0 4px 10px rgba(0,0,0,0.08);
      display:grid; gap:12px; max-width:500px;
      margin-bottom:30px;
    }
    input, button {
      padding:10px; border-radius:6px; border:1px solid #ccc;
      font-size:14px;
    }
    button {
      background:#d6681aff; color:#fff; border:none;
      cursor:pointer; transition:0.3s;
    }
    button:hover { background:#e03e00; }

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
    table th { background:#f9f9f9; font-weight:bold; }
    table tr:hover { background:#fafafa; }
    table img { border-radius:6px; }
    a.delete { color:#d6681aff; text-decoration:none; font-weight:bold; }
    a.delete:hover { text-decoration:underline; }
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
      <a href="admin_dashboard.php">📊 Dashboard</a>
      <a href="admin_products.php" class="active">🛍 Products</a>
      <a href="admin_orders.php">📦 Orders</a>
      <a href="admin_check.php">✅ Order Check</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
    <!-- Main -->
    <div class="main">
      <h2>Manage Products</h2>

 <form method="post" enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Product Name" required>
 <label for="category">Category:</label>
<select id="categoryFilter" name="category">
        <option value="all">All Categories</option>
        <option value="baby">Baby Items</option>
        <option value="rocket">Rocket/SkyShot</option>
        <option value="light">Lights</option>
        <option value="bomb">BOMB</option>
        <option value="fancy">Fancy</option>
      </select>
  <input type="number" step="0.01" name="price" placeholder="Price" required>
  <input type="number" name="actual_stock" placeholder="Actual Stock" required>
  <input type="number" name="online_stock" placeholder="Online Stock" required>

  <!-- IMAGE -->
<label>Image 1:</label>
<input type="file" name="image1" accept="image/*"><br>

<label>Image 2:</label>
<input type="file" name="image2" accept="image/*"><br>

<label>Image 3:</label>
<input type="file" name="image3" accept="image/*"><br>

<label>Thumbnail:</label>
<input type="file" name="thumbnail" accept="image/*"><br>
<label>Video Link (YouTube/Vimeo URL):</label>
<input type="text" name="video" placeholder="https://..."><br>

<label>Description:</label>
<textarea name="description" rows="4" placeholder="Product description..."></textarea><br>


  <label><input type="checkbox" name="featured" value="1"> Mark as Featured</label>
  <button type="submit" name="add">Add Product</button>
</form>

    <table>
  <tr>
    <th>Image</th>
    <th>Video</th>
    <th>Name</th>
    <th>Actual Stock</th>
    <th>Online Stock</th>
    <th>Featured</th>
    <th>Action</th>
  </tr>
  <?php while($p = $products->fetch_assoc()){ ?>
    <tr>
      <td>
        <?php if($p['thumbnail']): ?>
          <img src="<?= $p['thumbnail'] ?>" width="60">
        <?php else: ?>
          ❌
        <?php endif; ?>
      </td>
      <td>
        <?php if($p['video']): ?>
          <video src="<?= $p['video'] ?>" width="80" height="60" controls></video>
        <?php else: ?>
          ❌
        <?php endif; ?>
      </td>
      <td><?= $p['name'] ?></td>
      <td><?= $p['actual_stock'] ?></td>
      <td><?= $p['online_stock'] ?></td>
      <td><?= $p['featured'] ? "✅" : "❌" ?></td>
      <td>
        <a href="edit_product.php?id=<?= $p['id'] ?>" class="edit">Edit</a> | 
        <a href="delete_product.php?id=<?= $p['id'] ?>" 
           class="delete" 
           onclick="return confirm('Are you sure you want to delete this product?')">
           Delete
        </a>
      </td>
    </tr>
  <?php } ?>
</table>


    </div>
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
</body>
</html>