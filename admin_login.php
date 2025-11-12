<?php
session_start();
include("db.php");
$admin = $_SESSION['admin'];
$action = "Admin logged in";
$conn->query("INSERT INTO activities (admin_user, action) VALUES ('$admin', '$action')");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // simple hash

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Safal Sales</title>
  <link rel="stylesheet" href="style.css">
  <style>
/* Center Wrapper */
    .wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      padding-top: 60px; /* space for navbar */
      box-sizing: border-box;
    }

    /* Login Box */
    .login-box {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      width: 320px;
      text-align: center;
    }
    .login-box h2 {
      margin-bottom: 20px;
      color: #d6681aff;
    }
    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .login-box button {
      width: 100%;
      padding: 12px;
      background: #d6681aff;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }
    .login-box button:hover {
      background: #e03e00;
    }
    .error {
      color: red;
      margin-bottom: 10px;
      font-size: 14px;
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
          Cart 🛒 <span id="cartCount" class="cart-count">0</span>
        </a>
      </li>
      <li><a href="admin_dashboard.php">Admin</a></li>
    </ul>

    <div id="google_translate_element"></div>
    <button class="menu-toggle" id="menuToggle" aria-label="Menu" aria-expanded="false">☰</button>
  </nav>
  <!-- Login Box -->
  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Admin Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
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
</body>
</html>
