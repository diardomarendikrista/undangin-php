<?php
// Cek session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$fullname  = $_SESSION['full_name'] ?? '';

$p = isset($path) ? $path : '';
?>

<nav class="navbar">
  <div class="container nav-flex">

    <a href="<?= $isLoggedIn ? $p . "dashboard/index.php" : $p . "index.php" ?>" class="brand">
      UndangIn
    </a>

    <div class="nav-links">
      <?php if ($isLoggedIn): ?>

        <a href="<?= $p ?>dashboard/form_event.php" class="btn btn-primary nav-btn-add">
          + Undangan Baru
        </a>

        <div class="nav-dropdown">

          <button onclick="toggleNavbarDropdown()" class="nav-dropdown-toggle">
            Halo, <strong><?= $fullname ?></strong> ▼
          </button>

          <div id="navbarDropdown" class="nav-dropdown-content">
            <a href="<?= $p ?>dashboard/index.php">Dashboard</a>
            <a href="<?= $p ?>dashboard/profile.php">Profil Saya</a>
            <a href="<?= $p ?>index.php">Halaman Utama</a>
            <hr class="nav-hr-logout">
            <a href="<?= $p ?>logout.php" class="nav-btn-logout">Keluar</a>
          </div>

        </div>

      <?php else: ?>
        <div class="nav-group-buttons">
          <a href="<?= $p ?>auth/login.php">Masuk</a>
          <a href="<?= $p ?>auth/register.php" class="btn btn-primary">Buat Undangan</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>