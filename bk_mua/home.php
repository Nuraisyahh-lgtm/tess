<?php
require 'db.php';
require 'cek_customer.php';

// ambil semua MUA
$qMua = mysqli_query($koneksi, "SELECT * FROM mua ORDER BY nama ASC");

// ambil rekomendasi rating tertinggi
$qTop = mysqli_query($koneksi, "SELECT * FROM mua ORDER BY rating DESC, nama ASC LIMIT 1");
$top  = mysqli_fetch_assoc($qTop);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beranda - Booking MUA</title>
  <link rel="stylesheet" href="assets/css/customer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; // atau tempel langsung kode navbar di atas ?>

<div class="home-wrapper">
  <div class="cards-container">
    <?php while ($m = mysqli_fetch_assoc($qMua)): ?>
      <div class="mua-card">
        <div class="mua-card-body">
          <a href="detail_mua.php?id=<?= $m['id'] ?>" class="mua-name">
            <?= htmlspecialchars($m['nama']) ?>
          </a>

          <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($m['spesialisasi']) ?></p>
          <p><strong>Harga:</strong> Rp<?= number_format($m['harga'], 0, ',', '.') ?></p>
          <p><strong>Jadwal:</strong> <?= htmlspecialchars($m['jadwal']) ?></p>

          <div class="mua-rating">
            <i class="fas fa-star" style="color:#ffb400;"></i>
            <?= number_format($m['rating'], 1) ?> / 5
          </div>

          <?php if ($m['ulasan_singkat']): ?>
            <p class="mua-quote">"<?= htmlspecialchars($m['ulasan_singkat']) ?>"</p>
          <?php endif; ?>

          <div class="mua-actions">
            <a href="booking_form.php?id_mua=<?= $m['id'] ?>" class="btn-pink">Booking</a>
            <a href="detail_mua.php?id=<?= $m['id'] ?>" class="btn-pink-outline">Detail</a>
            <a href="review_form.php?id_mua=<?= $m['id'] ?>" class="btn-pink">
              <i class="fas fa-star"></i> Beri Ulasan
            </a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="info-box">
    <h3>Info Tambahan</h3>
    <p>Selamat datang di layanan Booking MUA...</p>
    <?php if ($top): ?>
      <p>
        Rekomendasi MUA dengan rating tertinggi saat ini:
        <strong><?= htmlspecialchars($top['nama']) ?></strong>
        (<?= number_format($top['rating'], 1) ?> / 5)
      </p>
    <?php endif; ?>
  </div>
</div>

<!-- floating WhatsApp kalau mau -->
<a href="https://wa.me/6281234567890" target="_blank" class="wa-float">
  <i class="fab fa-whatsapp"></i>
</a>

</body>
</html>
