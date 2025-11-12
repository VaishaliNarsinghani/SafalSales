<?php
include("db.php");
$featuredProducts = $conn->query("SELECT * FROM products WHERE featured=1 ORDER BY id DESC LIMIT 50");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Safal Sales | Quality Products at Best Prices</title>
  <meta name="description" content="Safal Sales provides top-quality products with reliable service. Visit safalsales.com to explore our full catalog.">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <!-- Favicon for all browsers -->
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

<!-- Apple Touch Icon (for iPhones etc.) -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<!-- Manifest (for Google Search + Android devices) -->
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#ffffff">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Safal Sales",
  "url": "https://safalsales.com",
  "logo": "https://safalsales.com/favicon-512x512.png"
}
</script>
</head>
<style>
  body{
    margin:0;
    padding:0;
  }
.product-card {
  width: 100%;
  text-align: center;
  margin: 0px;
  position: relative;
  overflow: hidden;
  transition: transform 0.3s ease; /* smooth hover effect */
}

.product-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
  display: block;
  margin: 0 auto 10px;
  transition: transform 0.3s ease, opacity 0.3s ease; /* smooth zoom + fade */
}

.product-card:hover img {
  opacity: 0.7; /* slightly faded */
  transform: scale(1.2); /* zoom effect */
}
.product-card-link {
  display: inline-block;
  text-decoration: none;
  color: inherit;
}

.product-card-link .product-card {
  cursor: pointer;
}

.product-card video {
  position: absolute;
  top: 10px;
  left: 10px;
  right: 10px;
  width: calc(100% - 20px);
  height: 200px;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
  border-radius: 8px;
  z-index: 2;
}

.product-card:hover video {
  opacity: 1;
}

/* Banner Container with Triangle */
.banner-container {
  position: relative;
  height: 280px;
  background: #000;
  overflow: hidden;
}

.banner {
  height: 100%;
  background: url("https://i.pinimg.com/originals/18/da/6d/18da6df7b7aa388a11ab708a7b4ce8f9.gif") center/cover no-repeat;
  display: flex;
  align-items: right; /* center vertically on all screens */
  justify-content: right; /* center horizontally */
  text-align: center;
  color: #fff;
  position: relative;
  animation: zoomIn 15s ease-in-out infinite alternate;
  padding: 0 15px; /* small horizontal padding for mobile */
}

.banner-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55); /* dark overlay */
}

.banner-content {
  position: relative;
  z-index: 2;
  max-width: 600px;
  padding: 10px;
  animation: fadeInUp 1.2s ease;
}

.banner h1 {
  font-size: 2.8rem;
  margin-bottom: 15px;
  font-weight: 800;
  line-height: 1.2;
  text-shadow: 0 4px 10px rgba(0,0,0,0.6);
}

.banner p {
  font-size: 1.2rem;
  margin-bottom:10px;
  color: #f0f0f0;
}

