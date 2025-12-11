<?php
// file ini diletakkan di root yang sama dengan file lain
?>
<nav class="top-navbar">
  <div class="container-nav">
    <div class="logo-left">
      <span class="logo-icon">💄</span>
      <span class="logo-text">Booking MUA</span>
    </div>
    <ul class="nav-menu">
      <?php if ($_SESSION['role'] === 'customer'): ?>
        <li><a href="home.php">Beranda</a></li>
        <li><a href="account.php">Atur Akun</a></li>
        <li><a href="booking_saya.php">Booking Saya</a></li>
      <?php else: ?>
        <li><a href="admin_mua.php">Data MUA</a></li>
        <li><a href="admin_booking.php">Data Booking</a></li>
      <?php endif; ?>
      <li><a href="logout.php">Keluar</a></li>
    </ul>
  </div>
</nav>
