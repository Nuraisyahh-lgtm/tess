<?php
require 'db.php';
require 'cek_customer.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mua WHERE id=$id"));
if (!$m) {
    die("MUA tidak ditemukan.");
}

// ambil ulasan
$rev = mysqli_query(
    $koneksi,
    "SELECT r.*, u.username
     FROM reviews r
     JOIN users u ON r.id_user = u.id
     WHERE r.id_mua=$id
     ORDER BY r.created_at DESC
     LIMIT 5"
);

// ambil foto makeup
$qFoto = mysqli_query(
    $koneksi,
    "SELECT * FROM mua_photos WHERE id_mua=$id ORDER BY created_at DESC LIMIT 8"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail MUA - <?= htmlspecialchars($m['nama']) ?></title>
  <link rel="stylesheet" href="assets/css/customer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="detail-wrapper">
  <!-- KARTU INFO MUA -->
  <div class="detail-main">
    <div class="detail-card">
      <h2><?= htmlspecialchars($m['nama']) ?></h2>
      <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($m['spesialisasi']) ?></p>
      <p><strong>Harga:</strong> Rp<?= number_format($m['harga'], 0, ',', '.') ?></p>
      <p><strong>Jadwal:</strong> <?= htmlspecialchars($m['jadwal']) ?></p>
      <p><strong>Rating:</strong> <?= number_format($m['rating'], 1) ?> / 5</p>
      <?php if ($m['ulasan_singkat']): ?>
        <p><em>"<?= htmlspecialchars($m['ulasan_singkat']) ?>"</em></p>
      <?php endif; ?>

      <div class="detail-actions">
        <a href="booking_form.php?id_mua=<?= $m['id'] ?>" class="btn-pink">Booking</a>
        <a href="review_form.php?id_mua=<?= $m['id'] ?>" class="btn-pink-outline">
          <i class="fas fa-star"></i> Beri Ulasan
        </a>
        <a href="home.php" class="btn-link">Kembali</a>
      </div>
    </div>

    <!-- ULASAN TERBARU -->
    <div class="detail-card" style="margin-top:16px;">
      <h3>Ulasan Terbaru</h3>
      <?php if (mysqli_num_rows($rev) === 0): ?>
        <p class="text-muted">Belum ada ulasan.</p>
      <?php else: ?>
        <?php while ($r = mysqli_fetch_assoc($rev)): ?>
          <div class="review-card">
            <div class="review-header">
              <span class="review-user"><?= htmlspecialchars($r['username']) ?></span>
              <span class="review-rating"><?= $r['rating'] ?>/5</span>
            </div>
            <div class="review-body">
              <?= nl2br(htmlspecialchars($r['komentar'])) ?>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- GALERI FOTO MAKEUP -->
  <div class="detail-side">
    <h3>Foto Hasil Makeup</h3>
    <?php if (mysqli_num_rows($qFoto) === 0): ?>
      <p class="text-muted">Belum ada foto yang ditambahkan.</p>
    <?php else: ?>
      <div class="gallery-grid">
        <?php while ($f = mysqli_fetch_assoc($qFoto)): ?>
          <div class="gallery-card">
            <img src="uploads/mua/<?= htmlspecialchars($f['file']) ?>" alt="Foto makeup">
            <?php if ($f['caption']): ?>
              <p class="gallery-caption"><?= htmlspecialchars($f['caption']) ?></p>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
