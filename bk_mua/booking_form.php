<?php
require 'db.php';
require 'cek_customer.php';

$id_mua = isset($_GET['id_mua']) ? (int)$_GET['id_mua'] : 0;
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mua WHERE id=$id_mua"));
if (!$m) {
    die("MUA tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['user_id'];
    $tgl     = $_POST['tanggal'];
    $jam     = $_POST['jam'];
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    mysqli_query(
        $koneksi,
        "INSERT INTO bookings (id_user,id_mua,tanggal,jam,alamat,catatan)
         VALUES ($id_user,$id_mua,'$tgl','$jam','$alamat','$catatan')"
    );

    header("Location: booking_saya.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking - <?= htmlspecialchars($m['nama']) ?></title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="form-wrapper">
  <h2>Booking - <?= htmlspecialchars($m['nama']) ?></h2>

  <form method="POST" class="form-card">
    <label>Tanggal</label>
    <input type="date" name="tanggal" required>

    <label>Jam</label>
    <input type="time" name="jam" required>

    <label>Alamat</label>
    <textarea name="alamat" required></textarea>

    <label>Catatan</label>
    <textarea name="catatan"></textarea>

    <div class="form-actions">
      <a href="home.php" class="btn-pink-outline">Batal</a>
      <button type="submit" class="btn-pink">Simpan Booking</button>
    </div>
  </form>
</div>

</body>
</html>
