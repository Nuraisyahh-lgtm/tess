<?php
require 'db.php';
require 'cek_admin.php';

$id_mua = isset($_GET['id_mua']) ? (int)$_GET['id_mua'] : 0;
if ($id_mua <= 0) {
    die("MUA tidak diketahui.");
}

// ambil data MUA untuk judul
$mua = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mua WHERE id=$id_mua"));
if (!$mua) {
    die("MUA tidak ditemukan.");
}

// hapus foto
if (isset($_GET['hapus'])) {
    $id_foto = (int)$_GET['hapus'];
    $foto = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mua_photos WHERE id=$id_foto AND id_mua=$id_mua"));
    if ($foto) {
        $path = __DIR__ . "/uploads/mua/" . $foto['file'];
        if (is_file($path)) {
            unlink($path);
        }
        mysqli_query($koneksi, "DELETE FROM mua_photos WHERE id=$id_foto");
    }
    header("Location: admin_mua_photos.php?id_mua=$id_mua");
    exit;
}

// upload foto baru
$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['foto']['name'])) {
        $caption = mysqli_real_escape_string($koneksi, $_POST['caption'] ?? "");

        $folder = __DIR__ . "/uploads/mua/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $nama_file = $_FILES['foto']['name'];
        $tmp       = $_FILES['foto']['tmp_name'];

        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            $err = "Format file harus JPG, JPEG, PNG, atau WEBP.";
        } else {
            $newName = "mua{$id_mua}_" . time() . "_" . rand(1000,9999) . "." . $ext;
            $target = $folder . $newName;

            if (move_uploaded_file($tmp, $target)) {
                mysqli_query(
                    $koneksi,
                    "INSERT INTO mua_photos (id_mua,file,caption)
                     VALUES ($id_mua,'$newName','$caption')"
                );
                header("Location: admin_mua_photos.php?id_mua=$id_mua");
                exit;
            } else {
                $err = "Gagal mengupload file.";
            }
        }
    } else {
        $err = "Pilih foto terlebih dahulu.";
    }
}

// ambil semua foto
$qFoto = mysqli_query($koneksi,
          "SELECT * FROM mua_photos WHERE id_mua=$id_mua ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Foto - <?= htmlspecialchars($mua['nama']) ?></title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="admin-wrapper">
  <div class="admin-header">
    <div>
      <h2>Foto Makeup - <?= htmlspecialchars($mua['nama']) ?></h2>
      <p class="text-muted">
        Upload beberapa foto hasil makeup untuk ditampilkan di halaman detail MUA.
      </p>
    </div>
    <a href="admin_mua.php" class="btn-pink-outline">Kembali ke Data MUA</a>
  </div>

  <div class="form-wrapper">
    <?php if ($err): ?>
      <div class="alert-error"><?= $err ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">
      <label>Foto Makeup (JPG, PNG, WEBP)</label>
      <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" required>

      <label>Caption (opsional)</label>
      <input type="text" name="caption" placeholder="Contoh: Look akad, natural glam">

      <div class="form-actions">
        <button type="submit" class="btn-pink">Upload Foto</button>
      </div>
    </form>
  </div>

  <div class="gallery-wrapper">
    <?php if (mysqli_num_rows($qFoto) === 0): ?>
      <p class="text-muted">Belum ada foto diupload.</p>
    <?php else: ?>
      <div class="gallery-grid">
        <?php while ($f = mysqli_fetch_assoc($qFoto)): ?>
          <div class="gallery-card">
            <img src="uploads/mua/<?= htmlspecialchars($f['file']) ?>" alt="Foto MUA">
            <?php if ($f['caption']): ?>
              <p class="gallery-caption"><?= htmlspecialchars($f['caption']) ?></p>
            <?php endif; ?>
            <a href="admin_mua_photos.php?id_mua=<?= $id_mua ?>&hapus=<?= $f['id'] ?>"
               class="btn-small-danger"
               onclick="return confirm('Hapus foto ini?');">
               Hapus
            </a>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
