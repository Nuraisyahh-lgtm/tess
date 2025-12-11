<?php
require 'db.php';
require 'cek_admin.php';

$qMua = mysqli_query($koneksi, "SELECT * FROM mua ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Data MUA</title>
  <link rel="stylesheet" href="assets/css/customer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="home-wrapper"><!-- pakai layout home supaya background & grid sama -->

  <div class="admin-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="color:#4c1d95;">Kelola Data MUA</h2>
    <a href="admin_mua_form.php" class="btn-pink">
      <i class="fas fa-plus"></i> Tambah MUA
    </a>
  </div>

  <div class="cards-container">
    <?php while ($m = mysqli_fetch_assoc($qMua)): ?>
      <div class="mua-card">

        <a href="detail_mua.php?id=<?= $m['id'] ?>" class="mua-name">
          <?= htmlspecialchars($m['nama']) ?>
        </a>

        <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($m['spesialisasi']) ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($m['harga'], 0, ',', '.') ?></p>
        <p><strong>Jadwal:</strong> <?= htmlspecialchars($m['jadwal']) ?></p>

        <p class="mua-rating">
          <i class="fas fa-star" style="color:#ffb400;"></i>
          <?= number_format($m['rating'], 1) ?> / 5
        </p>

        <?php if ($m['ulasan_singkat']): ?>
          <p class="mua-quote">"<?= htmlspecialchars($m['ulasan_singkat']) ?>"</p>
        <?php endif; ?>

        <div class="mua-actions">

          <!-- Tombol WA (opsional, kalau kolom whatsapp di DB terisi) -->
          <?php if (!empty($m['whatsapp'])): ?>
            <?php
              $pesan   = urlencode("Halo kak, saya ingin bertanya mengenai layanan MUA *{$m['nama']}*.");
              $wa_link = "https://wa.me/{$m['whatsapp']}?text={$pesan}";
            ?>
            <a href="<?= $wa_link ?>" target="_blank"
               class="btn-small"
               style="background:#25D366; color:white;">
              <i class="fab fa-whatsapp"></i> WA
            </a>
          <?php endif; ?>

          <!-- TOMBOL BARU: KELOLA FOTO -->
          <a href="admin_mua_photos.php?id_mua=<?= $m['id'] ?>"
             class="btn-small"
             style="background:#6366f1; color:white;">
            <i class="fas fa-images"></i> Kelola Foto
          </a>

          <!-- Edit -->
          <a href="admin_mua_form.php?id=<?= $m['id'] ?>" class="btn-small"
             style="background:#22c55e; color:white;">
            <i class="fas fa-edit"></i> Edit
          </a>

          <!-- Hapus -->
          <a href="admin_mua_delete.php?id=<?= $m['id'] ?>"
             onclick="return confirm('Yakin ingin menghapus data ini?')"
             class="btn-small-danger">
            <i class="fas fa-trash"></i> Hapus
          </a>

        </div>

      </div>
    <?php endwhile; ?>
  </div>

</div>

</body>
</html>