/* Animated Button */
.btn {
  background: linear-gradient(45deg,#ff4500,#ff6a00);
  color: #fff;
  padding: 12px 26px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  box-shadow: 0 4px 15px rgba(255,69,0,0.4);
  transition: all 0.3s ease;
}
.btn:hover {
  background: linear-gradient(45deg,#ff6a00,#ff4500);
  transform: translateY(-4px) scale(1.05);
  box-shadow: 0 6px 20px rgba(255,69,0,0.6);
}

/* ===== Responsive adjustments ===== */
@media (max-width: 1024px) {
  .banner h1 {
    font-size:35px;
  }
  .banner p {
    font-size: 20PX;
  }
  .banner-content {
    max-width: 600px;
  }
}

@media (max-width: 768px) {
  .banner-container {
    height: 100px;
  }
  .banner {
    height: 100px;
    justify-content: center; /* center for tablets and mobile */
    align-items: center;
  }
  .banner h1 {
    font-size: 1.8rem;
  }
  .banner p {
    font-size: 1rem;
  }
  .btn {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
  .banner-content {
    max-width: 90%;
    padding: 5px;
  }
}

@media (max-width: 480px) {
  .banner-container {
    height: 100px;
  }
  .banner {
    height: 100px;
  }
  .banner h1 {
    font-size: 20px;
  }
  .banner p {
    font-size: 15px;
  }
  .btn {
    padding: 2px 8px;
    font-size: 1rem;
  }
}

/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes zoomIn {
  from { background-size: 100%; }
  to { background-size: 110%; }
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
      font-weight: 900;
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

/* Orange Splash - FIXED POSITIONING */
.triangle-splash {
  position: absolute;
  top: 0;
  left: 0;
  width: 25%;
  height: 100%;
  background: #d6681a;
  clip-path: polygon(0 0, 100% 0, 0 100%);
  z-index: 5;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
  padding: 30px 20px;
  box-shadow: 5px 0 25px rgba(0,0,0,0.3);
  animation: slideInTriangle 1.5s cubic-bezier(0.77, 0, 0.175, 1) forwards;
}

/* Fireworks Overlay */
.fireworks-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.85;
  animation: fireworksFade 3s ease-in-out infinite alternate;
  pointer-events: none; /* prevents blocking clicks */
}

/* Logo & text inside splash - FIXED POSITIONING */
.triangle-content {
  position: relative;
  text-align: left;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  opacity: 0;
  animation: fadeInText 2s ease forwards;
  animation-delay: 1.2s;
  z-index: 2;
  max-width: 90%;
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
.promo-text {
  font-family: 'Poppins', sans-serif;
  font-size: 18px;
  font-weight: bold;
  text-align: center;
  color: #ff6a00;
  text-shadow: 2px 2px 5px #ffeb3b, 0 0 10px #ff5722;
  animation: sparkle 1.5s infinite alternate;
  margin: 20px 0;
}

.promo-text span {
  color: #ffeb3b;
  text-transform: uppercase;
  font-size: 22px;
  animation: pulse 1s infinite alternate;
}

/* Sparkle animation for text shadow */
@keyframes sparkle {
  0% { text-shadow: 2px 2px 5px #ffeb3b, 0 0 10px #ff5722; }
  50% { text-shadow: 2px 2px 8px #fff, 0 0 15px #ff4500; }
  100% { text-shadow: 2px 2px 5px #ffeb3b, 0 0 10px #ff5722; }
}

/* Pulse animation for discount number */
@keyframes pulse {
  0% { transform: scale(1); color: #ffeb3b; }
  50% { transform: scale(1.2); color: #fffa00; }
  100% { transform: scale(1); color: #ffeb3b; }
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  padding: 20px;
}

/* Product card */
.product-card {
  background: linear-gradient(145deg, #fff8e1, #ffe0b2);
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  overflow: hidden;
  position: relative;
  text-align: center;
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 20px rgba(0,0,0,0.2);
}

/* Product media */
.product-media {
  position: relative;
  height: 180px;
  overflow: hidden;
  border-radius: 15px 15px 0 0;
}

.product-media img, 
.product-media video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s;
}

.product-card:hover .product-media img,
.product-card:hover .product-media video {
  transform: scale(1.1);
}

/* Show video on hover if exists */
.product-media video {
  position: absolute;
  top: 0; left: 0;
  opacity: 0;
  pointer-events: none;
}

.product-card:hover .product-media video {
  opacity: 1;
}

/* Product info */
.product-card h3 {
  font-size: 16px;
  margin: 10px 0 5px;
  color: #ff6a00;
}

.product-card h5 {
  font-size: 13px;
  color: #555;
  margin-bottom: 8px;
}

.product-card .price {
  font-weight: bold;
  color: #d32f2f;
  margin-bottom: 10px;
}

/* Actions */
.actions {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 10px;
}

.actions input {
  width: 50px;
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.actions button {
  background: #ff6a00;
  border: none;
  color: white;
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s;
}

.actions button:hover {
  background: #ff4500;
  transform: scale(1.05);
}
/* ===== Navbar & Controls for Mobile ===== */
@media (max-width: 768px) {
  .navbar {
    flex-wrap: wrap;
    padding: 2px 5px;
  }
  .logo span {
    font-size: 14px;
  }
  .nav-links {
    flex-direction: column;
    width: 100%;
    display: none;
    margin-top: 5px;
  }
  .nav-links.active {
    display: flex;
  }
  .nav-links li a {
    font-size: 20px;
    padding: 8px 0;
    text-align: center;
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

/* ===== Banner Adjustments ===== */
@media (max-width: 480px) {
  .banner-container {
    height: auto;
    padding: 20px 10px;
    text-align: center;
  }
  .banner {
    height: auto;
  }
  .banner h1 {
    font-size: 1.5rem;
  }
  .banner p, .promo-text {
    font-size: 0.9rem;
  }
  .btn {
    padding: 8px 16px;
    font-size: 0.85rem;
  }
}

/* ===== Product Grid Adjustments ===== */
@media (max-width: 480px) {
  .product-grid {
    grid-template-columns: 1fr;
    gap: 5px;
    padding: 15px 10px;
  }

  .product-card {
    max-width: 100%;
    margin: 0 auto;
    text-align: center;
  }

  .product-media {
    height: 160px;
  }

  .product-card h3 {
    font-size: 20px;
  }
  .product-card h5 {
    font-size: 15px;
  }
  .product-card .price {
    font-size: 18px;
  }
  .actions input {
    width: 40px;
    padding: 3px;
  }
  .actions button {
    padding: 8px 12px;
    font-size: 15px;
  }
}

/* ===== Footer ===== */
.site-footer {
  font-size: 13px;
  padding: 15px 10px;
}
/* ===== Featured Products Styling ===== */
.section-title {
  text-align: center;
  font-size: 2rem;
  margin: 40px 0 20px;
  font-weight: 800;
  color: #ff6a00;
  text-shadow: 2px 2px 10px rgba(255,106,0,0.5);
  font-family: 'Poppins', sans-serif;
}

/* Glassmorphism Product Card */
.product-card {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(12px);
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  transition: all 0.35s ease-in-out;
  position: relative;
}

.product-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 15px 30px rgba(255,106,0,0.4);
}

/* Featured Ribbon */
.product-cards::before {
  content: "⭐ Featured";
  position: absolute;
  top: 12px;
  left: -35px;
  background: linear-gradient(45deg,#ff6a00,#ff4500);
  color: white;
  font-size: 12px;
  font-weight: bold;
  padding: 6px 50px;
  transform: rotate(-45deg);
  box-shadow: 0 3px 8px rgba(0,0,0,0.3);
}

/* Product Media */
.product-media {
  position: relative;
  height: 220px;
  overflow: hidden;
}

.product-media img, .product-media video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s;
  border-bottom: 2px solid #ff6a00;
}

.product-card:hover .product-media img {
  transform: scale(1.1);
  opacity: 0.8;
}

/* Price Tag */
.price {
  font-size: 1.1rem;
  font-weight: 800;
  color: #d32f2f;
  margin: 8px 0;
  animation: glowPrice 1.2s infinite alternate;
}

/* Shimmer Animation */
@keyframes glowPrice {
  from { text-shadow: 0 0 5px #ff6a00; }
  to { text-shadow: 0 0 15px #ff4500; }
}

/* Actions (Cart) */
.actions button {
  background: linear-gradient(45deg, #ff6a00, #ff4500);
  border: none;
  color: white;
  font-weight: bold;
  border-radius: 30px;
  padding: 8px 16px;
  transition: all 0.3s ease-in-out;
}

.actions button:hover {
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(255,106,0,0.6);
}
/* Gradient glowing offer text */

/* Tilt effect for content inside triangle */
/* ===== News Ticker (Red ribbon + yellow text) ===== */
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
  z-index: 20;                       /* below sticky navbar's z-index but above content */
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
<style>
/* Orange Splash - FIXED POSITIONING */
.triangle-splash {
  position: absolute;
  top: 0;
  left: 0;
  width: 25%;
  height: 100%;
  background: #d6681a;
  clip-path: polygon(0 0, 100% 0, 0 100%);
  z-index: 5;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
  padding: 30px 20px;
  box-shadow: 5px 0 25px rgba(0,0,0,0.3);
  animation: slideInTriangle 1.5s cubic-bezier(0.77, 0, 0.175, 1) forwards;
}

/* Logo & text inside splash - TILTED TO MATCH TRIANGLE */
.triangle-content {
  position: relative;
  text-align: left;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  opacity: 0;
  animation: fadeInText 2s ease forwards;
  animation-delay: 1.2s;
  z-index: 2;
  max-width: 90%;
  transform: rotate(-45deg) translateX(-20px) translateY(20px);
  transform-origin: top left;
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

/* Adjust text sizes for better fit in tilted position */
.triangle-content .offer-sub {
  font-size: 15px;
  margin-bottom: 5px;
  font-weight: 900;
  color: black;
  line-height: 1.2;
}

.triangle-content .offer-sub:nth-child(2) {
  font-size: 8px;
  margin-bottom: 12px;
  color: black;
}

.triangle-content .countdown {
  font-size: 10px;
  font-weight: bold;
  color: #ffeb3b;
  text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
  margin-bottom: 8px;
}

.triangle-content .offer-btn {
  margin-top: 10px;
  padding: 4px 8px;
  font-size: 8px;
  background: linear-gradient(45deg,#ff6a00,#ff9800);
  color: #fff;
  font-weight: 600;
  text-decoration: none;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  transition: all 0.3s ease;
  display: inline-block;
}

.triangle-content .offer-btn:hover {
  background: linear-gradient(45deg,#ff9800,#ff6a00);
  transform: scale(1.1);
}

/* Animations */
@keyframes slideInTriangle {
  from { left: -100%; opacity: 0; }
  to { left: 0; opacity: 1; }
}

@keyframes fadeInText {
  from { opacity: 0; transform: rotate(-45deg) translateX(-40px) translateY(40px); }
  to { opacity: 1; transform: rotate(-45deg) translateX(-20px) translateY(20px); }
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
</style>
 <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
 <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<body>

  <!-- ===== NAVBAR ===== -->
<nav class="navbar">
  <div class="logo" style="display: flex; align-items: center; gap: 10px;">
    <img src="safal sales logo.png" alt="Safal Sales Logo" style="height:55px; border-radius:70%;">
    <span>Safal Sales</span>
  </div>

  <ul class="nav-links">
    <li><a href="#home" class="active">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li>
      <a href="cart.php">
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

<!-- ===== BANNER WITH TRIANGLE SPLASH ===== -->
<div class="banner-container">
  <!-- Triangle Splash -->
  <div class="triangle-splash">
  <img src="firework.png" alt="Fireworks" class="fireworks-overlay">
  <div class="triangle-content">
    <div class="offer-sub">upto 🎆 85% OFF 🎆 on <br>Safal Sales</div>
    <div class="offer-sub">Festive Sale • Limited Stock</div>
    <div id="countdown" class="countdown"></div>
    <a href="products.php" class="offer-btn">Grab Now 🎉</a>
  </div>
</div>
  
  <!-- Banner -->
  <section class="banner" id="home">
    <div class="banner-overlay"></div>
    <div class="banner-content">
      <h1>✨Happy Diwali 2025✨</h1>
      <p>Enjoy Celebrations With Huge Savings!!</p>
      <p class="promo-text">🎆 Upto <span>85% OFF</span> on Fireworks & Attractive Offers on Sweets! 🎇</p>
      <br>
      <a href="products.php" class="btn">Click To See Products -></a>
    </div>
  </section>
</div>

  <!-- ===== FEATURED PRODUCTS ===== -->
 <section id="products">
  <h2 class="section-title">Featured Products</h2>
  <div class="product-grid ">
    <?php while($p = $featuredProducts->fetch_assoc()): ?>
    <div class="product-card" onclick="window.location='product.php?id=<?= $p['id'] ?>'">
      <!-- Image / Video -->
      <div class="product-media product-cards">
        <img src="<?= $p['thumbnail'] ?>" alt="<?= $p['name'] ?>">
        <?php if(!empty($p['video'])): ?>
        <video src="<?= $p['video'] ?>" muted loop class="product-video"></video>
        <?php endif; ?>
      </div>

      <!-- Product Info -->
      <h3><?= $p['name'] ?></h3>
      <h5><?= $p['category'] ?></h5>
      <p class="price">₹<?= $p['price'] ?></p>

      <!-- Actions -->
      <div class="actions" onclick="event.stopPropagation();">
        <input type="number" value="1" min="1">
        <button onclick="addToCart('<?= $p['name'] ?>', <?= $p['price'] ?>, '<?= $p['thumbnail'] ?>')">Add to Cart</button>
      </div>
    </div>
    <?php endwhile; ?>

    </div>
   
  </section>
   <footer class="site-footer">
  <div class="footer-content">
    <p>Contact Number: <a href="tel:+919425046286">+91 94250 46286</a></p>
    <p>&copy; <?= date('Y') ?> Safal Sales. All rights reserved.</p>
  </div>
</footer>
<script>
  // Countdown to 3 days later
  const countdownDate = new Date(new Date().getTime() + 3*24*60*60*1000);

  const timer = setInterval(() => {
    const now = new Date().getTime();
    const distance = countdownDate - now;

    if (distance <= 0) {
      document.getElementById("countdown").innerHTML = "Offer Expired!";
      clearInterval(timer);
      return;
    }

    const days = Math.floor(distance / (1000*60*60*24));
    const hours = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
    const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
    const seconds = Math.floor((distance % (1000*60)) / 1000);

    document.getElementById("countdown").innerHTML =
      `⏳ ${days}d ${hours}h ${minutes}m ${seconds}s`;
  }, 1000);
</script>

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
  <script src="script.js"></script>
  <script>
    
document.querySelectorAll('.product-card').forEach(card => {
  const video = card.querySelector('video');
  if(video){
    card.addEventListener('mouseenter', () => {
      video.currentTime = 0; // start from beginning
      video.play();
    });
    card.addEventListener('mouseleave', () => {
      video.pause();
      video.currentTime = 0; // reset
    });
  }
});
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
<script src="cart.js"></script>
</body>
</html>