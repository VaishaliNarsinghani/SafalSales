<?php 
include "db.php";

// --- safer fetch ---
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { die("Invalid product"); }

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) { die("Product not found"); }

// Collect media
$imgs = [];
if (!empty($product['thumbnail'])) $imgs[] = $product['thumbnail'];
if (!empty($product['image1']))    $imgs[] = $product['image1'];
if (!empty($product['image2']))    $imgs[] = $product['image2'];
if (!empty($product['image3']))    $imgs[] = $product['image3'];
$hasVideo = !empty($product['video']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --brand:#d6681a; --brand-dark:#d84315; --text:#333; --bg:#fdfdfd;
      --z-navbar: 1000; --z-ticker: 900;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-family:'Segoe UI',Arial,sans-serif;line-height:1.55}

    /* NAVBAR */
    .navbar{
      font-family:'Poppins',sans-serif; display:flex; justify-content:space-between; align-items:center;
      padding:8px 18px; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.1);
      position:sticky; top:0; z-index:var(--z-navbar);
    }
    .navbar .logo span{font-size:30px;font-weight:900;color:var(--brand)}
    .navbar .nav-links{list-style:none;display:flex;gap:25px;margin:0;padding:0}
    .navbar .nav-links a{text-decoration:none;font-weight:700;font-size:20px;color:#636161;transition:.3s}
    .navbar .nav-links a:hover,.navbar .nav-links a.active{color:var(--brand);transform:scale(1.05)}
    .menu-toggle{font-size:26px;background:none;border:none;cursor:pointer;display:none}
    .right-controls{display:flex;align-items:center;gap:18px}
    .admin-link{ text-decoration:none;color:transparent;font-weight:600;font-size:15px;
      background-image:linear-gradient(90deg,var(--brand) 0%,#fff 100%); background-size:0% 2px; background-repeat:no-repeat; background-position:bottom left;
      opacity:.05; transition:.3s; user-select:none}
    .admin-link:hover{opacity:1;color:var(--brand);background-size:100% 2px}
    @media (max-width:768px){
      .menu-toggle{display:block}
      .navbar{flex-wrap:wrap}
      .navbar .nav-links{
        display:none; flex-direction:column; width:100%; margin-top:10px; padding:10px 0; border-top:1px solid #eee; background:#fff;
        position:relative; box-shadow:0 4px 12px rgba(0,0,0,.08); z-index: calc(var(--z-navbar) + 1);
      }
      .navbar .nav-links.active{display:flex; animation:slideDown .25s ease}
      .navbar .nav-links li{width:100%; padding:10px 0; border-bottom:1px solid #f2f2f2}
      .navbar .nav-links li:last-child{border-bottom:none}
      .navbar .nav-links a{display:block}
    }
    @keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

    /* TICKER (under the dropdown) */
    .ticker{
      position: relative; width:100%; background:#c1121f; color:#ffeb3b; font-family:'Poppins',sans-serif; font-weight:700;
      overflow:hidden; display:flex; align-items:stretch; min-height:44px; box-shadow:0 2px 8px rgba(0,0,0,.1); z-index:var(--z-ticker);
    }
    .ticker-ribbon-left{width:48px; background:#9b0f19; position:relative; flex:0 0 auto;}
    .ticker-ribbon-left::after{
      content:""; position:absolute; right:-16px; top:0; width:0; height:0;
      border-top:22px solid transparent; border-bottom:22px solid transparent; border-left:16px solid #9b0f19;
    }
    .ticker-track{ position:relative; overflow:hidden; display:flex; align-items:center; flex:1 1 auto; }
    .ticker-content{ display:inline-flex; gap:16px; white-space:nowrap; will-change:transform; animation:tickerScroll var(--ticker-speed,30s) linear infinite; padding:0 10px; align-items:center; }
    .ticker:hover .ticker-content{ animation-play-state:paused; }
    .ticker-content span{ font-size:15px; line-height:44px; }
    .ticker-ctrl{ flex:0 0 auto; width:44px; height:44px; border:none; background:#a50f1a; color:#ffeb3b; font-size:16px; cursor:pointer; display:grid; place-items:center; }
    @keyframes tickerScroll{ from{transform:translateX(0)} to{transform:translateX(-50%)} }
    @media (max-width:768px){ .ticker{min-height:40px} .ticker-content span{line-height:40px} .ticker-ctrl{width:40px;height:40px} .ticker-ribbon-left{width:40px}
      .ticker-ribbon-left::after{border-top-width:20px;border-bottom-width:20px;right:-14px;border-left-width:14px;} }
    @media (max-width:480px){ .ticker{min-height:36px} .ticker-content span{line-height:36px} .ticker-ctrl{width:36px;height:36px} .ticker-ribbon-left{width:36px}
      .ticker-ribbon-left::after{border-top-width:18px;border-bottom-width:18px;right:-12px;border-left-width:12px;} }

    /* PAGE WRAP */
    .product-detail{max-width:1200px;margin:24px auto;padding:12px}
    .back-link{display:inline-block;margin:6px 0 16px;padding:10px 16px;background:#bb6439;color:#fff;font-weight:700;border-radius:6px;text-decoration:none;box-shadow:0 3px 8px rgba(0,0,0,.2)}
    .back-link:hover{background:#888584;color:#000;transform:translateY(-2px)}

    /* LAYOUT */
    .product-layout{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:start}
    @media (max-width:900px){.product-layout{grid-template-columns:1fr;gap:22px}}

    /* MEDIA + PAN/ZOOM */
    .slider{position:relative;width:100%;overflow:hidden}
    .slides{display:flex;transition:transform .45s ease}
    .slide{flex:0 0 100%;display:flex;justify-content:center;align-items:center}
    .zoom-container{
      width:100%;max-width:720px;height:440px;border-radius:12px;overflow:hidden;position:relative;background:#fff;
      box-shadow:0 4px 12px rgba(0,0,0,.12);
      touch-action: none;               /* capture pinch & pan */
      cursor: grab;
    }
    .zoom-container.dragging{ cursor: grabbing; }
    .zoom-img{
      width:100%; height:100%; object-fit:contain;
      transform-origin: 0 0;            /* important for anchored zoom */
      transition: transform .08s ease-out;
      user-select: none; -webkit-user-drag: none; -webkit-user-select:none;
      pointer-events: none;             /* container receives pointers */
    }

    .slide-btn{position:absolute;top:50%;transform:translateY(-50%);width:44px;height:44px;border-radius:50%;border:none;background:rgba(255,255,255,.95);box-shadow:0 6px 18px rgba(0,0,0,.12);font-size:26px;color:#333;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:5}
    .slide-btn.prev{left:10px}.slide-btn.next{right:10px}
    .thumb-container{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:10px}
    .thumb{border:none;background:transparent;padding:3px;cursor:pointer}
    .thumb img{width:64px;height:64px;object-fit:cover;border-radius:6px;border:2px solid transparent}
    .thumb.active img{border-color:var(--brand)}

    /* VIDEO CARD (Watch Product Video) */
    .video-card{
      margin:14px auto 0;
      max-width:640px;
      border-radius:12px;
      background:#fff8f6;
      box-shadow:0 4px 12px rgba(0,0,0,.08);
      overflow:hidden;
      transition:transform .2s ease, box-shadow .2s ease;
    }
    .video-card:hover{
      transform:translateY(-2px);
      box-shadow:0 8px 20px rgba(0,0,0,.12);
    }
    .video-card a{
      display:flex;
      align-items:center;
      gap:16px;
      text-decoration:none;
      padding:14px;
      color:inherit;
    }
    .video-thumb{
      width:56px; height:56px;
      border-radius:12px;
      background: linear-gradient(45deg, var(--brand), #ff944d);
      display:flex; align-items:center; justify-content:center;
      font-size:26px; color:#fff; flex-shrink:0;
      box-shadow:0 4px 10px rgba(214,104,26,.35);
    }
    .video-text h3{
      margin:0; font-size:18px; color:#222; font-weight:800;
    }
    .video-text p{
      margin:4px 0 0; font-size:14px; color:#555;
    }
    .video-card a:focus-visible{
      outline:3px solid #ffd29b; outline-offset:3px; border-radius:14px;
    }
    @media (max-width:480px){
      .video-card a{ padding:12px }
      .video-thumb{ width:52px; height:52px; font-size:24px }
      .video-text h3{ font-size:16px }
      .video-text p{ font-size:13px }
    }

    /* LIGHTBOX */
    .lightbox{display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:2000;justify-content:center;align-items:center;opacity:0;transition:opacity .3s}
    .lightbox.active{display:flex;opacity:1}
    .lightbox-content{position:relative;max-width:92%;max-height:92%;display:flex;align-items:center;justify-content:center;width:100%; touch-action:none;}
    .lightbox-img{max-width:100%;max-height:88vh;object-fit:contain;border-radius:8px;box-shadow:0 10px 30px rgba(0,0,0,.5);transform-origin:0 0;transition:transform .08s ease-out; user-select:none; -webkit-user-select:none; pointer-events:none; cursor:grab;}
    .lightbox.dragging .lightbox-img{cursor:grabbing;}
    .lightbox-nav{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.25);border:none;color:#fff;font-size:24px;width:50px;height:50px;border-radius:50%;cursor:pointer;z-index:2100}
    .lightbox-prev{left:18px}.lightbox-next{right:18px}
    .lightbox-close{position:absolute;top:-40px;right:-40px;background:rgba(255,255,255,.25);border:none;color:#fff;font-size:30px;width:40px;height:40px;border-radius:50%;cursor:pointer;z-index:2101}
    .lightbox-counter{position:absolute;bottom:-40px;left:50%;transform:translateX(-50%);color:#fff;background:rgba(0,0,0,.55);padding:5px 14px;border-radius:20px;font-size:14px}

    /* INFO */
    .product-info h2{margin:0 0 10px;font-size:28px;color:#222}
    .product-info .price{font-size:24px;font-weight:800;color:#e53935;margin:6px 0 14px}
    .qty-form{display:flex;align-items:center;gap:12px;margin-top:16px;flex-wrap:wrap}
    .qty-form input{width:80px;padding:8px;text-align:center;border:1px solid #ccc;border-radius:6px;font-size:15px}
    .checkout-btn{background:var(--brand);border:none;padding:12px 20px;color:#fff;font-size:16px;border-radius:8px;cursor:pointer;font-weight:600}
    .checkout-btn:hover{background:var(--brand-dark)}

    /* SAFETY + FOOTER */
    .safety-info{margin-top:28px;padding:18px;background:#fff;border-left:4px solid var(--brand);border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
    .site-footer{background:#222;color:#fff;text-align:center;padding:20px 15px;font-family:'Poppins',sans-serif;margin-top:40px}
    .site-footer a{color:#ff6a00;text-decoration:none}
    .site-footer a:hover{text-decoration:underline}

    /* Responsive tweaks */
    @media (max-width:768px){
      .product-detail{padding:10px}
      .zoom-container{height:320px}
      .thumb img{width:56px;height:56px}
      .checkout-btn{width:100%}
      .lightbox-close{top:10px;right:10px;background:rgba(0,0,0,.5)}
    }
    @media (max-width:480px){
      .zoom-container{height:260px}
      .thumb img{width:48px;height:48px}
      .lightbox-nav{display:none}
    }
    
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo" style="display:flex;align-items:center;gap:10px;">
    <img src="safal sales logo.png" alt="Safal Sales Logo" style="height:55px;border-radius:70%;">
    <span>Safal Sales</span>
  </div>

  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php" class="active">Products</a></li>
    <li><a href="cart.php">MyCart🛒<span id="cartCount" class="cart-count">0</span></a></li>
  </ul>

  <div class="right-controls">
    <a href="admin_dashboard.php" class="admin-link">Admin</a>
  </div>

  <button class="menu-toggle" id="menuToggle" aria-label="Menu" aria-expanded="false">☰</button>
</nav>

<?php
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

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" aria-modal="true" role="dialog">
  <div class="lightbox-content" id="lightboxContent">
    <button class="lightbox-close" id="lightboxClose" aria-label="Close">&times;</button>
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Previous">‹</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="">
    <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Next">›</button>
    <div class="lightbox-counter" id="lightboxCounter"></div>
  </div>
</div>

<!-- PAGE -->
<div class="product-detail">
  <a href="products.php" class="back-link">← Back to Products</a>

  <div class="product-layout">
    <div class="media">
      <?php if (count($imgs) || $hasVideo): ?>
        <div class="slider">
          <?php if (count($imgs) > 1): ?><button class="slide-btn prev" aria-label="Previous">‹</button><?php endif; ?>
          <div class="slides">
            <?php foreach($imgs as $i => $src): ?>
              <div class="slide">
                <div class="zoom-container">
                  <img src="<?= htmlspecialchars($src) ?>" alt="Product image <?= $i+1 ?>" class="zoom-img" data-index="<?= $i ?>">
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($imgs) > 1): ?><button class="slide-btn next" aria-label="Next">›</button><?php endif; ?>

          <?php if (count($imgs) > 0): ?>
            <div class="thumb-container" aria-hidden="false">
              <?php foreach($imgs as $i => $src): ?>
                <button class="thumb" data-index="<?= $i ?>" type="button" aria-label="Show image <?= $i+1 ?>">
                  <img src="<?= htmlspecialchars($src) ?>" alt="thumb <?= $i+1 ?>">
                </button>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if ($hasVideo): ?>
            <div class="video-card">
              <a href="<?= htmlspecialchars($product['video']) ?>" target="_blank" rel="noopener">
                <div class="video-thumb">🎥</div>
                <div class="video-text">
                  <h3>Watch Product Video</h3>
                  <p>Click to view a quick demo or preview</p>
                </div>
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="zoom-container">
          <img src="placeholder.png" class="zoom-img" alt="No image">
        </div>
      <?php endif; ?>
    </div>

    <div class="product-info">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p class="price">₹<?= number_format((float)$product['price'], 2) ?></p>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

      <form class="qty-form" onsubmit="addToCart(event,'<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>', <?= (float)$product['price'] ?>, '<?= htmlspecialchars($product['thumbnail'], ENT_QUOTES) ?>')">
        <label for="qty">Quantity:</label>
        <input type="number" id="qty" value="1" min="1" inputmode="numeric">
        <button type="submit" class="checkout-btn">🛒 Add to Cart</button>
      </form>
    </div>
  </div>

  <div class="safety-info" role="note" aria-label="Safety Information">
    <h3>Safety Information</h3>
    <ul>
      <li>Always read and follow the instructions carefully.</li>
      <li>Keep away from children without adult supervision.</li>
      <li>Use in open areas away from buildings, vehicles, and flammable materials.</li>
      <li>Have water or a fire extinguisher nearby.</li>
      <li>Never attempt to relight a "dud" firework.</li>
    </ul>
  </div>
</div>

<footer class="site-footer">
  <div class="footer-content">
    <p>Contact Number: <a href="tel:+919425046286">+91 94250 46286</a></p>
    <p>&copy; <?= date('Y') ?> Safal Sales. All rights reserved.</p>
  </div>
</footer>

<!-- JS -->
<script>
/* NAV TOGGLE */
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menuToggle');
  const links  = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', () => {
      const open = links.classList.toggle('active');
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.textContent = open ? '✖' : '☰';
    });
    links.addEventListener('click', (e) => {
      if (e.target.closest('a')) {
        links.classList.remove('active');
        toggle.setAttribute('aria-expanded','false');
        toggle.textContent = '☰';
      }
    });
  }
});
</script>

<script>
/* SLIDER + LIGHTBOX + PAN/ZOOM */
document.addEventListener('DOMContentLoaded', () => {
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const lightboxClose = document.getElementById('lightboxClose');
  const lightboxPrev = document.getElementById('lightboxPrev');
  const lightboxNext = document.getElementById('lightboxNext');
  const lightboxCounter = document.getElementById('lightboxCounter');

  const productImages = document.querySelectorAll('.zoom-img');
  let currentImageIndex = 0;
  let images = [];

  productImages.forEach((img, index) => {
    images.push({ src: img.src, alt: img.alt });
    img.parentElement.addEventListener('dblclick', (e)=>{ e.preventDefault(); }); // prevent default dblclick
    img.parentElement.addEventListener('click', () => openLightbox(index));
  });

  function openLightbox(i){
    currentImageIndex = i;
    updateLightboxImage();
    lightbox.classList.add('active');
    document.body.style.overflow='hidden';
    lb.reset();
  }
  function closeLightbox(){
    lightbox.classList.remove('active');
    document.body.style.overflow='';
  }
  function updateLightboxImage(){
    if (!images.length) return;
    lightboxImg.src = images[currentImageIndex].src;
    lightboxImg.alt = images[currentImageIndex].alt || '';
    lightboxCounter.textContent = (currentImageIndex+1)+' / '+images.length;
  }
  function prevImage(){ if (currentImageIndex>0){ currentImageIndex--; updateLightboxImage(); lb.reset(); } }
  function nextImage(){ if (currentImageIndex<images.length-1){ currentImageIndex++; updateLightboxImage(); lb.reset(); } }

  lightboxClose.addEventListener('click', closeLightbox);
  lightboxPrev.addEventListener('click', prevImage);
  lightboxNext.addEventListener('click', nextImage);
  lightbox.addEventListener('click', (e)=>{ if(e.target===lightbox) closeLightbox(); });

  document.addEventListener('keydown', (e)=>{
    if (!lightbox.classList.contains('active')) return;
    if (e.key==='Escape') closeLightbox();
    if (e.key==='ArrowLeft') prevImage();
    if (e.key==='ArrowRight') nextImage();
  });

  // Slider controls
  const slidesWrap = document.querySelector('.slides');
  if (slidesWrap){
    const slides = Array.from(document.querySelectorAll('.slide'));
    const thumbs = Array.from(document.querySelectorAll('.thumb'));
    const btnPrev = document.querySelector('.slide-btn.prev');
    const btnNext = document.querySelector('.slide-btn.next');
    let index = 0, total = slides.length;

    function updateSlider(){
      slidesWrap.style.transform = `translateX(${-index*100}%)`;
      thumbs.forEach((t,i)=>t.classList.toggle('active', i===index));
    }
    btnPrev && btnPrev.addEventListener('click', ()=>{ index=(index-1+total)%total; updateSlider(); });
    btnNext && btnNext.addEventListener('click', ()=>{ index=(index+1)%total; updateSlider(); });
    thumbs.forEach(t => t.addEventListener('click', ()=>{ index=parseInt(t.dataset.index||'0',10); updateSlider(); }));
    updateSlider();
  }

  /* PAN & ZOOM (robust) */
  function PanZoom(container, img, {minScale=1, maxScale=6, dblScale=2.5} = {}){
    const state = {
      scale: 1, tx: 0, ty: 0,
      start: new Map(),   // pointerId -> {x,y}
      initial: {scale:1, dist:0, tx:0, ty:0, cx:0, cy:0},
      dragging:false
    };

    function setTransform(){
      img.style.transform = `translate(${state.tx}px, ${state.ty}px) scale(${state.scale})`;
    }
    function clamp(){
      const rect = container.getBoundingClientRect();
      const cw = rect.width, ch = rect.height;
      const maxTX = 0;
      const minTX = cw - cw*state.scale;
      const maxTY = 0;
      const minTY = ch - ch*state.scale;
      state.tx = Math.min(maxTX, Math.max(minTX, state.tx));
      state.ty = Math.min(maxTY, Math.max(minTY, state.ty));
    }
    function getDistAndCenter(){
      const pts = Array.from(state.start.values());
      const [p1,p2] = pts;
      const dist = Math.hypot(p2.x - p1.x, p2.y - p1.y);
      const cx = (p1.x + p2.x)/2;
      const cy = (p1.y + p2.y)/2;
      return {dist,cx,cy};
    }
    function zoomAt(clientX, clientY, nextScale){
      nextScale = Math.max(minScale, Math.min(maxScale, nextScale));
      const rect = container.getBoundingClientRect();
      const x = clientX - rect.left;
      const y = clientY - rect.top;

      const sx = (x - state.tx) / state.scale;
      const sy = (y - state.ty) / state.scale;

      state.scale = nextScale;
      state.tx = x - sx * state.scale;
      state.ty = y - sy * state.scale;

      clamp(); setTransform();
      container.classList.toggle('dragging', state.scale > 1 && state.dragging);
    }
    function reset(){
      state.scale = 1; state.tx = 0; state.ty = 0;
      setTransform();
    }

    // Wheel zoom
    container.addEventListener('wheel', (e)=>{
      e.preventDefault();
      const factor = e.deltaY < 0 ? 1.15 : 0.87;
      zoomAt(e.clientX, e.clientY, state.scale * factor);
    }, {passive:false});

    // Double click / Double tap
    let lastClick = 0;
    container.addEventListener('click', (e)=>{
      const now = Date.now();
      if (now - lastClick < 300){
        if (state.scale === 1) zoomAt(e.clientX, e.clientY, dblScale);
        else reset();
      }
      lastClick = now;
    });

    // Pointer events
    container.addEventListener('pointerdown', (e)=>{
      container.setPointerCapture?.(e.pointerId);
      state.start.set(e.pointerId, {x:e.clientX, y:e.clientY});
      if (state.start.size === 1){
        state.dragging = true;
        container.classList.add('dragging');
      } else if (state.start.size === 2){
        const {dist,cx,cy} = getDistAndCenter();
        state.initial = {scale: state.scale, dist, tx: state.tx, ty: state.ty, cx, cy};
      }
      e.preventDefault();
    }, {passive:false});

    container.addEventListener('pointermove', (e)=>{
      if (!state.start.has(e.pointerId)) return;
      const prev = state.start.get(e.pointerId);
      state.start.set(e.pointerId, {x:e.clientX, y:e.clientY});

      if (state.start.size === 1){
        if (state.scale > 1){
          state.tx += e.clientX - prev.x;
          state.ty += e.clientY - prev.y;
          clamp(); setTransform();
        }
      } else if (state.start.size === 2){
        const {dist,cx,cy} = getDistAndCenter();
        const scale = state.initial.scale * (dist / state.initial.dist);

        const rect = container.getBoundingClientRect();
        const x = cx - rect.left;
        const y = cy - rect.top;

        const sx = (x - state.tx) / state.scale;
        const sy = (y - state.ty) / state.scale;

        state.scale = Math.max(minScale, Math.min(maxScale, scale));
        state.tx = x - sx * state.scale;
        state.ty = y - sy * state.scale;

        clamp(); setTransform();
      }
      e.preventDefault();
    }, {passive:false});

    function endPointer(e){
      state.start.delete(e.pointerId);
      if (state.start.size === 0){
        state.dragging = false;
        container.classList.remove('dragging');
      }
    }
    container.addEventListener('pointerup', endPointer);
    container.addEventListener('pointercancel', endPointer);
    container.addEventListener('pointerleave', endPointer);

    return { reset, get scale(){return state.scale;} };
  }

  // Attach to gallery images
  const galleryInstances = [];
  document.querySelectorAll('.zoom-container').forEach((wrap)=>{
    const img = wrap.querySelector('.zoom-img');
    galleryInstances.push(PanZoom(wrap, img, {minScale:1, maxScale:6, dblScale:2.5}));
  });

  // Lightbox pan/zoom
  const lb = PanZoom(document.getElementById('lightboxContent'), lightboxImg, {minScale:1, maxScale:6, dblScale:2.5});

  // Swipe between images in lightbox only when not zoomed
  let touchStartX=0, touching=false;
  lightbox.addEventListener('touchstart', (e)=>{
    if (!lightbox.classList.contains('active')) return;
    touching = true; touchStartX = e.changedTouches[0].screenX;
  }, {passive:true});
  lightbox.addEventListener('touchend', (e)=>{
    if (!lightbox.classList.contains('active') || !touching) return;
    touching = false;
    if (lb.scale !== 1) return;
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 50){ dx>0 ? prevImage() : nextImage(); }
  }, {passive:true});
});
</script>

<script>
// Cart utils + Ticker button
function addToCart(event,name,price,img){
  event.preventDefault();
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let qty = parseInt(document.getElementById('qty').value) || 1;
  let existing = cart.find(item => item.name===name);
  if (existing){ existing.qty += qty; } else { cart.push({name, price:parseFloat(price), qty, img}); }
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartCount();
  alert(name + ' added to cart!');
}
function updateCartCount(){
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let total = cart.reduce((s,i)=>s+i.qty,0);
  const badge = document.getElementById('cartCount');
  if (badge) badge.textContent = total>0 ? total : 0;
}
window.addEventListener('load', updateCartCount);
window.addEventListener('storage', (e)=>{ if (e.key==='cart') updateCartCount(); });

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
