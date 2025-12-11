<?php
require 'db.php';
require 'cek_admin.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$nama = $spe = $jadwal = $ulasan = $wa = "";
$harga = 0;
$rating = 0;

if ($id > 0) {
    $q = mysqli_query($koneksi, "SELECT * FROM mua WHERE id=$id");
    if ($row = mysqli_fetch_assoc($q)) {
        $nama   = $row['nama'];
        $spe    = $row['spesialisasi'];
        $harga  = $row['harga'];
        $jadwal = $row['jadwal'];
        $rating = $row['rating'];
        $ulasan = $row['ulasan_singkat'];
        $wa     = $row['whatsapp'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $spe    = mysqli_real_escape_string($koneksi, $_POST['spe']);
    $harga  = (int)$_POST['harga'];
    $jadwal = mysqli_real_escape_string($koneksi, $_POST['jadwal']);
    $rating = (float)$_POST['rating'];
    $ulasan = mysqli_real_escape_string($koneksi, $_POST['ulasan']);
    $wa     = mysqli_real_escape_string($koneksi, $_POST['wa']);

    if ($id > 0) {
        $sql = "UPDATE mua SET 
                    nama='$nama',
                    spesialisasi='$spe',
                    harga=$harga,
                    jadwal='$jadwal',
                    rating=$rating,
                    ulasan_singkat='$ulasan',
                    whatsapp='$wa'
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO mua (nama,spesialisasi,harga,jadwal,rating,ulasan_singkat,whatsapp)
                VALUES('$nama','$spe',$harga,'$jadwal',$rating,'$ulasan','$wa')";
    }

    mysqli_query($koneksi, $sql);
    header("Location: admin_mua.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? "Edit" : "Tambah" ?> MUA</title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="form-wrapper">
  <h2><?= $id ? "Edit" : "Tambah" ?> MUA</h2>

  <form method="POST" class="form-card">
    <label>Nama MUA</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>

    <label>Spesialisasi</label>
    <input type="text" name="spe" value="<?= htmlspecialchars($spe) ?>" required>

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $harga ?>" required>

    <label>Jadwal</label>
    <input type="text" name="jadwal" value="<?= htmlspecialchars($jadwal) ?>" required>

    <label>Rating (0 - 5)</label>
    <input type="number" step="0.1" min="0" max="5" name="rating" value="<?= $rating ?>">

    <label>Ulasan singkat</label>
    <input type="text" name="ulasan" value="<?= htmlspecialchars($ulasan) ?>">

    <label>No WhatsApp (opsional)</label>
    <input type="text" name="wa" value="<?= htmlspecialchars($wa) ?>">

    <div class="form-actions">
      <a href="admin_mua.php" class="btn-pink-outline">Batal</a>
      <button type="submit" class="btn-pink">Simpan</button>
    </div>
  </form>
</div>

</body>
</html>
