<?php
if (!isset($pageTitle))  $pageTitle  = SITE_NAME;
if (!isset($activePage)) $activePage = 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> - <?= SITE_NAME ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- AOS Animate on Scroll -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- ICON TAB -->
  <link rel="icon" type="image/png" href="assets/img/logo.png">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<header class="pampang-nav" id="mainNav">
  <div class="container-fluid px-4 px-lg-5 d-flex align-items-center justify-content-between">
    <!-- Brand -->
    <a class="nav-brand" href="index.php" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
      <img src="assets/img/logo.png" alt="Logo Pampang" style="height:36px; width:auto; object-fit:contain;">
      <div>
        <div class="nav-brand-name">Desa Wisata Pampang</div>
        <div class="nav-brand-sub">Samarinda, Kalimantan Timur</div>
      </div>
    </a>

    <!-- Desktop Links -->
    <ul class="nav-links d-none d-lg-flex list-unstyled mb-0 gap-1">
      <li><a href="index.php"    class="nav-link-item <?= $activePage==='home'     ? 'active':'' ?>">Home</a></li>
      <li><a href="about.php"    class="nav-link-item <?= $activePage==='about'    ? 'active':'' ?>">About</a></li>
      <li><a href="services.php" class="nav-link-item <?= $activePage==='services' ? 'active':'' ?>">Services</a></li>
      <li><a href="ulasan.php"   class="nav-link-item <?= $activePage==='ulasan'   ? 'active':'' ?>">Reviews</a></li>
    </ul>

    <!-- Hamburger -->
    <button class="hamburger d-lg-none" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-overlay" id="mobileOverlay"></div>
<nav class="mobile-menu" id="mobileMenu">
  <button class="mobile-close" id="mobileClose"><i class="bi bi-x-lg"></i></button>
  <a href="index.php"    class="<?= $activePage==='home'     ? 'active':'' ?>"><i class="bi bi-house-door me-2"></i>Home</a>
  <a href="about.php"    class="<?= $activePage==='about'    ? 'active':'' ?>"><i class="bi bi-info-circle me-2"></i>About</a>
  <a href="services.php" class="<?= $activePage==='services' ? 'active':'' ?>"><i class="bi bi-map me-2"></i>Wisata</a>
  <a href="ulasan.php"   class="<?= $activePage==='ulasan'   ? 'active':'' ?>"><i class="bi bi-chat-square-text me-2"></i>Ulasan</a>
</nav>
