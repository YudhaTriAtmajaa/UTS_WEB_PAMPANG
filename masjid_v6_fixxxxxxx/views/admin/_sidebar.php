<?php
$activePage = $activePage ?? 'dashboard';
$adminUsername = $_SESSION['admin_username'] ?? 'admin';
?>
<aside class="admin-sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon"><i class="fa-solid fa-mosque"></i></div>
    <div class="sidebar-brand-name">Panel Admin</div>
    <div class="sidebar-brand-sub">Masjid Raya Darussalam</div>
  </div>

  <div class="sidebar-section-label">Kelola Konten</div>
  <a href="dashboard.php" class="sidebar-link <?= $activePage==='dashboard' ? 'active':'' ?>"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
  <a href="kelola_ulasan.php" class="sidebar-link <?= $activePage==='ulasan'    ? 'active':'' ?>"><i class="fa-regular fa-comment-dots"></i> Kelola Ulasan</a>
  <a href="kelola_fasilitas.php" class="sidebar-link <?= $activePage==='fasilitas' ? 'active':'' ?>"><i class="fa-solid fa-building-columns"></i> Kelola Fasilitas</a>
  <a href="kelola_galeri.php" class="sidebar-link <?= $activePage==='galeri'    ? 'active':'' ?>"><i class="fa-solid fa-images"></i> Kelola Galeri & Video</a>

  <div class="sidebar-section-label">Halaman Publik</div>
  <a href="../../index.php" class="sidebar-link"><i class="fa-solid fa-house"></i> Beranda</a>
  <a href="../../views/detail.php" class="sidebar-link"><i class="fa-solid fa-mosque"></i> Detail &amp; Fasilitas</a>
  <a href="../../views/ulasan.php" class="sidebar-link"><i class="fa-regular fa-comment-dots"></i> Halaman Ulasan</a>

  <div class="sidebar-bottom">
    <div class="sidebar-user" style="text-align:center; margin-bottom:.65rem;">
      Login sebagai: <strong><?= htmlspecialchars($adminUsername) ?></strong>
    </div>
    <button class="act-btn act-delete w-100" style="justify-content:center;padding:.6rem;font-size:.82rem" onclick="window._ctrl.confirmLogout()" title="Logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </button>
  </div>
</aside>